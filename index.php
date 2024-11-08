<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>
<body id="body">
    <div class="container"> 
        <div class="left-side">
            <img src="./img/logo.png" alt="Logo">
        </div>
        <div class="right-side">
        <form id="loginForm" action="./php/login.php" method="post" class="form-login">
        <h2>Iniciar sesión</h2>
            <label for="Usuario">Usuario: </label>
            <br><br>
            <input type="text" name="Usuario" id="Usuario" class="form-login-label">
            <span id="usuarioError" class="error-message"></span>
            <br><br>
            <label for="Contra">Contraseña: </label>
            <br><br>
            <input type="password" name="Contra" id="Contra" class="form-login-label">
            <span id="contraError" class="error-message"></span>
            <br><br><br>
            <button type="submit" name="btn_iniciar_sesion" class="form-login-button">Iniciar sesion</button>
        </form>  
        </div>  
    </div>
    <script src="./js/auth.js"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
        }
    </style>
</body>
</html>