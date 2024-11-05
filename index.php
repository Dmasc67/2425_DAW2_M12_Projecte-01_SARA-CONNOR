<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <img src="logo.php" alt="Logo">
        </div>
        <div class="right-side">
        <form action="./php/login.php" method="post">
            <label for="Usuario">Usuario: </label>
            <input type="text" name="Usuario" id="Usuario">
            <br><br>
            <label for="Contra">Contraseña: </label>
            <input type="text" name="Contra" id="Contra">
            <br><br> 
            <button type="submit" name="btn_iniciar_sesion">Iniciar sesion</button>
        </form>  
        </div>  
    </div>
</body>
</html>