<?php
session_start();
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

// Obtener usuario de la sesión
$usuario = $_SESSION['usuario'];

// Consulta para obtener `id_usuario`
$query_usuario = "SELECT id_usuario FROM tbl_usuarios WHERE nombre_user = ?";
$stmt_usuario = mysqli_prepare($conexion, $query_usuario);
mysqli_stmt_bind_param($stmt_usuario, "s", $usuario);
mysqli_stmt_execute($stmt_usuario);
mysqli_stmt_bind_result($stmt_usuario, $id_usuario);
mysqli_stmt_fetch($stmt_usuario);
mysqli_stmt_close($stmt_usuario);

// Verificar la categoría seleccionada
$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consultar solo las salas que pertenecen a la categoría seleccionada
$query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = ?";
$stmt_salas = mysqli_prepare($conexion, $query_salas);
mysqli_stmt_bind_param($stmt_salas, "s", $categoria_seleccionada);
mysqli_stmt_execute($stmt_salas);
$result_salas = mysqli_stmt_get_result($stmt_salas);

// Manejar cambio de estado de mesas
if (isset($_POST['cambiar_estado'])) {
    $mesa_id = $_POST['mesa_id'];
    $estado_nuevo = $_POST['estado'] == 'libre' ? 'ocupada' : 'libre';
    $fecha_hora = date("Y-m-d H:i:s");

    // Cambiar estado de la mesa
    $query_update = "UPDATE tbl_mesas SET estado = ? WHERE id_mesa = ?";
    $stmt_update = mysqli_prepare($conexion, $query_update);
    mysqli_stmt_bind_param($stmt_update, "si", $estado_nuevo, $mesa_id);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Registrar ocupación
    if ($estado_nuevo == 'ocupada') {
        $query_insert = "INSERT INTO tbl_ocupaciones (id_usuario, id_mesa, fecha_inicio) VALUES (?, ?, ?)";
        $stmt_insert = mysqli_prepare($conexion, $query_insert);
        mysqli_stmt_bind_param($stmt_insert, "iis", $id_usuario, $mesa_id, $fecha_hora);
        mysqli_stmt_execute($stmt_insert);
        mysqli_stmt_close($stmt_insert);
    } else {
        $query_end = "UPDATE tbl_ocupaciones SET fecha_fin = ? WHERE id_mesa = ? AND fecha_fin IS NULL";
        $stmt_end = mysqli_prepare($conexion, $query_end);
        mysqli_stmt_bind_param($stmt_end, "si", $fecha_hora, $mesa_id);
        mysqli_stmt_execute($stmt_end);
        mysqli_stmt_close($stmt_end);
    }
    header("Location: mostrar.php?categoria=$categoria_seleccionada");
    exit();
}

// Mostrar mesas y su estado
echo "<h2>Estado de las mesas - $categoria_seleccionada</h2>";

// Verificar si hay resultados de salas
if ($result_salas && mysqli_num_rows($result_salas) > 0) {
    while ($sala = mysqli_fetch_assoc($result_salas)) {
        echo "<h3>Sala: " . htmlspecialchars($sala['nombre_sala']) . " (Capacidad: " . htmlspecialchars($sala['capacidad']) . " personas)</h3>";
        
        $query_mesas = "SELECT * FROM tbl_mesas WHERE id_sala = ?";
        $stmt_mesas = mysqli_prepare($conexion, $query_mesas);
        mysqli_stmt_bind_param($stmt_mesas, "i", $sala['id_sala']);
        mysqli_stmt_execute($stmt_mesas);
        $result_mesas = mysqli_stmt_get_result($stmt_mesas);
        
        if ($result_mesas && mysqli_num_rows($result_mesas) > 0) {
            echo "<table border='1'><tr><th>Mesa</th><th>Capacidad</th><th>Estado</th><th>Acción</th></tr>";
            while ($mesa = mysqli_fetch_assoc($result_mesas)) {
                $numero_mesa = isset($mesa['numero_mesa']) ? htmlspecialchars($mesa['numero_mesa']) : 'N/A';
                $capacidad_mesa = isset($mesa['capacidad_mesa']) ? htmlspecialchars($mesa['capacidad_mesa']) : 'N/A';
                $estado_actual = isset($mesa['estado']) ? htmlspecialchars($mesa['estado']) : 'libre';
                $estado_opuesto = $estado_actual === 'libre' ? 'Ocupar' : 'Liberar';
                
                echo "<tr>
                    <td>{$numero_mesa}</td>
                    <td>{$capacidad_mesa}</td>
                    <td>{$estado_actual}</td>
                    <td>
                        <form method='POST' action='mostrar.php?categoria=$categoria_seleccionada'>
                            <input type='hidden' name='mesa_id' value='" . htmlspecialchars($mesa['id_mesa']) . "'>
                            <input type='hidden' name='estado' value='{$estado_actual}'>
                            <button type='submit' name='cambiar_estado'>{$estado_opuesto}</button>
                        </form>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay mesas registradas para esta sala.</p>";
        }
        mysqli_stmt_close($stmt_mesas);
    }
} else {
    echo "<p>No hay salas disponibles para la categoría seleccionada.</p>";
}

mysqli_stmt_close($stmt_salas);
mysqli_close($conexion);
?>
