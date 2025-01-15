<?php
session_start();
include('../../db.php');
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

$data_inicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : '';
$data_fim = isset($_GET['data_fim']) ? $_GET['data_fim'] : '';
$nome_doador = isset($_GET['nome_doador']) ? $_GET['nome_doador'] : '';
$nome_ong = isset($_GET['nome_ong']) ? $_GET['nome_ong'] : '';

$sql = "SELECT DOACAO.id_doacao, DOACAO.valor_total, DOACAO.valor_taxa, DOACAO.data_hora, DOACAO.status, 
            DOADOR.nome AS nome_doador, ONG.nome AS nome_ong
        FROM DOACAO
        JOIN DOADOR ON DOACAO.id_doador = DOADOR.id_doador
        JOIN ONG ON DOACAO.id_ong = ONG.id_ong
        WHERE DOACAO.status = 'realizado'";

if ($data_inicio && $data_fim) {
    $sql .= " AND DOACAO.data_hora BETWEEN '$data_inicio' AND '$data_fim'";
}

if ($nome_doador) {
    $sql .= " AND DOADOR.nome LIKE '%$nome_doador%'";
}

if ($nome_ong) {
    $sql .= " AND ONG.nome LIKE '%$nome_ong%'";
}

$sql .= " ORDER BY DOACAO.data_hora DESC";

$result = $mysqli->query($sql);
$dados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
} else {
    $dados = [];
}
?>



<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/adm-grafico-transferencias.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="adm-configuracoes.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>
    <main>
        <div class="relatorio">
            <h1 class="title">Relatório de Doações</h1>

            <form method="GET" action="">
                <div class="filtros">
                    <label>Data Inicial:
                        <input type="date" name="data_inicio" value="<?php echo $data_inicio; ?>">
                    </label>
                    <label>Data Final:
                        <input type="date" name="data_fim" value="<?php echo $data_fim; ?>">
                    </label>
                    <label>Nome do Doador:
                        <input type="text" name="nome_doador" placeholder="Nome do Doador" value="<?php echo $nome_doador; ?>">
                    </label>
                    <label>Nome da ONG:
                        <input type="text" name="nome_ong" placeholder="Nome da ONG" value="<?php echo $nome_ong; ?>">
                    </label>
                    <button type="submit">Filtrar</button>
                </div>
            </form>

            <?php if (!empty($dados)) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID Doação</th>
                            <th>Nome da ONG</th>
                            <th>Nome do Doador</th>
                            <th>Valor Total</th>
                            <th>Valor da Taxa</th>
                            <th>Data e Hora</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $row) : ?>
                            <tr>
                                <td><?php echo $row['id_doacao']; ?></td>
                                <td><?php echo $row['nome_ong']; ?></td>
                                <td><?php echo $row['nome_doador']; ?></td>
                                <td><?php echo number_format($row['valor_total'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format($row['valor_taxa'], 2, ',', '.'); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($row['data_hora'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma doação encontrada para o período selecionado.</p>
            <?php endif; ?>
        </div>
        <div class="container">
            <h1 class="title">Gráfico de Doações para ONGs</h1>
            <div class="filters">
                <label>Data Inicial:
                    <input type="date" id="data_inicio" value="<?php echo date('Y-m-d', strtotime('-50 days')); ?>">
                </label>
                <label style="margin-left: 20px;">Data Final:
                    <input type="date" id="data_fim" value="<?php echo date('Y-m-d'); ?>">
                </label>
                <button onclick="atualizarGrafico()" style="margin-left: 20px;">Filtrar</button>
            </div>
            <div>
                <canvas id="meuGrafico"></canvas>
            </div>
        </div>
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

    <script src="../../js/header.js"></script>
    <script src="../../js/grafico.js"></script>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>