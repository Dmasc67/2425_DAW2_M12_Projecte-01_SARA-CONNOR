<?php
session_start();
require_once('./php/conexion.php');

// Verificación de sesión iniciada
if (!isset($_SESSION['usuario'])) {
    header("Location: index.php?error=sesion_no_iniciada");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <nav class="navegacion">
        <!-- Sección izquierda con el logo grande y el ícono adicional más pequeño -->
        <div class="navbar-left">
            <a href="./menu.php"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            <a href="./registro.php"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
        </div>

        <!-- Título en el centro -->
        <div class="navbar-title">
            <h3>Bienvenido <?php if (isset($_SESSION['usuario'])){echo $_SESSION['usuario'];}?></h3>
        </div>

        <!-- Icono de logout a la derecha -->
        <div class="navbar-right">
            <a href="./salir.php"><img src="./img/logout.png" alt="Logout" class="navbar-icon"></a>
        </div>
    </nav>
</div>  
<div class="container-menu">
    <section>
        <?php
        $categoria_seleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

        // Consultar salas por tipo
        $query_salas = "SELECT * FROM tbl_salas WHERE tipo_sala = ?";
        $stmt_salas = mysqli_prepare($conexion, $query_salas);
        mysqli_stmt_bind_param($stmt_salas, "s", $categoria_seleccionada);
        mysqli_stmt_execute($stmt_salas);
        $result_salas = mysqli_stmt_get_result($stmt_salas);
        
        // echo "<h2>Selecciona una sala de tipo $categoria_seleccionada</h2>";
        
        mysqli_stmt_close($stmt_salas);
        mysqli_close($conexion);
            if ($result_salas && mysqli_num_rows($result_salas) > 0) {
                while ($sala = mysqli_fetch_assoc($result_salas)) {
            echo "<a class='image-container' href='./gestionar_mesas.php?categoria=" . urlencode($categoria_seleccionada) . "&id_sala=" . $sala['id_sala'] . "'>
                    <img src='./img/" . htmlspecialchars($sala['nombre_sala']) . ".jpg' alt='' id='terraza'>
                    <div class='text-overlay'>" . htmlspecialchars($sala['nombre_sala']) . "</div>
                </a>";
                }
            } else {
                echo "<p>No hay salas disponibles para esta categoría.</p>";
            }
        ?>
    </section>
</div>
</body>
</html>