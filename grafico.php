<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('db.php'); 
if (!$mysqli) {
    die("Erro de conexão: " . $mysqli->connect_error);
}

$data_inicio = $_GET['data_inicio']; 
$data_fim = $_GET['data_fim'];

$data_inicio .= " 00:00:00"; 
$data_fim .= " 23:59:59"; 

$sql = "SELECT ONG.nome, SUM(DOACAO.valor_total) AS valor_total
        FROM DOACAO
        JOIN ONG ON DOACAO.id_ong = ONG.id_ong
        WHERE DOACAO.data_hora BETWEEN ? AND ?
        GROUP BY ONG.nome";

$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $mysqli->error);
}

$stmt->bind_param('ss', $data_inicio, $data_fim);

if (!$stmt->execute()) {
    die("Erro ao executar a consulta: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $dados = [];
    while ($row = $result->fetch_assoc()) {
        $dados[] = $row;
    }
    echo json_encode($dados);
} else {
    echo json_encode([]);
}
