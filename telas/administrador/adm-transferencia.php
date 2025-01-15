<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

include('../../db.php'); 

$sql = "SELECT ONG.id_ong, ONG.nome, 
               SUM(DOACAO.valor_total) AS total_doacoes, 
               SUM(DOACAO.valor_taxa) AS total_taxa, 
               COUNT(DOACAO.id_doacao) AS numero_doacoes, 
               SUM(CASE WHEN DOACAO.transferida = 'sim' THEN DOACAO.valor_total ELSE 0 END) AS total_transferido
        FROM ONG
        LEFT JOIN DOACAO ON DOACAO.id_ong = ONG.id_ong AND DOACAO.transferida = 'não'
        WHERE ONG.status = 'ativo'
        GROUP BY ONG.id_ong, ONG.nome";
$result = $mysqli->query($sql);


$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_ong = $_POST['id_ong'];
    $valor = $_POST['total_doacoes'];


    if ($valor <= 0) {
        $error_message = "O valor total arrecadado é 0. Não é possível realizar a transferência.";
    } else {
        header("Location: adm-transferencia-pagamento.php?id_ong=$id_ong&valor=$valor");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/teste.css">
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
        <h1 class="title">Transferência de Doações para ONGs</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ONG</th>
                        <th>Total Arrecadado</th>
                        <th>Total Taxa</th>
                        <th>Total Transferido</th>
                        <th>Transferir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php 
                            $valor_pago =  $row['total_doacoes'] - $row['total_taxa']; 
                            $valor_pago_formatado = number_format($valor_pago, 2, ',', '.'); 
                            $id_ong = $row['id_ong'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nome']); ?></td>
                            <td>R$ <?php echo number_format($row['total_doacoes'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($row['total_taxa'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo $valor_pago_formatado; ?></td>
                            <td>
                                <form method="POST" action="adm-transferencia-pagamento.php">
                                    <input type="hidden" name="id_ong" value="<?php echo $row['id_ong']; ?>">
                                    <input type="hidden" name="total_doacoes" value="<?php echo $row['total_doacoes']; ?>">
                                    <input type="hidden" name="total_taxa" value="<?php echo $row['total_taxa']; ?>">
                                    <input type="hidden" name="valor_pago" value="<?php echo $valor_pago; ?>">
                                    <button type="submit">Transferir</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>

        <?php if ($error_message): ?>
            <div class="error-message">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
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

</html>
