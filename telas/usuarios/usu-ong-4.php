<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../db.php');

$current_url = $_SERVER['REQUEST_URI'];
if (preg_match('/ong-(\d+)\.php/', $current_url, $matches)) {
    $ong_id = $matches[1];
}

if (isset($ong_id)) {
    $sql = "SELECT id_ong, nome, chave_pix FROM ONG WHERE id_ong = ?";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $ong_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $nome_ong = $row['nome'];
            $chave_pix = $row['chave_pix'];
        } else {
            echo "ONG não encontrada.";
        }
    } else {
        echo "Erro na consulta SQL.";
    }
} else {
    echo "ID da ONG não especificado.";
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
            <h1 class="ong-name">Realizar Doação</h1>
            <div class="donation-image">
                <img src="../../assets/ong-4.png" alt="Imagem da ONG">
            </div>
            <div class="ong-description-box">
                <p>Mais União tem como missão unir forças para apoiar causas sociais, promovendo campanhas de arrecadação e ações que conectem doadores a projetos que impactam positivamente comunidades carentes.</p>
            </div>

            <div class="error-message" id="error-message" style="display: none;">
                <div class="error-popup">
                    <p><strong>Erro:</strong> O valor da doação deve ser no mínimo R$5.</p>
                    <button class="close-btn" onclick="closeErrorPopup()">X</button>
                </div>
            </div>

            <div class="input-box">
                <label for="valor">Valor (R$):</label>
                <input type="number" id="valor" name="valor" placeholder="Digite o valor da doação (somente números)" required>
            </div>

            <p class="note">*Somente PIX</p>
            <p class="note">*Minimo: R$5</p>

            <div class="button-container">
                <div class="cancel-button" onclick="window.location.href='../usuarios/usu-ongs.php'">
                    <p>Cancelar doação</p>
                </div>
                <div class="confirm-button" onclick="validateDonation()">
                    <p>Confirmar doação</p>
                </div>
            </div>

        </section>
    </main>
    <div class="popup-overlay" id="popup-error">
        <div class="popup-box">
            <h2>Erro</h2>
            <p>Somente doadores podem realizar doações. Você será redirecionado para a página inicial.</p>
            <button class="popup-btn" onclick="redirectToHome()">OK</button>
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

    <script>
        function validateDonation() {
            const valor = document.getElementById('valor').value;
            const ong_id = <?php echo $ong_id; ?>;

            if (valor >= 5) {
                const taxa = (valor * 0.05).toFixed(2);

                <?php if (isset($_SESSION['user_tipo']) && $_SESSION['user_tipo'] === 'doador') { ?>
                    const nome_doador = '<?php echo $_SESSION['user_nome'] ?? "Anônimo"; ?>';
                    window.location.href = `../doador/doador-doacao.php?ong=${ong_id}&valor=${valor}&taxa=${taxa}&doador=${nome_doador}`;
                <?php } else { ?>
                    document.getElementById('popup-error').style.display = 'flex';
                <?php } ?>
            } else {
                alert("O valor mínimo para doação é R$5.");
            }
        }

        function redirectToHome() {
            document.getElementById('popup-error').style.display = 'none';
            window.location.href = '../../telas/usuarios/usu-index.php';
        }
    </script>
</body>

</html>