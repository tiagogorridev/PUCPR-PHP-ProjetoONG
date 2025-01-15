<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $_SESSION['error_message'] = 'Por favor, preencha todos os campos.';
        header("Location: usu-login.php");
        exit();
    }

    try {
        $sql_admin = "SELECT id_administrador AS id, nome, email, senha FROM ADMINISTRADOR WHERE email = ?";
        $stmt_admin = $mysqli->prepare($sql_admin);
        $stmt_admin->bind_param("s", $email);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        $sql_doador = "SELECT id_doador AS id, nome, email, senha, status FROM DOADOR WHERE email = ?";
        $stmt_doador = $mysqli->prepare($sql_doador);
        $stmt_doador->bind_param("s", $email);
        $stmt_doador->execute();
        $result_doador = $stmt_doador->get_result();

        $sql_ong = "SELECT id_ong AS id, nome, email, senha, status FROM ONG WHERE email = ?";
        $stmt_ong = $mysqli->prepare($sql_ong);
        $stmt_ong->bind_param("s", $email);
        $stmt_ong->execute();
        $result_ong = $stmt_ong->get_result();

        if ($result_admin->num_rows > 0) {
            $admin = $result_admin->fetch_assoc();
            if ($admin['senha'] === $senha) {
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['user_nome'] = $admin['nome'];
                $_SESSION['user_email'] = $admin['email'];
                $_SESSION['user_tipo'] = 'administrador';
                $_SESSION['logged_in'] = true;
                header("Location: usu-index.php");
                exit();
            }
        }

        if ($result_doador->num_rows > 0) {
            $doador = $result_doador->fetch_assoc();
            if ($doador['senha'] === $senha) {
                if ($doador['status'] === 'desativado') {
                    $_SESSION['error_message'] = 'Sua conta foi desativada. Entre em contato com o suporte.';
                    header("Location: usu-login.php");
                    exit();
                }

                $_SESSION['user_id'] = $doador['id'];
                $_SESSION['user_nome'] = $doador['nome'];
                $_SESSION['user_email'] = $doador['email'];
                $_SESSION['user_tipo'] = 'doador';
                $_SESSION['logged_in'] = true;

                header("Location: usu-index.php");
                exit();
            }
        }

        if ($result_ong->num_rows > 0) {
            $ong = $result_ong->fetch_assoc();

            if ($ong['status'] == 'inativo') {
                $_SESSION['error_message'] = 'Sua ONG foi desativada. Entre em contato com o suporte.';
                header("Location: usu-login.php");
                exit();
            }

            if ($ong['senha'] === $senha) {
                $_SESSION['user_id'] = $ong['id'];
                $_SESSION['user_nome'] = $ong['nome'];
                $_SESSION['user_email'] = $ong['email'];
                $_SESSION['user_tipo'] = 'ong';
                $_SESSION['logged_in'] = true;

                header("Location: usu-index.php");
                exit();
            }
        }

        $_SESSION['error_message'] = '✕ Email ou senha inválidos.';
        header("Location: usu-login.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = 'Erro ao realizar login. Tente novamente mais tarde.';
        header("Location: usu-login.php");
        exit();
    } finally {
        if (isset($stmt_admin)) $stmt_admin->close();
        if (isset($stmt_doador)) $stmt_doador->close();
        if (isset($stmt_ong)) $stmt_ong->close();
        if (isset($mysqli)) $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Página de login do Novo Começo. Faça login para acessar sua conta.">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/usuario-login.css">
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
                <a href="../telas/usu-index.php">
                    <img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo">
                </a>
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
                <a href="../../telas/usuarios/usu-login.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>

    <div id="message-container" style="width: 50%; max-width: 500px; text-align: center; margin: 0 auto; margin-bottom: 1%;">
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<div class="error-message" style="background-color: #ffeaea; color: #dc3545; border: 1px solid #f5c6cb; padding: 10px; margin-top: 10px; border-radius: 5px; font-size: 14px;">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }

        if (isset($_SESSION['success_message'])) {
            echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        ?>
    </div>

    <main>
        <h1 class="title">LOGIN</h1>
        <section class="login">
            <form id="loginForm" action="usu-login.php" method="POST" novalidate>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu email" required aria-required="true">
                </div>

                <div class="input-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="senha" placeholder="Digite sua senha" required aria-required="true">
                </div>

                <div class="secondary-action">
                    <a href="../usuarios/usu-alterar-senha.php" class="esqueci-senha">Esqueci minha senha</a>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="action-button">Entrar</button>
                </div>
            </form>

            <div class="secondary-action div-cadastrar">
                <p>Não tem uma conta? Cadastrar-se como
                    <a href="../usuarios/usu-cadastrar-admin.php" class="cadastrar">Administrador</a> /
                    <a href="../usuarios/usu-cadastrar-doador.php" class="cadastrar">Doador</a> /
                    <a href="../usuarios/usu-cadastrar-ong.php" class="cadastrar">ONG</a>
                </p>
            </div>
        </section>
    </main>

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
                <img class="boneco-footer" class="img-footer" <script src="../../js/header.js"></script>>
            </div>
        </div>
    </footer>

    <script src="../../js/header.js"></script>
    <script>
        function setupMessageRemoval() {
            const messages = document.querySelectorAll('.error-message, .success-message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-in-out';
                    message.style.opacity = '0';
                    setTimeout(() => {
                        if (message.parentNode) {
                            message.parentNode.removeChild(message);
                        }
                    }, 500);
                }, 3000);
            });
        }

        document.addEventListener('DOMContentLoaded', setupMessageRemoval);
    </script>
</body>

</html>
