<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../db.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$cpf_admin = 'Não disponível';
$sql_admin = "SELECT cpf FROM administrador WHERE id_administrador = ?";
$stmt_admin = $mysqli->prepare($sql_admin);
if ($stmt_admin) {
    $stmt_admin->bind_param("i", $admin_id);
    $stmt_admin->execute();
    $result_admin = $stmt_admin->get_result();
    if ($result_admin && $row_admin = $result_admin->fetch_assoc()) {
        $cpf_admin = $row_admin['cpf'];
    }
    $stmt_admin->close();
}

if (isset($_GET['id_ong'])) {
    $id_ong = intval($_GET['id_ong']);
    $sql = "SELECT id_ong, nome, chave_pix FROM ONG WHERE id_ong = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_ong);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result && $row = $result->fetch_assoc()) {
            $ong_id = $row['id_ong'];
            $nome_ong = $row['nome'];
            $chave_pix = $row['chave_pix'];
        } else {
            echo "ONG não encontrada.";
            exit();
        }
        $stmt->close();
    } else {
        echo "Erro na consulta SQL.";
        exit();
    }
} else {
    echo "ID da ONG não especificado.";
    exit();
}

date_default_timezone_set('America/Sao_Paulo');
$data_emissao = date('Y-m-d');
$data_vencimento = date('Y-m-d', strtotime('+7 days'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor_transferencia = $_POST['valor_transferencia'];
    $metodo_pagamento = 'PIX';
    $status_pagamento = 'pendente';
    $sql_boleto = "INSERT INTO BOLETO (id_ong, id_administrador, valor_transferencia, data_emissao, data_vencimento, status_pagamento, metodo_pagamento) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_boleto = $mysqli->prepare($sql_boleto);
    if ($stmt_boleto) {
        $stmt_boleto->bind_param("iisssss", $ong_id, $admin_id, $valor_transferencia, $data_emissao, $data_vencimento, $status_pagamento, $metodo_pagamento);
        if ($stmt_boleto->execute()) {
            header("Location: adm-configuracoes.php");
            exit();
        } else {
            echo "Erro ao registrar o boleto: " . $stmt_boleto->error;
        }
        $stmt_boleto->close();
    } else {
        echo "Erro na preparação da consulta de boleto.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="Alegrinho">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/todos-ong.css">
</head>

<body>
    <header>
        <nav class="navbar nav-lg-screen" id="navbar" aria-label="Menu principal">
            <button class="btn-icon-header" onclick="toggleSideBar()" aria-label="Abrir menu lateral">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
            <div>
                <img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo da ONG" />
            </div>
            <div class="nav-links" id="nav-links">
                <ul>
                    <li><button class="btn-icon-header" onclick="toggleSideBar()" aria-label="Fechar menu lateral">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                            </svg>
                        </button></li>
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

    <main class="container">
        <section class="donation-box">
            <h1 class="ong-name"><?php echo htmlspecialchars($nome_ong); ?></h1>
            <div class="donation-image">
                <img src="../../assets/ong-<?php echo $ong_id; ?>.png" alt="Imagem da ONG">
            </div>
            <div class="input-box">
                <p>ONG: <?php echo htmlspecialchars($nome_ong); ?></p>
            </div>
            <div class="input-box">
                <p>CPF do Administrador: <?php echo htmlspecialchars($cpf_admin); ?></p>
            </div>
            <form>
                <label for="valor">Valor (R$):</label>
                <input type="text" id="valor" name="valor" placeholder="Digite o valor da doação (somente números)">
                <label for="dataEmissao">Data de Emissão:</label>
                <input type="date" id="dataEmissao" name="dataEmissao" value="<?php echo $data_emissao; ?>" disabled>
                <label for="dataVencimento">Data de Vencimento:</label>
                <input type="date" id="dataVencimento" name="dataVencimento" value="<?php echo $data_vencimento; ?>" disabled>
                <label for="metodoPagamento">Método de Pagamento:</label>
                <input type="text" id="metodoPagamento" name="metodoPagamento" value="PIX" disabled>
            </form>
        </section>
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
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>