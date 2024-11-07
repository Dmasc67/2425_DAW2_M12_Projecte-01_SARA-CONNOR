<?php
session_start();

// Verificar si la variable de sesión 'Usuario' está configurada
if (!isset($_SESSION['Usuario'])) {
    $_SESSION['Usuario'] = 'Invitado'; // Valor por defecto si no está configurada
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

<body data-usuario="<?php echo htmlspecialchars($_SESSION['Usuario'], ENT_QUOTES, 'UTF-8'); ?>">
<div class="container">
    <nav class="navegacion">
        <!-- Sección izquierda con el logo grande y el ícono adicional más pequeño -->
        <div class="navbar-left">
            <a href="#"><img src="./img/logo.png" alt="Logo de la Marca" class="logo" style="width: 100%;"></a>
            <a href="#"><img src="./img/lbook.png" alt="Ícono adicional" class="navbar-icon"></a>
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
    <!------------FIN BARRA DE NAVEGACION--------------------->
    <div class="container-menu">
    <section>
        <a class="image-container" href="./seleccionar_sala?categoria=Terraza">
            <img src="./img/comedor.jpg" alt=""  id="terraza">
            <div class="text-overlay">Comedor</div>
        </a>
        <a class="image-container"  href="./seleccionar_sala?categoria=Comedor">
            <img src="./img/private.jpg" alt="" id="comedor">
            <div class="text-overlay">Sala privada</div>
        </a>
        <a class="image-container" >
            <img src="./img/terraza.jpg" alt="" id="privada">
            <div class="text-overlay">Terraza</div>
        </a>
    </section>
</div>
    
    <script src="./js/sweetalert.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>