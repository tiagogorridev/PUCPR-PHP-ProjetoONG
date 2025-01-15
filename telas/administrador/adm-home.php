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
    <link rel="stylesheet" href="../../css/usuario-home.css">
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

    <main tax`bindex="0" class="content" id="content" onclick="closeSideBar()">
        <section class="banner-intro">
            <div class="text-banner">
                <div class="first-line-banner">
                    <div>
                        <span class="tittle-banner">NOVO</span>
                    </div>
                    <div class="a">
                        <a class="btn-quero-doar" href="../usuarios/pagina-quero-doar">QUERO DOAR!</a>
                    </div>
                </div>
                <div>
                    <span class="tittle-banner">COMEÇO</span>
                </div>
            </div>
            <div>
                <img class="img-banner" src="../../assets/banner.png">
            </div>
            <div class="quero-doar">
                <a class="btn-quero-doar-mobile" href="queroDoar.html">QUERO DOAR!</a>
            </div>
        </section>

        <section class="banner-cards-ongs">
            <div class="card-ong">
                <img class="img-ong" src="../../assets/ong-1.png">
                <a class="btn-conhecer" href="../usuarios/usu-ong-1.php">CONHECER</a>
            </div>
            <div class="card-ong">
                <img class="img-ong" src="../../assets/ong-2.png">
                <a class="btn-conhecer" href="../usuarios/usu-ong-2.php">CONHECER</a>
            </div>
            <div class="card-ong">
                <img class="img-ong" src="../../assets/ong-3.png">
                <a class="btn-conhecer" href="../usuarios/usu-ong-3.php">CONHECER</a>
            </div>
        </section>

        <section class="banner-cards-clientes">
            <div class="card-doador">
                <div>
                </div>
                <p>
                    "A solidariedade é um ato poderoso que transforma vidas. Contribuir com amor e compaixão é um
                    pequeno gesto que pode
                    criar um impacto enorme!"<br>Cesar Yoshio
                </p>
            </div>
            <div class="card-doador">
                <p>
                    A doação é uma maneira simples de espalhar esperança. Saber que minha contribuição pode mudar uma
                    vida me motiva a
                    continuar ajudando!"<br>Tiago Gorri
                </p>
            </div>
            <div class="card-doador">
                <p>
                    "Ao ajudar o próximo, plantamos sementes de esperança. Cada doação traz um pouco mais de luz e faz a
                    diferença no
                    caminho de alguém."<br>Matheus Muller
                </p>
            </div>
        </section>
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

    <script src="../../js/header.js"></script>

</body>

</html>