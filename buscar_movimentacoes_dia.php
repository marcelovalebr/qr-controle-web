<?php
include 'config.php';

if (isset($_GET['data'])) {
    $data = mysqli_real_escape_string($conn, $_GET['data']);
    $data_inicio = date('Y-m-d 00:00:00', strtotime($data));
    $data_fim = date('Y-m-d 23:59:59', strtotime($data));

    $sql = "
        SELECT p.nome, r.acao, r.timestamp
        FROM registros r
        JOIN pessoas p ON r.cpf = p.cpf
        WHERE r.timestamp BETWEEN '$data_inicio' AND '$data_fim'
        ORDER BY r.timestamp
    ";

    $result = mysqli_query($conn, $sql);

    $movimentacoes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $movimentacoes[] = [
            'nome' => $row['nome'],
            'acao' => $row['acao'],
            'timestamp' => date('d/m/Y H:i:s', strtotime($row['timestamp']))
        ];
    }

    echo json_encode($movimentacoes);
}
?>
