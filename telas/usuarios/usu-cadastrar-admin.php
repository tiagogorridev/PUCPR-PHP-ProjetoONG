<?php
include('../../db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verificationCode = $_POST['verification-code'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $cpf = $_POST['cpf'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];
    $numero = $_POST['numero'];
    $complemento = $_POST['complemento'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    $error_message = "";

    if ($verificationCode !== "000") {
        $error_message = "Código de verificação inválido.";
    } elseif ($password !== $confirmPassword) {
        $error_message = "As senhas não coincidem.";
    } else {
        $check_admin = "SELECT * FROM administrador WHERE email = '$email' OR cpf = '$cpf'";
        $result_admin = $mysqli->query($check_admin);

        $check_doador = "SELECT * FROM doador WHERE email = '$email' OR cpf = '$cpf'";
        $result_doador = $mysqli->query($check_doador);

        $check_ong = "SELECT * FROM ong WHERE email = '$email'";
        $result_ong = $mysqli->query($check_ong);

        if ($result_admin->num_rows > 0) {
            $error_message = "Email ou CPF já cadastrado como administrador.";
        } elseif ($result_doador->num_rows > 0) {
            $error_message = "Email ou CPF já cadastrado como doador.";
        } elseif ($result_ong->num_rows > 0) {
            $error_message = "Email já cadastrado como ONG.";
        } else {
            $sql_code = "INSERT INTO administrador (nome, email, senha, telefone, cpf, end_rua, end_numero, end_bairro, end_cidade, end_estado, end_complemento) 
                        VALUES ('$name', '$email', '$password', '$phone', '$cpf', '$rua', '$numero', '$bairro', '$cidade', '$estado', '$complemento')";

            if ($mysqli->query($sql_code) === TRUE) {
                $_SESSION['user_id'] = $mysqli->insert_id;
                $_SESSION['user_nome'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_tipo'] = 'administrador';
                $_SESSION['logged_in'] = true;

                echo "<div class='success-message'>Cadastro realizado com sucesso!</div>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'usu-index.php';
                    }, 2000);
                </script>";
            } else {
                $error_message = "Erro ao cadastrar: " . $mysqli->error;
                echo "<div class='error-message'>$error_message</div>";
            }
        }
    }

    if (!empty($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="Alegrinho">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/todos-cadastrar.css">
</head>

<body>
    <header>
        <nav class="navbar nav-lg-screen" id="navbar">
            <button class="btn-icon-header" onclick="toggleSideBar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
            <div>
                <img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo">
            </div>
            <div class="nav-links" id="nav-links">
                <ul>
                    <li>
                        <button class="btn-icon-header" onclick="toggleSideBar()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                class="bi bi-x" viewBox="0 0 16 16">
                                <path
                                    d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                            </svg>
                        </button>
                    </li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-index.php">HOME</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-ongs.php">ONG'S</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-sobre.php">SOBRE</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-contato.php">CONTATO</a></li>
                </ul>
            </div>
            <div class="user">
                <a href="../usuarios/usu-login.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>
    <div class="container">
        <section class="register-section">
            <h1>Criar conta</h1>
            <p>Crie sua conta e ajude o próximo!</p>
            <form action="usu-cadastrar-admin.php" method="POST">
                <div class="input-group">
                    <input type="text" id="verification-code" name="verification-code" placeholder="Código de Verificação" required>
                </div>
                <div class="input-group">
                    <input type="text" id="name" name="name" placeholder="Nome Completo" required>
                </div>
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder="E-mail" required>
                </div>
                <div class="input-group">
                    <input type="tel" id="phone" name="phone" placeholder="Telefone (XX-XXXXXXXXX)" required>
                </div>
                <div class="input-group">
                    <input type="text" id="cpf" name="cpf" placeholder="CPF (XXX.XXX.XXX-XX)" required>
                </div>
                <div class="input-group">
                    <input type="text" id="cep" name="cep" placeholder="CEP (XXXXX-XXX)" required maxlength="10">
                </div>
                <div class="input-group">
                    <input type="text" id="estado" name="estado" placeholder="Estado" required readonly>
                </div>
                <div class="input-group">
                    <input type="text" id="cidade" name="cidade" placeholder="Cidade" required readonly>
                </div>
                <div class="input-group">
                    <input type="text" id="bairro" name="bairro" placeholder="Bairro" required readonly>
                </div>
                <div class="input-group">
                    <input type="text" id="rua" name="rua" placeholder="Rua" required>
                </div>
                <div class="input-group">
                    <input type="text" id="numero" name="numero" placeholder="Número" required>
                </div>
                <div class="input-group">
                    <input type="text" id="complemento" name="complemento" placeholder="Complemento">
                </div>
                <div class="password-section">
                    <div class="input-group">
                        <input type="password" id="password" name="password" placeholder="Senha" required>
                    </div>
                    <div class="input-group">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmar Senha" required>
                    </div>
                </div>
                <button type="submit" class="register-button">Criar conta</button>
            </form>
        </section>
    </div>
    <footer>
        <div class="footer">
            <div class="img-footer-start">
                <img class="boneco-footer" class="img-footer" src="../../assets/img-footer.png">
            </div>
            <div class="socias">
                <div class="icons-col-1">
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/google.png">
                        <p>novocomeço@gmail.com</p>
                    </div>
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/instagram.png">
                        <p>@novocomeço</p>
                    </div>
                </div>
                <div class="icons-col-2">
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/whatsapp.png">
                        <p>(41)99997676</p>
                    </div>
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/facebook.png">
                        <p>@novocomeco</p>
                    </div>
                </div>
            </div>
            <div class="img-footer-end">
                <img class="boneco-footer" class="img-footer" src="../../assets/img-footer.png">
            </div>
        </div>
    </footer>
    <script src="../../js/header.js"></script>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script>
        document.getElementById('cep').addEventListener('blur', function() {
            let cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.erro) {
                            alert('CEP não encontrado!');
                        } else {
                            document.getElementById('estado').value = data.uf || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('rua').value = data.logradouro || '';
                        }
                    })
                    .catch(error => console.error('Erro ao buscar o CEP:', error));
            } else {
                alert('CEP inválido! Por favor, digite um CEP com 8 dígitos.');
            }
        });
    </script>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
    <script src="../../js/cadastro-administrador.js"></script>
</body>

</html>