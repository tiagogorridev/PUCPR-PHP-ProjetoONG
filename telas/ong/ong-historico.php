<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'ong') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Começo</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/historicos.css">
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
                    <li>
                        <button class="btn-icon-header" onclick="toggleSideBar()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
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
                <a href="../../telas/administrador/adm-configuracoes.php">
                    <img class="img-user" src="../../assets/user.png" alt="Usuário">
                </a>
            </div>
        </nav>
    </header>
    <div class="container">
        <h1 class="title">Histórico de Transferência Recebidas</h1>
        <div class="filter-options">
            <select id="filter-ong">
                <option value="all">Filtros</option>
                <option value="ong1">ONG 1</option>
                <option value="ong2">ONG 2</option>
                <option value="ong3">ONG 3</option>
            </select>
            <div style="display: flex; gap: 15px; align-items: center;">
                <label for="start-date">De:</label>
                <input type="date" id="start-date">
                <label for="end-date">Até:</label>
                <input type="date" id="end-date">
            </div>
        </div>
        <div class="donation-box">
            <div class="circle">
                <p>ONG: xxx</p>
            </div>
            <div class="donation-details">
                <p>Doação para a ONG xxx</p>
                <p>Data de doação: xx/xx/xxxx</p>
                <p>Valor da doação: R$ xxx,xx</p>
            </div>
        </div>
        <div class="donation-box">
            <div class="circle">
                <p>ONG: xxx</p>
            </div>
            <div class="donation-details">
                <p>Doação para a ONG xxx</p>
                <p>Data de doação: xx/xx/xxxx</p>
                <p>Valor da doação: R$ xxx,xx</p>
            </div>
        </div>
        <div class="donation-box">
            <div class="circle">
                <p>ONG: xxx</p>
            </div>
            <div class="donation-details">
                <p>Doação para a ONG xxx</p>
                <p>Data de doação: xx/xx/xxxx</p>
                <p>Valor da doação: R$ xxx,xx</p>
            </div>
        </div>
    </div>
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
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>