<?php
require_once "config.php";
require_once "session.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])){
    $fullname = trim($_POST['nome']);
    $fullname_bixo = trim($_POST['nome_bixo']);
    $email = trim($_POST['email']);
    $password = trim($_POST['senha']);
    $confim_password = trim($_POST["confirm_password"]);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    if($query = $db->prepare("SELECT * FROM users WHERE email = ?")){
        $error = '';
        // Bind parameters (s = string, i = int, b = blob, etc)
        $query->bind_param('s', $email);
        $query->execute();
        // Store the result so we can check if the account exists in the database
        $query->store_result();
            if ($query->num_rows > 0) {
                $error .= '<p class="error">O endereço de email já foi utilizado! </p>';
            } else {
                //Validate password
                if (strlen($password) < 6) {
                    $error .= '<p class="error">A senha deve conter pelo menos 6 caracteres.</p>';
                }
                //Validade confirm password
                if (empty($confim_password)) {
                    $error .= '<p class="error">Por favor, confirme a senha. </p>';
                } else {
                    if (empty($error) && ($password != $confim_password)) {
                        $error .= '<p class="error">As senhas não correspondem.</p>';
                    }
                }
                if (empty(error)) {
                    $insertQuery = $db->prepare("INSERT INTO users (nome, nome_bixo, email, senha) VALUES (?, ?, ?, ?);");
                    $insertQuery->bind_param("ssss", $fullname, $fullname_bixo, $email, $password_hash);
                    $result = $insertQuery->execute();
                    if ($result) {
                        $error .= '<p class="sucess">Seu cadastro foi realizado!</p>';
                    } else {
                        $error .= '<p class="error">Algo deu errado!</p>';
                    }
                }
            }
           
    }
    $query->close();
    $insertQuery->close();
    // Close DB connection
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
    <title>Pet Planet | Cadastre-se</title>

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
        <h1>Seja bem-vindo(a) à Pet Planet!</h1>

        <div class="pic-user">
            <img src="images/cat-sigin.jpg" alt="">
        </div>

        <form autocomplete="off" action="" method="POST">
            <h2>Cadastre-se facilmente</h2>

            <div class="input-wrp">
                <input type="text" name="name" id="name" class="wrp-campo" required>
                <span class="floating-label"> Seu nome completo</span>
                <img src="images/user-3-line.svg" alt="">
            </div>

            <div class="input-wrp">
                <input type="text" name="pet-name" id="pet-name" class="wrp-campo" required>
                <span class="floating-label"> Nome de um bichinho seu</span>
                <img src="images/mickey-line.svg" alt="">
            </div>

            <div class="input-wrp">
                <input type="text" name="email-address" id="email-address" class="wrp-campo" required>
                <span class="floating-label"> Endereço de e-mail</span>
                <img src="images/mail-line.svg" alt="">
            </div>

            <div class="input-wrp">
                <input type="password" name="password" id="password" class="wrp-campo" required>
                <span class="floating-label"> Senha</span>
                <img src="images/lock-unlock-line.svg" alt=""> 
            </div>

            <div class="input-wrp">
                <input type="password" name="confirm-password" id="confirm-password" class="wrp-campo" required>
                <span class="floating-label"> Confirme sua senha</span>
                <img src="images/lock-password-line.svg" alt=""> 
            </div>

            <div class="block-terms">
                <div class="i-agree">
                    <input type="checkbox" name="agree" id="agree" required>
                    <span class="checkmark">Eu li e concordo com os</span>
                </div>
                <a href="#" class="terms-of-use">termos e condições de uso</a>
            </div>
            <div class="block-terms">
                <div class="i-agree">
                    <input type="checkbox" name="i-want" id="i-want" checked>
                    <span class="checkmark">Quero receber novidades via e-mail</span>
                </div>
            </div>
            
            <button type="submit" name="submit" value="Submit" class="primary-button" id="lets-go">Vamos lá!</button>

            <div class="block-pass bottom">Já possui uma conta?<a href="05-login.html" class="pass-recover">Fazer login</a></div>
            
        </form>
    </main>

    <footer>
        © Copyright 2021. Todos os direitos reservados.
    </footer>

</body>
</html>