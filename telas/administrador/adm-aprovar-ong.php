<?php
session_start();
include('../../db.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_ong_id'])) {
    $accept_ong_id = $_POST['accept_ong_id'];
    $update_query = "UPDATE ONG SET status = 'ativo' WHERE id_ong = ?";
    $stmt = $mysqli->prepare($update_query);
    $stmt->bind_param('i', $accept_ong_id);
    $stmt->execute();
    $stmt->close();
}

$filter_condition = "";
$params = [];

if (isset($_POST['ong_filter']) && $_POST['ong_filter'] !== "") {
    $ong_filter = $_POST['ong_filter'];
    $filter_condition .= " AND B.id_ong = ?";
    $params[] = $ong_filter;
}

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
        ONG.nome AS nome_ong, 
        B.valor_transferencia, 
        B.data_emissao, 
        B.data_vencimento, 
        B.status_pagamento, 
        B.metodo_pagamento 
    FROM BOLETO B
    INNER JOIN ONG ON B.id_ong = ONG.id_ong
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

$ongs_query = "SELECT id_ong, nome FROM ONG";
$ongs_result = $mysqli->query($ongs_query);

$ongs = [];
while ($row = $ongs_result->fetch_assoc()) {
    $ongs[] = $row;
}

$pending_ongs_query = "SELECT id_ong, nome FROM ONG WHERE status = 'pendente'";
$pending_ongs_result = $mysqli->query($pending_ongs_query);

$pending_ongs = [];
while ($row = $pending_ongs_result->fetch_assoc()) {
    $pending_ongs[] = $row;
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
    <link rel="stylesheet" href="../../css/adm-aprovar-ong.css">
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
        <h1>Histórico para Aprovação</h1>

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
            <a href="adm-configuracoes.php" class="btn-submit">Voltar para Configurações</a>
        </form>

        <div class="pending-ongs">
            <h2>ONGs Pendentes:</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_ongs as $pending_ong): ?>
                        <tr>
                            <td><?= htmlspecialchars($pending_ong['id_ong']) ?></td>
                            <td><?= htmlspecialchars($pending_ong['nome']) ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="accept_ong_id" value="<?= $pending_ong['id_ong'] ?>">
                                    <button type="submit">Aceitar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
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