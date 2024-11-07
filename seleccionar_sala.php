<?php
session_start();
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

$categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Consultar salas por tipo
$query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = ?";
$stmt_salas = mysqli_prepare($conexion, $query_salas);
mysqli_stmt_bind_param($stmt_salas, "s", $categoria_seleccionada);
mysqli_stmt_execute($stmt_salas);
$result_salas = mysqli_stmt_get_result($stmt_salas);

echo "<h2>Selecciona una sala de tipo $categoria_seleccionada</h2>";

if ($result_salas && mysqli_num_rows($result_salas) > 0) {
    while ($sala = mysqli_fetch_assoc($result_salas)) {
        echo "<div>
                <a href='./gestionar_mesas.php?categoria=" . urlencode($categoria_seleccionada) . "&id_sala=" . $sala['id_sala'] . "'>
                    <button>" . htmlspecialchars($sala['nombre_sala']) . "</button>
                </a>
              </div>";
    }
} else {
    echo "<p>No hay salas disponibles para esta categoría.</p>";
}

mysqli_stmt_close($stmt_salas);
mysqli_close($conexion);
?>
