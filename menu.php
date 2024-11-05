<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ./index.php");
    exit;
} else if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ./index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="./css/menu.css">
    <link rel="shortcut icon" href="./img/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="./js/popup.js" defer></script>
    <style>
        /* Estilo para el botón en la esquina superior derecha */
        .image-item {
            position: relative;
            display: inline-block;
        }

        .image-item .btn-top-right {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 1;
            /* Ajusta el z-index para que esté sobre la imagen */
            background-color: #28a745;
            /* Color de fondo del botón */
            color: #fff;
            /* Color del texto del botón */
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-lights position-top">
        <div class="container">
            <div>
                <a class="navbar-brand " href="#">
                    <img src="./img/logo.png" alt="" width="100" height="90">
                     echo "<a href='./registro.php'><button class='atrasboton'><img class='atrasimg' src='./img/libro.png' alt=''></button></a>";
                    ?> </a>
            </div>
            <div class="saludo">
                <b style="color:white">¡Bienvenido al portal, <?php echo $_SESSION['Usuario']; ?>!</b>
            </div>
            <a href="./inc/salir.php"><button class="logoutboton"><img class="logoutimg" src="./img/logout.webp" alt=""></button></a>
        </div>
    </nav>
    <!------------FIN BARRA DE NAVEGACION--------------------->
    <div class="image-grid">
        <div class="image-item">
            <a href="./mostrar.php?id=Terraza">
                <img data-src="./img/terraza.jpg" id="terraza" src="./img/terraza.jpg" alt="Imagen 1">
                <div class="image-text">
                    <h2>Terrazas</h2>
                    <p>En la terraza, encontrarás tres áreas al aire libre, cada una con capacidad para cuatro mesas.</p>
                </div>
            </a>
        </div>

        <div class="image-item">
            <a href="./mostrar.php?id=Menjador">
                <img data-src="./img/comedor.jpg" id="comedor" src="./img/comedor.jpg" alt="Imagen 2">
                <div class="image-text">
                    <h2>Comedores</h2>
                    <p>Dentro de nuestros comedores, contamos con dos zonas, cada una con cuatro mesas.</p>
                </div>
            </a>
        </div>

        <div class="image-item">
            <a href="./mostrar.php?id=Privada">

                <img data-src="./img/private.jpg" id="privada" src="./img/private.jpg" alt="Imagen 3">
                <div class="image-text">
                    <h2>Areas Privadas</h2>
                    <p>Nuestras cuatro salas privadas están equipadas con una mesa en cada una. Estos espacios brindan privacidad y comodidad.</p>
                </div>
            </a>
        </div>

    </div>
    <script src="./js/imagen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>