<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$tipoUsuario = $_SESSION['user_tipo'] ?? null;
$email = $_SESSION['user_email'] ?? null;

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $tipoUsuario !== 'doador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

include_once('../../db.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nova_senha = trim($_POST['nova_senha']);
    $confirmar_senha = trim($_POST['confirmar_senha']);

    if (empty($nova_senha) || empty($confirmar_senha)) {
        $error_message = "Todos os campos são obrigatórios.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $error_message = "As senhas não coincidem.";
    } else {
        $senha = $nova_senha;

        $sql = "UPDATE DOADOR SET senha = ? WHERE email = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ss", $senha, $email);

            if ($stmt->execute()) {
                session_unset();
                session_destroy();

                header("Location: ../usuarios/usu-login.php");
                exit();
            } else {
                $error_message = "Erro ao atualizar a senha: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error_message = "Erro ao preparar a consulta: " . $mysqli->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/todos-redefinir-senha.css">
</head>

<body>
    <header>
        <nav class="navbar nav-lg-screen" id="navbar">
            <button class="btn-icon-header" onclick="toggleSideBar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
            <div>
                <img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo">
            </div>
            <div class="nav-links" id="nav-links">
                <ul>
                    <li><button class="btn-icon-header" onclick="toggleSideBar()">X</button></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-index.php">HOME</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-ongs.php">ONG'S</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-sobre.php">SOBRE</a></li>
                    <li class="nav-link"><a href="../../telas/usuarios/usu-contato.php">CONTATO</a></li>
                </ul>
            </div>
            <div class="user">
                <a href="doador-configuracoes.php"><img class="img-user" src="../../assets/user.png" alt="Usuário"></a>
            </div>
        </nav>
    </header>

    <main>
        <div class="title">
            <h1>Redefinir Senha</h1>
            <?php
            if (!empty($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            if (!empty($success_message)) {
                echo "<p style='color: green;'>$success_message</p>";
            }
            ?>
        </div>
        <section class="password-change">
            <form id="password-form" action="" method="POST" aria-labelledby="password-change-section">
                <div class="input-group">
                    <label for="nova-senha">Nova senha</label>
                    <input type="password" id="nova-senha" name="nova_senha" required>
                </div>
                <div class="input-group">
                    <label for="confirme-nova-senha">Confirme sua nova senha</label>
                    <input type="password" id="confirme-nova-senha" name="confirmar_senha" required>
                </div>
                <div class="action-buttons">
                    <button type="button" class="action-button" onclick="cancelarAlteracao()">Cancelar</button>
                    <button type="submit" class="confirm-button">Confirmar</button>
                </div>
            </form>
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
                <img class="boneco-footer" class="img-footer" src="../../assets/img-footer.png">
            </div>
        </div>
    </footer>

    <script>
        function cancelarAlteracao() {
            window.location.href = "doador-configuracoes.php";
        }
    </script>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>
