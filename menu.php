<?php
session_start();
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
            background-color: #28a745;
            color: #fff;
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
                <a class="navbar-brand" href="#">
                    <img src="./img/logo.png" alt="Logo" width="100" height="90">
                    <?php
                    echo "<a href='./registro.php'><button class='atrasboton'><img class='atrasimg' src='./img/libro.png' alt=''></button></a>";
                    ?>
                </a>
            </div>
            <div class="saludo">
                <b style="color:white">¡Bienvenido al portal, <?php echo $_SESSION['Usuario']; ?>!</b>
            </div>
            <a href="./salir.php"><button class="logoutboton"><img class="logoutimg" src="./img/logout.webp" alt="Logout"></button></a>
        </div>
    </nav>
    <!------------FIN BARRA DE NAVEGACION--------------------->
    <div class="image-grid">
        <div class="image-item">
            <a href="./seleccionar_sala.php?categoria=Terraza">
                <img src="./img/terraza.jpg" id="terraza" alt="Imagen de Terraza">
                <div class="image-text">
                    <h2>Terrazas</h2>
                    <p>En la terraza, encontrarás tres áreas al aire libre, cada una con capacidad para cuatro mesas.</p>
                </div>
            </a>
        </div>

        <div class="image-item">
            <a href="./seleccionar_sala.php?categoria=Comedor">
                <img src="./img/comedor.jpg" id="comedor" alt="Imagen de Comedor">
                <div class="image-text">
                    <h2>Comedores</h2>
                    <p>Dentro de nuestros comedores, contamos con dos zonas, cada una con cuatro mesas.</p>
                </div>
            </a>
        </div>

        <div class="image-item">
            <a href="./seleccionar_sala.php?categoria=Privada">
                <img src="./img/private.jpg" id="privada" alt="Imagen de Área Privada">
                <div class="image-text">
                    <h2>Áreas Privadas</h2>
                    <p>Nuestras cuatro salas privadas están equipadas con una mesa en cada una. Estos espacios brindan privacidad y comodidad.</p>
                </div>
            </a>
        </div>
    </div>

    <script src="./js/imagen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>

</html>