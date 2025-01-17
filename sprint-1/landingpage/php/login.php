<?php

require_once "config.php";
require_once "session.php";

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['senha']);

    //Validate if email is empty
    if (empty($email)) {
        $error .= '<p class="error">Insira o email.</p>';
    }

    //Validade if password is empty
    if (empty($password)) {
        $error .= '<p class="error">Insira sua senha.</p>';
    }

    if (empty($error)) {
        if($query = $db->prepare("SELECT * FROM users WHERE email = ?")) {
            $query->bind_param('s', $email);
            $query->execute();
            $row = $query->fetch();
            if ($row) {
                if (password_verify($password, $row['senha'])) {
                    $_SESSION["userid"] = $row['id'];
                    $_SESSION["user"] = $row;

                    // Redirect the user to welcome page
                    header("location: 07-user-page.html");
                    exit;
                } else {
                    $error .= '<p class="error">A senha não é válida.</p>';
                }
            } else {
                $error .= '<p class="error">Usuário não encontrado!</p>';
            }
        }
        $query->close();
    }
    // Close connection
    mysqli_close($db);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style-forms.css">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <title>Pet Planet | Login</title>

    <script src="js/menu-mobile.js" type="text/javascript"></script>

</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="index.html"><img class="imagelogo" src="images/pet-planet.png" alt="Logo"></a>
            </div>

            <div class="menu">
                <div class="menuMobile">
                    <div class="mm-line"></div>
                    <div class="mm-line"></div>
                    <div class="mm-line"></div>
                </div>
                <nav>
                    <ul>
                        <li><a href="index.html">Início</a></li>
                        <li><a href="02-quem-somos.html">Quem somos</a></li>
                        <li><a href="03-nossos-servicos.html">Nossos serviços</a></li>
                        <li><a href="04-fale-conosco.html">Fale conosco</a></li>
                    </ul>
                </nav>
            </div>

            <div class="buttons">
                <a href="05-login.html" class="secundary-button">Login</a>
                <a href="06-cadastro.html" class="primary-button">Registre-se</a>
            </div>

        </div>
       
    </header>
    
    <main>
        <h1>Olá, é um prazer revê-lo!</h1>

        <div class="pic-user">
            <img src="images/dog-login.jpg" alt="">
        </div>

        <form autocomplete="off" action="" method="POST">
            <h2>Fazer login</h2>

            <div class="input-wrp">
                <input type="text" name="email" id="email" class="wrp-campo" required>
                <span class="floating-label"> Digite seu e-mail</span>
                <img src="images/mail-line.svg" alt="">
            </div>

            <div class="input-wrp">
                <input type="password" name="senha" id="senha" class="wrp-campo" required>
                <span class="floating-label"> Digite sua senha</span>
                <img src="images/lock-password-line.svg" alt=""> 
            </div>

            <div class="block-pass">
                <div class="remember-pass">
                    <input type="checkbox" name="lembrar-me" id="lembrar-me" checked>
                    <span class="checkmark">Lembrar-me</span>
                </div>
                <a href="#" class="pass-recover">Esqueci minha senha</a>
            </div>
            
            <button type="submit" name="submit" value="Submit" class="primary-button login-button">Entrar</button>
            
            <div class="block-pass bottom">Ainda não é nosso cliente?<a href="06-cadastro.html" class="pass-recover">Cadastre-se</a></div>
            
        </form>
        
    </main>

    <footer>
        © Copyright 2021. Todos os direitos reservados.
    </footer>

</body>
</html>
