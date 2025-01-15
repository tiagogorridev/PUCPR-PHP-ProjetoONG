<?php
session_start();
require('../../db.php'); 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_mensagem'], $_POST['novo_status'])) {
    $id_mensagem = intval($_POST['id_mensagem']);
    $novo_status = $_POST['novo_status'];

    $sql = "UPDATE contato SET status = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("si", $novo_status, $id_mensagem);
        $stmt->execute();
        $stmt->close();
    }
}

$sql = "SELECT id, nome, email, mensagem, status, data_envio FROM contato WHERE status = 'nao_lido' ORDER BY data_envio DESC";
$result = $mysqli->query($sql);
$mensagens = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/adm-respostas.css">
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
                <a href="adm-configuracoes.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1>Gerenciar Mensagens</h1>
        <a href="adm-configuracoes.php" class="btn-submit">Voltar para Configurações</a>

        <?php if (!empty($mensagens)): ?>

            <?php foreach ($mensagens as $mensagem): ?>

                <div class="message-box">
                    <p><strong>Nome:</strong> <?= htmlspecialchars($mensagem['nome']); ?></p>
                    <p><strong>E-mail:</strong> <?= htmlspecialchars($mensagem['email']); ?></p>
                    <p><strong>Mensagem:</strong> <?= nl2br(htmlspecialchars($mensagem['mensagem'])); ?></p>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($mensagem['data_envio'])); ?></p>

                    <form method="post">
                        <input type="hidden" name="id_mensagem" value="<?= $mensagem['id']; ?>">
                        <button class="btn-status" type="submit" name="novo_status" value="lido">Marcar como Lido</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhuma mensagem encontrada.</p>
        <?php endif; ?>
    </div>

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
        </div>
    </footer>

    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

</body>

</html>