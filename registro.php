<?php
session_start();
require_once('./php/conexion.php');

// Verificar sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

echo "<h2>Historial de ocupaciones</h2>";

// Filtros de usuario, sala, mesa y estado
echo "<form method='GET' action='registro.php'>
    <label for='usuario'>Usuario:</label>
    <select name='usuario'>
        <option value=''>Todos</option>";
$query_usuarios = "SELECT id_usuario, nombre_user FROM tbl_usuarios";
$result_usuarios = mysqli_query($conexion, $query_usuarios);
while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
    $selected = isset($_GET['usuario']) && $_GET['usuario'] == $usuario['id_usuario'] ? 'selected' : '';
    echo "<option value='{$usuario['id_usuario']}' $selected>{$usuario['nombre_user']}</option>";
}
echo "</select>";

echo "<label for='sala'>Sala:</label>
    <select name='sala'>
        <option value=''>Todas</option>";
$query_salas = "SELECT id_sala, nombre_sala FROM tbl_salas";
$result_salas = mysqli_query($conexion, $query_salas);
while ($sala = mysqli_fetch_assoc($result_salas)) {
    $selected = isset($_GET['sala']) && $_GET['sala'] == $sala['id_sala'] ? 'selected' : '';
    echo "<option value='{$sala['id_sala']}' $selected>{$sala['nombre_sala']}</option>";
}
echo "</select>";

echo "<label for='mesa'>Mesa:</label>
    <select name='mesa'>
        <option value=''>Todas</option>";
$query_mesas = "SELECT id_mesa, numero_mesa FROM tbl_mesas";
$result_mesas = mysqli_query($conexion, $query_mesas);
while ($mesa = mysqli_fetch_assoc($result_mesas)) {
    $selected = isset($_GET['mesa']) && $_GET['mesa'] == $mesa['id_mesa'] ? 'selected' : '';
    echo "<option value='{$mesa['id_mesa']}' $selected>{$mesa['numero_mesa']}</option>";
}
echo "</select>";

echo "<label for='estado'>Estado Sala:</label>
    <select name='estado'>
        <option value=''>Todos</option>
        <option value='libre' ".(isset($_GET['estado']) && $_GET['estado'] == 'libre' ? 'selected' : '').">Libre</option>
        <option value='ocupada' ".(isset($_GET['estado']) && $_GET['estado'] == 'ocupada' ? 'selected' : '').">Ocupada</option>
    </select>";

// Botones para filtrar y borrar filtros
echo "<button type='submit'>Filtrar</button>
      <button type='button' onclick='window.location.href=\"registro.php\"'>Borrar Filtros</button>
</form>";

// Variables para los filtros
$usuario_filter = isset($_GET['usuario']) && !empty($_GET['usuario']) ? $_GET['usuario'] : '';
$sala_filter = isset($_GET['sala']) && !empty($_GET['sala']) ? $_GET['sala'] : '';
$mesa_filter = isset($_GET['mesa']) && !empty($_GET['mesa']) ? $_GET['mesa'] : '';
$estado_filter = isset($_GET['estado']) && !empty($_GET['estado']) ? $_GET['estado'] : '';

// Construcción de la consulta SQL con filtros
$query_historial = "SELECT u.nombre_user, s.nombre_sala, m.numero_mesa, m.estado, 
                           o.fecha_inicio, o.fecha_fin, 
                           TIMESTAMPDIFF(MINUTE, o.fecha_inicio, o.fecha_fin) AS duracion
                    FROM tbl_ocupaciones o
                    JOIN tbl_mesas m ON o.id_mesa = m.id_mesa
                    JOIN tbl_salas s ON m.id_sala = s.id_sala
                    JOIN tbl_usuarios u ON o.id_usuario = u.id_usuario";

$filters = [];
if ($usuario_filter) {
    $filters[] = "u.id_usuario = '".mysqli_real_escape_string($conexion, $usuario_filter)."'";
}
if ($sala_filter) {
    $filters[] = "s.id_sala = '".mysqli_real_escape_string($conexion, $sala_filter)."'";
}
if ($mesa_filter) {
    $filters[] = "m.id_mesa = '".mysqli_real_escape_string($conexion, $mesa_filter)."'";
}
if ($estado_filter) {
    $filters[] = "m.estado = '".mysqli_real_escape_string($conexion, $estado_filter)."'";
}

if (!empty($filters)) {
    $query_historial .= " WHERE " . implode(" AND ", $filters);
}

$result_historial = mysqli_query($conexion, $query_historial);

// Mostrar resultados en tabla
echo "<table border='1'>
        <tr>
            <th>Usuario</th>
            <th>Sala</th>
            <th>Número de Mesa</th>
            <th>Estado</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Duración (minutos)</th>
        </tr>";

while ($ocupacion = mysqli_fetch_assoc($result_historial)) {
    echo "<tr>
            <td>{$ocupacion['nombre_user']}</td>
            <td>{$ocupacion['nombre_sala']}</td>
            <td>{$ocupacion['numero_mesa']}</td>
            <td>{$ocupacion['estado']}</td>
            <td>{$ocupacion['fecha_inicio']}</td>
            <td>{$ocupacion['fecha_fin']}</td>
            <td>{$ocupacion['duracion']}</td>
        </tr>";
}

echo "</table>";
?>
