<?php
session_start();
require '../../db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    unset($_SESSION['admin_id'], $_SESSION['doador_id'], $_SESSION['ong_id'], $_SESSION['user_tipo']);

    $sql_admin = "SELECT * FROM ADMINISTRADOR WHERE email = ? AND senha = ?";
    $stmt = $conn->prepare($sql_admin);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $result_admin = $stmt->get_result();

    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id_administrador'];
        $_SESSION['admin_nome'] = $admin['nome'];
        $_SESSION['user_tipo'] = 'admin';
        header("Location: /telas/usuarios/usu-index.php");
        exit();
    }

    $sql_doador = "SELECT * FROM DOADOR WHERE email = ? AND senha = ?";
    $stmt2 = $conn->prepare($sql_doador);
    $stmt2->bind_param("ss", $email, $senha);
    $stmt2->execute();
    $result_doador = $stmt2->get_result();

    if ($result_doador->num_rows > 0) {
        $doador = $result_doador->fetch_assoc();
        $_SESSION['doador_id'] = $doador['id_doador'];
        $_SESSION['doador_nome'] = $doador['nome'];
        $_SESSION['user_tipo'] = 'doador';
        header("Location: /telas/usuarios/usu-index.php");
        exit();
    }

    $sql_ong = "SELECT * FROM ONG WHERE email = ? AND senha = ?";
    $stmt3 = $conn->prepare($sql_ong);
    $stmt3->bind_param("ss", $email, $senha);
    $stmt3->execute();
    $result_ong = $stmt3->get_result();

    if ($result_ong->num_rows > 0) {
        $ong = $result_ong->fetch_assoc();

        if (strtolower($ong['status']) === 'ativo') {
            $_SESSION['ong_id'] = $ong['id_ong'];
            $_SESSION['ong_nome'] = $ong['nome'];
            $_SESSION['user_tipo'] = 'ong';
            header("Location: /telas/usuarios/usu-index.php");
            exit();
        } else {
            echo "<div class='error-message'>Sua ONG foi desativada. Entre em contato com o suporte.</div>";
            exit();
        }
    }

    echo "<div class='error-message'>Email ou senha inválidos.</div>";
}

if (isset($stmt)) $stmt->close();
if (isset($stmt2)) $stmt2->close();
if (isset($stmt3)) $stmt3->close();
$conn->close();
