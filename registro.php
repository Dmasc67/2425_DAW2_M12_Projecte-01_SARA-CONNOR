<?php
session_start();
require_once('./php/conexion.php');

// Verificar sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

echo "<h2>Historial de ocupaciones</h2>";

// Filtros de sala y mesa
echo "<form method='GET' action='registro.php'>
    <label for='sala'>Sala:</label>
    <select name='sala'>
        <option value=''>Todas</option>";

$query_salas = "SELECT id_sala, nombre_sala FROM tbl_salas";
$result_salas = mysqli_query($conexion, $query_salas);
while ($sala = mysqli_fetch_assoc($result_salas)) {
    echo "<option value='{$sala['id_sala']}'>{$sala['nombre_sala']}</option>";
}

echo "</select>
    <label for='mesa'>Mesa:</label>
    <input type='text' name='mesa'>
    <button type='submit'>Filtrar</button>
</form>";

$sala_filter = isset($_GET['sala']) && !empty($_GET['sala']) ? $_GET['sala'] : '';
$mesa_filter = isset($_GET['mesa']) && !empty($_GET['mesa']) ? $_GET['mesa'] : '';

$query_historial = "SELECT o.id_ocupacion, m.numero_mesa, s.nombre_sala, o.fecha_inicio, o.fecha_fin, u.nombre_user
                    FROM tbl_ocupaciones o
                    JOIN tbl_mesas m ON o.id_mesa = m.id_mesa
                    JOIN tbl_salas s ON m.id_sala = s.id_sala
                    JOIN tbl_usuarios u ON o.id_usuario = u.id_usuario";

$filters = [];
if ($sala_filter) {
    $filters[] = "s.id_sala = '".mysqli_real_escape_string($conexion, $sala_filter)."'";
}
if ($mesa_filter) {
    $filters[] = "m.numero_mesa LIKE '%".mysqli_real_escape_string($conexion, $mesa_filter)."%'";
}

if (!empty($filters)) {
    $query_historial .= " WHERE " . implode(" AND ", $filters);
}

$result_historial = mysqli_query($conexion, $query_historial);
echo "<table border='1'><tr><th>Mesa</th><th>Sala</th><th>Inicio</th><th>Fin</th><th>Camarero</th></tr>";
while ($ocupacion = mysqli_fetch_assoc($result_historial)) {
    echo "<tr>
        <td>{$ocupacion['numero_mesa']}</td>
        <td>{$ocupacion['nombre_sala']}</td>
        <td>{$ocupacion['fecha_inicio']}</td>
        <td>{$ocupacion['fecha_fin']}</td>
        <td>{$ocupacion['nombre_user']}</td>
    </tr>";
}
echo "</table>";
?>
