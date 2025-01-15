<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
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
    <link rel="stylesheet" href="../../css/adm-transferencias.css">
</head>

<body>
    <header>
        <nav class="navbar nav-lg-screen" id="navbar">
            <button class="btn-icon-header" onclick="toggleSideBar()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                </svg>
            </button>
            <div><img class="img-logo" id="logo" src="../../assets/logo.png" alt="Logo"></div>
            <div class="nav-links" id="nav-links">
                <ul>
                    <li><button class="btn-icon-header" onclick="toggleSideBar()">
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
            <div class="user"><a href="adm-configuracoes.php"><img class="img-user" src="../../assets/user.png" alt="Usuário"></a></div>
        </nav>
    </header>

    <main>
        <div class="container">
            <section class="donation-box">
                <h1 class="ong-name">Transferir Para ONG</h1>
                <div class="donation-image"><img src="../../assets/imagem-ong-1.jpg" alt="Imagem da ONG"></div>
                <div class="input-box" style="text-align: center; width: 70%; margin: 0 auto;">
                    <label for="ong" style="font-size: 1.2em;">Selecione a ONG desejada:</label>
                    <select id="ong" name="ong" required style="font-size: 1.2em;">
                        <option value="" disabled selected></option>
                        <option value="1">Mão Amiga</option>
                        <option value="2">Amigos do Bem</option>
                        <option value="3">Cultivando a Vida</option>
                        <option value="4">Mais União</option>
                        <option value="5">Amigos da Terra</option>
                        <option value="6">Amor Animal</option>
                    </select>
                </div>
                <script>
                    document.getElementById('ong').addEventListener('change', function() {
                        const selectedOngId = this.value;
                        window.location.href = `../../telas/administrador/adm-ong1.php?id_ong=${selectedOngId}`;
                    });
                </script>
            </section>
        </div>
    </main>

    <footer>
        <div class="footer">
            <div class="img-footer-start"><img class="boneco-footer" src="../../assets/img-footer.png" alt="Imagem de rodapé 1"></div>
            <div class="sociais">
                <div class="icons-col-1">
                    <div class="social-footer"><img class="icon-footer" src="../../assets/google.png" alt="Ícone do Google">
                        <p>novocomeço@gmail.com</p>
                    </div>
                    <div class="social-footer"><img class="icon-footer" src="../../assets/instagram.png" alt="Ícone do Instagram">
                        <p>@novocomeço</p>
                    </div>
                </div>
                <div class="icons-col-2">
                    <div class="social-footer"><img class="icon-footer" src="../../assets/whatsapp.png" alt="Ícone do WhatsApp">
                        <p>(41)99997676</p>
                    </div>
                    <div class="social-footer"><img class="icon-footer" src="../../assets/facebook.png" alt="Ícone do Facebook">
                        <p>@novocomeco</p>
                    </div>
                </div>
            </div>
            <div class="img-footer-end"><img class="boneco-footer" src="../../assets/img-footer.png" alt="Imagem de rodapé 2"></div>
        </div>
    </footer>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
    <script src="../../js/header.js"></script>

</body>

</html>