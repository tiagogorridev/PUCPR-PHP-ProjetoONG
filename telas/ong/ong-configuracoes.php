<?php
session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$tipoUsuario = $_SESSION['user_tipo'] ?? null;
$email = $_SESSION['user_email'] ?? null;

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $tipoUsuario !== 'ong') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

include_once('../../db.php');

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$nome_ong = "";
$email_ong = "";
$cnpj_ong = "";

try {
    $sql = "SELECT nome, email, cnpj FROM ONG WHERE email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($nome_ong, $email_ong, $cnpj_ong);
        $stmt->fetch();
        $stmt->close();
    } else {
        throw new Exception("Erro ao preparar consulta: " . $mysqli->error);
    }
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

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

        $sql = "UPDATE ONG SET senha = ? WHERE email = ?";

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
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/todos-configuracoes.css">
    <link rel="stylesheet" href="../../css/configuracoes-ong.css">
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
                <img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo da Novo Começo">
            </div>
            <div class="nav-links" id="nav-links">
                <ul>
                    <li>
                        <button class="btn-icon-header" onclick="toggleSideBar()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
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
                <a href="../../telas/ong/ong-configuracoes.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h1 class="title">Configurações</h1>
        <section class="donation-box">
            <div class="circle">
                <p>FOTO</p>
            </div>
            <div class="donation-details">
                <p><strong>ONG:</strong> <?= htmlspecialchars($nome_ong) ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($email_ong) ?></p>
                <p><strong>CNPJ:</strong> <?= htmlspecialchars($cnpj_ong) ?></p>
            </div>
        </section>
        <div class="action-buttons">
            <button class="action-button" onclick="window.location.href='../ong/ong-historico-transf.php'">Histórico de Transferências Recebidas</button>
            <button class="action-button" onclick="window.location.href='../ong/ong-redefinir-senha.php'">Redefinir Senha</button>
            <button class="action-button" onclick="window.location.href='ong-desvincular.php'">Desvincular ONG</button>
            <button class="action-button" onclick="window.location.href='../../logout.php'">Logout</button>
        </div>
    </main>

    <footer>
        <div class="footer">
            <div class="img-footer-start">
                <img class="boneco-footer img-footer" src="../../assets/img-footer.png" alt="Boneco do rodapé">
            </div>
            <div class="socias">
                <div class="icons-col-1">
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/google.png" alt="Google">
                        <p>novocomeço@gmail.com</p>
                    </div>
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/instagram.png" alt="Instagram">
                        <p>@novocomeço</p>
                    </div>
                </div>
                <div class="icons-col-2">
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/whatsapp.png" alt="Whatsapp">
                        <p>(41) 99997-6767</p>
                    </div>
                    <div class="social-footer">
                        <img class="icon-footer" src="../../assets/facebook.png" alt="Facebook">
                        <p>@novocomeco</p>
                    </div>
                </div>
            </div>
            <div class="img-footer-end">
                <img class="boneco-footer img-footer" src="../../assets/img-footer.png" alt="Boneco do rodapé">
            </div>
        </div>
    </footer>

    <script src="../../js/header.js"></script>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>