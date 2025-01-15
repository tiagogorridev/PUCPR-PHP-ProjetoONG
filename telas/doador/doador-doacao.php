<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'doador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

require __DIR__ . '../../../db.php';
$referer = $_SERVER['HTTP_REFERER'] ?? null;
$ong_id = null;
$ong_selecionada = "Não especificada";
$chave_pix = "Chave PIX não encontrada";

if (isset($_GET['ong'])) {
    $ong_id = (int)$_GET['ong'];
}

if ($ong_id) {
    $sql = "SELECT id_ong, nome, chave_pix FROM ONG WHERE id_ong = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $ong_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $ong_selecionada = $row['nome'];
            $chave_pix = $row['chave_pix'];
            $id_ong = $row['id_ong'];
        }
        $stmt->close();
    }
}

$valor = $_GET['valor'] ?? 0;
$taxa = $_GET['taxa'] ?? 0;
$nome_doador = $_SESSION['user_nome'] ?? ($_GET['doador'] ?? 'Anônimo');

if ($valor <= 0 || $taxa < 0) {
    echo "<script>alert('Valores de doação ou taxa inválidos.');</script>";
    header("Refresh: 2; url=/telas/usuarios/usu-ongs.php");
    exit();
}

if (isset($_POST['finalizar_doacao'])) {
    date_default_timezone_set('America/Sao_Paulo');
    $data_hora = date('Y-m-d H:i:s');
    $id_ong = $ong_id;
    $id_doador = $_SESSION['user_id'];
    $valor_total = $valor;
    $valor_taxa = $taxa;
    $status = 'realizado';

    $sql = "INSERT INTO DOACAO (id_ong, id_doador, valor_total, valor_taxa, data_hora, status) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iiddss", $id_ong, $id_doador, $valor_total, $valor_taxa, $data_hora, $status);
        if ($stmt->execute()) {
            echo "<script>alert('Doação registrada com sucesso!');</script>";
            header("Location: doador-home.php");
            exit();
        } else {
            echo "<script>alert('Erro ao registrar a doação.');</script>";
        }
        $stmt->close();
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
    <link rel="stylesheet" href="../../css/doador-realizar-pagamento.css">
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
    <div class="main-container">
        <div class="summary-section">
            <h2>Resumo da Doação</h2>
            <div class="button-container">
                <p><span class="fake-button"><strong>ONG Selecionada:</strong> <?php echo $ong_selecionada; ?></span></p>
                <p><span class="fake-button"><strong>Nome do Doador:</strong> <?php echo htmlspecialchars($nome_doador); ?></span></p>
                <p><span class="fake-button"><strong>Valor da Doação:</strong> R$ <?php echo number_format($valor, 2, ',', '.'); ?></span></p>
                <p><span class="fake-button"><strong>Taxa sobre o Valor Total:</strong> R$ <?php echo number_format($taxa, 2, ',', '.'); ?></span></p>
                <span>*Taxa inclusa no valor da doação</span>
            </div>
        </div>
        <div class="pix-box">
            <div class="pix-image">
                <img src="../../assets/pix.png" alt="PIX" style="width: 100%; height: auto; object-fit: contain;">
            </div>
            <p>Chave da ONG: <span id="chave"><?= htmlspecialchars($chave_pix); ?></span></p>
        </div>
    </div>
    <div class="button-group">
        <button class="btn-voltar" onclick="window.history.back()">Voltar</button>
        <form method="post">
            <button type="submit" name="finalizar_doacao" class="btn-voltar">Pronto</button>
        </form>
    </div>
    <footer>
        <div class="footer">
            <div class="img-footer-start">
                <img class="boneco-footer" src="../../assets/img-footer.png" alt="Imagem">
            </div>
        </div>
    </footer>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
    <script src="../../js/header.js"></script>
</body>

</html>