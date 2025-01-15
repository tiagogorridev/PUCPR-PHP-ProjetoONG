<?php
session_start();
include('../../db.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'ong') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

$id_ong = $_SESSION['user_id'];

$filter_condition = " AND B.id_ong = ?";
$params = [$id_ong];

if (isset($_POST['data_filter_de']) && isset($_POST['data_filter_ate']) && $_POST['data_filter_de'] !== "" && $_POST['data_filter_ate'] !== "") {
    $data_filter_de = $_POST['data_filter_de'];
    $data_filter_ate = $_POST['data_filter_ate'];

    if (strtotime($data_filter_ate) < strtotime($data_filter_de)) {
        echo "<script>alert('A data \"Até\" não pode ser anterior à data \"De\".');</script>";
    } else {
        $filter_condition .= " AND B.data_emissao BETWEEN ? AND ?";
        $params[] = $data_filter_de;
        $params[] = $data_filter_ate;
    }
}

$query = "
    SELECT 
        B.id_boleto, 
        B.valor_transferencia, 
        B.data_emissao, 
        B.data_vencimento, 
        B.status_pagamento, 
        B.metodo_pagamento 
    FROM BOLETO B
    WHERE 1=1" . $filter_condition . "
    ORDER BY B.data_emissao DESC
";

$stmt = $mysqli->prepare($query);

if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$transferencias = [];
while ($row = $result->fetch_assoc()) {
    $transferencias[] = $row;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/adm-historico-transferencias.css">
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
        <div class="container">
            <h1>Histórico de Transferências Recebidas</h1>

            <form method="POST" action="" class="filter-form">
                <div>
                    <label for="data_filter_de">Filtrar por Data (De):</label>
                    <input type="date" id="data_filter_de" name="data_filter_de" value="<?= $data_filter_de ?? '' ?>">
                </div>
                <div>
                    <label for="data_filter_ate">Filtrar por Data (Até):</label>
                    <input type="date" id="data_filter_ate" name="data_filter_ate" value="<?= $data_filter_ate ?? '' ?>">
                </div>
                <button type="submit">Filtrar</button>
                <a href="ong-configuracoes.php" class="btn-submit">Voltar para Configurações</a>
            </form>

            <?php if (count($transferencias) > 0): ?>
                <?php foreach ($transferencias as $transferencia): ?>
                    <div class="transferencias-box">
                        <p><strong>ID do Boleto:</strong> <?= htmlspecialchars($transferencia['id_boleto']) ?></p>
                        <p><strong>Valor:</strong> R$ <?= number_format($transferencia['valor_transferencia'], 2, ',', '.') ?></p>
                        <p><strong>Data de Emissão:</strong> <?= date('d/m/Y', strtotime($transferencia['data_emissao'])) ?></p>
                        <p><strong>Data de Vencimento:</strong> <?= date('d/m/Y', strtotime($transferencia['data_vencimento'])) ?></p>
                        <p><strong>Status:</strong> <?= ucfirst($transferencia['status_pagamento']) ?></p>
                        <p><strong>Método de Pagamento:</strong> <?= htmlspecialchars($transferencia['metodo_pagamento']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhuma transferência encontrada.</p>
            <?php endif; ?>
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
</body>
<script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
<script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
</script>

</html>