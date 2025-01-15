<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

include('../../db.php');

$id_ong = $_POST['id_ong'] ?? null;
$valor = $_POST['total_doacoes'] ?? 0;
$taxa = $_POST['total_taxa'] ?? 0;
$valor_pago = $_POST['valor_pago'] ?? 0;
$nome_doador = $_SESSION['user_nome'] ?? 'Administrador';


$sql = "SELECT nome, chave_pix FROM ONG WHERE id_ong = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_ong);
$stmt->execute();
$result = $stmt->get_result();
$ong_selecionada = "Não especificada";
$chave_pix = "Chave PIX não encontrada";


if ($result && $row = $result->fetch_assoc()) {
    $ong_selecionada = $row['nome'];
    $chave_pix = $row['chave_pix'];
}
$stmt->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar_transferencia'])) {
    $id_ong = $_POST['id_ong'] ?? null;
    $valor_pago = $_POST['valor_pago'] ?? 0;

    if ($id_ong !== null) {
        $sql = "UPDATE DOACAO SET transferida = 'sim' WHERE id_ong = ? AND transferida = 'não'";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $id_ong);

        if ($stmt->execute()) {
            $sqlInsert = "INSERT INTO boleto (
                id_ong,
                id_administrador,
                data_emissao,
                data_vencimento,
                metodo_pagamento,
                status_pagamento,
                valor_transferencia
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmtInsert = $mysqli->prepare($sqlInsert);


            $id_administrador = $_SESSION['user_id'];
            $data_emissao = date("Y-m-d H:i:s");
            $data_vencimento = date("Y-m-d H:i:s", strtotime("+7 days"));
            $metodo_pagamento = "PIX";
            $status_pagamento = "realizado";
            $valor_pago = str_replace(',', '.', str_replace('.', '', $valor_pago));
            $valor_pago = floatval($valor_pago);

            $stmtInsert->bind_param(
                "iissssd",
                $id_ong,
                $id_administrador,
                $data_emissao,
                $data_vencimento,
                $metodo_pagamento,
                $status_pagamento,
                $valor_pago
            );

            if ($stmtInsert->execute()) {
                echo "<script>alert('Transferência e registro realizados com sucesso!');</script>";
                header("Location: adm-transferencia.php");
                exit();
            } else {
                echo "<script>alert('Transferência realizada, mas falha ao registrar.');</script>";
            }

            $stmtInsert->close();
        } else {
            echo "<script>alert('Erro ao realizar a transferência.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('ID da ONG não fornecida.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Transferência</title>
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/teste2.css">
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
    <main>
        <h1 class="title">Transferir para a ONG: <?php echo htmlspecialchars($ong_selecionada); ?></h1>
        <div class="summary-section">
            <h2>Resumo da Transferência</h2>
            <p><strong>Valor Total Arrecadado:</strong> R$ <?php echo number_format($valor, 2, ',', '.'); ?></p>
            <p><strong>Taxa:</strong> R$ <?php echo number_format($taxa, 2, ',', '.'); ?></p>
            <p><strong>Valor a Ser Pago:</strong> R$ <?php echo number_format($valor_pago, 2, ',', '.'); ?></p>
            <p><strong>Chave PIX da ONG:</strong> <?php echo htmlspecialchars($chave_pix); ?></p>
        </div>
        <form method="POST">
            <input type="hidden" name="valor_pago" value="<?php echo $valor_pago; ?>">
            <input type="hidden" name="id_ong" value="<?php echo $id_ong; ?>">
            <button type="submit" name="finalizar_transferencia">Confirmar Transferência</button>
        </form>
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