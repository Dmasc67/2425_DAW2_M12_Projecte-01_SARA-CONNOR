<?php
session_start();
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

// Obtener el id_usuario desde la sesión
$usuario = $_SESSION['usuario'];

// Obtener id_usuario de la base de datos
$query_usuario = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_user = ?";
$stmt_usuario = mysqli_prepare($conexion, $query_usuario);
mysqli_stmt_bind_param($stmt_usuario, "s", $usuario);
mysqli_stmt_execute($stmt_usuario);
mysqli_stmt_bind_result($stmt_usuario, $id_usuario);
mysqli_stmt_fetch($stmt_usuario);
mysqli_stmt_close($stmt_usuario);

// Verificación de parámetros GET
if (isset($_GET['categoria']) && isset($_GET['id_sala'])) {
    $categoria_seleccionada = $_GET['categoria'];
    $id_sala = $_GET['id_sala'];

    // Mostrar los parámetros para depuración (quitar en producción)
    echo "Categoria seleccionada: " . htmlspecialchars($categoria_seleccionada) . "<br>";
    echo "ID Sala seleccionada: " . htmlspecialchars($id_sala) . "<br>";

    // Consultar las salas de acuerdo a la categoría seleccionada
    $query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = ? AND id_sala = ?";
    $stmt_salas = mysqli_prepare($conexion, $query_salas);
    mysqli_stmt_bind_param($stmt_salas, "si", $categoria_seleccionada, $id_sala);
    mysqli_stmt_execute($stmt_salas);
    $result_salas = mysqli_stmt_get_result($stmt_salas);

    if (mysqli_num_rows($result_salas) > 0) {
        // Si la sala existe, obtener las mesas de esa sala
        $query_mesas = "SELECT * FROM tbl_mesas WHERE id_sala = ?";
        $stmt_mesas = mysqli_prepare($conexion, $query_mesas);
        mysqli_stmt_bind_param($stmt_mesas, "i", $id_sala);
        mysqli_stmt_execute($stmt_mesas);
        $result_mesas = mysqli_stmt_get_result($stmt_mesas);

        echo "<h2>Mesas disponibles en la sala: $categoria_seleccionada - " . htmlspecialchars($id_sala) . "</h2>";

        if (mysqli_num_rows($result_mesas) > 0) {
            echo "<table border='1'><tr><th>Mesa</th><th>Estado</th><th>Acción</th></tr>";

            while ($mesa = mysqli_fetch_assoc($result_mesas)) {
                $estado_actual = htmlspecialchars($mesa['estado']);
                $estado_opuesto = $estado_actual === 'libre' ? 'Ocupar' : 'Liberar';

                // Verificar si la mesa está ocupada y quién la ocupa
                $mesa_id = $mesa['id_mesa'];
                $query_ocupacion = "SELECT id_usuario FROM tbl_ocupaciones WHERE id_mesa = ? AND fecha_fin IS NULL";
                $stmt_ocupacion = mysqli_prepare($conexion, $query_ocupacion);
                mysqli_stmt_bind_param($stmt_ocupacion, "i", $mesa_id);
                mysqli_stmt_execute($stmt_ocupacion);
                mysqli_stmt_bind_result($stmt_ocupacion, $id_usuario_ocupante);
                mysqli_stmt_fetch($stmt_ocupacion);
                mysqli_stmt_close($stmt_ocupacion);

                // Si la mesa está ocupada por el usuario actual, mostrar el botón de liberación
                // Si el usuario actual no es el que ocupó la mesa, el botón de liberar se desactiva
                $desactivar_boton = ($estado_actual === 'ocupada' && $id_usuario !== $id_usuario_ocupante);

                echo "<tr>
                    <td>" . htmlspecialchars($mesa['numero_mesa']) . "</td>
                    <td>" . $estado_actual . "</td>
                    <td>
                        <form method='POST' action='gestionar_mesas.php?categoria=$categoria_seleccionada&id_sala=$id_sala'>
                            <input type='hidden' name='mesa_id' value='" . htmlspecialchars($mesa['id_mesa']) . "'>
                            <input type='hidden' name='estado' value='" . $estado_actual . "'>
                            <button type='submit' name='cambiar_estado' " . ($desactivar_boton ? 'disabled' : '') . ">" . ($estado_opuesto === 'Liberar' && $desactivar_boton ? 'Tú no puedes liberar esta mesa' : $estado_opuesto) . "</button>
                        </form>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay mesas registradas en esta sala.</p>";
        }

        mysqli_stmt_close($stmt_mesas);
    } else {
        echo "<p>No se encontró la sala seleccionada o no corresponde a la categoría.</p>";
    }

    mysqli_stmt_close($stmt_salas);
} else {
    echo "<p>Faltan parámetros para la selección de sala o categoría.</p>";
}

// Manejar el cambio de estado de las mesas
if (isset($_POST['cambiar_estado'])) {
    $mesa_id = $_POST['mesa_id'];
    $estado_nuevo = $_POST['estado'] == 'libre' ? 'ocupada' : 'libre';
    $fecha_hora = date("Y-m-d H:i:s");

    // Actualizar estado de la mesa
    $query_update = "UPDATE tbl_mesas SET estado = ? WHERE id_mesa = ?";
    $stmt_update = mysqli_prepare($conexion, $query_update);
    mysqli_stmt_bind_param($stmt_update, "si", $estado_nuevo, $mesa_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Si la mesa se ocupa, insertar la ocupación
    if ($estado_nuevo == 'ocupada') {
        $query_insert = "INSERT INTO tbl_ocupaciones (id_usuario, id_mesa, fecha_inicio) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conexion, $query_insert);
        mysqli_stmt_bind_param($stmt_insert, "iis", $id_usuario, $mesa_id, $fecha_hora);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    } else {
        // Si la mesa se libera, actualizar la fecha de fin
        $query_end = "UPDATE tbl_ocupaciones SET fecha_fin = ? WHERE id_mesa = ? AND fecha_fin IS NULL";
        $stmt_end = mysqli_prepare($conexion, $query_end);
        mysqli_stmt_bind_param($stmt_end, "si", $fecha_hora, $mesa_id);
        mysqli_stmt_execute($stmt_end);
        mysqli_stmt_close($stmt_end);
    }

    // Redirigir después de cambiar el estado
    header("Location: gestionar_mesas.php?categoria=$categoria_seleccionada&id_sala=$id_sala");
    exit();
}

mysqli_close($conexion);
?>
