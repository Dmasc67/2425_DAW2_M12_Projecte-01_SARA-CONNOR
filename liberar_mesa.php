<?php
session_start();
require_once('./php/conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}

$usuario_id = $_SESSION['usuario_id']; // ID del usuario logueado
$ocupacion_id = $_GET['id_ocupacion']; // ID de la ocupación de la mesa

// Verificar si la mesa fue ocupada por el usuario logueado
$query = "SELECT id_usuario FROM tbl_ocupaciones WHERE id_ocupacion = '$ocupacion_id'";
$result = mysqli_query($conexion, $query);
$ocupacion = mysqli_fetch_assoc($result);

if ($ocupacion && $ocupacion['id_usuario'] == $usuario_id) {
    // Si el usuario logueado ocupó esta mesa, liberar la mesa
    $query_liberar = "UPDATE tbl_mesas SET estado = 'libre' WHERE id_mesa = (SELECT id_mesa FROM tbl_ocupaciones WHERE id_ocupacion = '$ocupacion_id')";
    mysqli_query($conexion, $query_liberar);

    // Eliminar la ocupación
    $query_eliminar_ocupacion = "DELETE FROM tbl_ocupaciones WHERE id_ocupacion = '$ocupacion_id'";
    mysqli_query($conexion, $query_eliminar_ocupacion);

    // Redirigir después de liberar la mesa
    header("Location: registro.php?success=mesa_liberada");
    exit();
} else {
    // Si no coincide, mostrar mensaje de error
    header("Location: registro.php?error=no_permisos_para_liberar");
    exit();
}
?>
