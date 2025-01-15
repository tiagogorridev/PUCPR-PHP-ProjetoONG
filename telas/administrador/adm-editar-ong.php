<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_tipo'] !== 'administrador') {
    header("Location: /telas/usuarios/usu-login.php");
    exit();
}

include('../../db.php');

if (isset($_GET['id_ong'])) {
    $id_ong = $_GET['id_ong'];

    $sql = "SELECT * FROM ONG WHERE id_ong = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id_ong);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        header("Location: adm-ongs.php");
        exit();
    }

    $stmt->close();
} else {
    header("Location: adm-ongs.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];
    $email = $_POST['email'];
    $data_validacao = $_POST['data_validacao'];
    $data_cadastro = $_POST['data_cadastro'];
    $cnpj = $_POST['cnpj'];
    $status = $_POST['status'];
    $constituicao = $_POST['constituicao'];
    $comprobatorio = $_POST['comprobatorio'];
    $estatuto_social = $_POST['estatuto_social'];
    $end_rua = $_POST['end_rua'];
    $end_numero = $_POST['end_numero'];
    $end_bairro = $_POST['end_bairro'];
    $end_cidade = $_POST['end_cidade'];
    $end_estado = $_POST['end_estado'];
    $end_complemento = $_POST['end_complemento'];
    $banco = $_POST['banco'];
    $agencia = $_POST['agencia'];
    $conta_corrente = $_POST['conta_corrente'];
    $chave_pix = $_POST['chave_pix'];

    $sql_update = "UPDATE ONG SET nome = ?, telefone = ?, senha = ?, email = ?, data_validacao = ?, data_cadastro = ?, cnpj = ?, status = ?, constituicao = ?, comprobatorio = ?, estatuto_social = ?, end_rua = ?, end_numero = ?, end_bairro = ?, end_cidade = ?, end_estado = ?, end_complemento = ?, banco = ?, agencia = ?, conta_corrente = ?, chave_pix = ? WHERE id_ong = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("sssssssssssssssssssssi", $nome, $telefone, $senha, $email, $data_validacao, $data_cadastro, $cnpj, $status, $constituicao, $comprobatorio, $estatuto_social, $end_rua, $end_numero, $end_bairro, $end_cidade, $end_estado, $end_complemento, $banco, $agencia, $conta_corrente, $chave_pix, $id_ong);

    if ($stmt_update->execute()) {
        $msg = "Dados atualizados com sucesso!";
    } else {
        $msg = "Erro ao atualizar dados. Tente novamente.";
    }

    $stmt_update->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar ONG</title>
    <link rel="shortcut icon" href="../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/todos-global.css">
    <link rel="stylesheet" href="../../css/adm-editar-ong.css">
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
        <h1 class="title">Editar ONG</h1>

        <?php if (isset($msg)) : ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>

        <form action="adm-editar-ong.php?id_ong=<?php echo $row['id_ong']; ?>" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php echo $row['nome']; ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" value="<?php echo $row['telefone']; ?>" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" value="<?php echo $row['senha']; ?>" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" value="<?php echo $row['email']; ?>" required>

            <label for="data_validacao">Data de Validação:</label>
            <input type="date" name="data_validacao" value="<?php echo $row['data_validacao']; ?>" required>

            <label for="data_cadastro">Data de Cadastro:</label>
            <input type="date" name="data_cadastro" value="<?php echo $row['data_cadastro']; ?>" required>

            <label for="cnpj">CNPJ:</label>
            <input type="text" name="cnpj" value="<?php echo $row['cnpj']; ?>" required>

            <label for="status">Status:</label>
            <input type="text" name="status" value="<?php echo $row['status']; ?>" required>

            <label for="constituicao">Constituição:</label>
            <input type="text" name="constituicao" value="<?php echo $row['constituicao']; ?>" required>

            <label for="comprobatorio">Comprovatório:</label>
            <input type="text" name="comprobatorio" value="<?php echo $row['comprobatorio']; ?>" required>

            <label for="estatuto_social">Estatuto Social:</label>
            <input type="text" name="estatuto_social" value="<?php echo $row['estatuto_social']; ?>" required>

            <label for="end_rua">Rua:</label>
            <input type="text" name="end_rua" value="<?php echo $row['end_rua']; ?>" required>

            <label for="end_numero">Número:</label>
            <input type="text" name="end_numero" value="<?php echo $row['end_numero']; ?>" required>

            <label for="end_bairro">Bairro:</label>
            <input type="text" name="end_bairro" value="<?php echo $row['end_bairro']; ?>" required>

            <label for="end_cidade">Cidade:</label>
            <input type="text" name="end_cidade" value="<?php echo $row['end_cidade']; ?>" required>

            <label for="end_estado">Estado:</label>
            <input type="text" name="end_estado" value="<?php echo $row['end_estado']; ?>" required>

            <label for="end_complemento">Complemento:</label>
            <input type="text" name="end_complemento" value="<?php echo $row['end_complemento']; ?>" required>

            <label for="banco">Banco:</label>
            <input type="text" name="banco" value="<?php echo $row['banco']; ?>" required>

            <label for="agencia">Agência:</label>
            <input type="text" name="agencia" value="<?php echo $row['agencia']; ?>" required>

            <label for="conta_corrente">Conta Corrente:</label>
            <input type="text" name="conta_corrente" value="<?php echo $row['conta_corrente']; ?>" required>

            <label for="chave_pix">Chave PIX:</label>
            <input type="text" name="chave_pix" value="<?php echo $row['chave_pix']; ?>" required>

            <button type="submit">Atualizar</button>
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
</body>

</html>