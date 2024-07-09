<?php
include 'config.php';

$sql = "
    SELECT p.nome, r.timestamp 
    FROM pessoas p
    JOIN registros r ON p.cpf = r.cpf
    WHERE r.id IN (
        SELECT MAX(id) FROM registros GROUP BY cpf
    ) AND r.acao = 'entrada'
";

$result = mysqli_query($conn, $sql);

$pessoas_dentro = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pessoas_dentro[] = [
        'nome' => $row['nome'],
        'timestamp' => date('d/m/Y H:i:s', strtotime($row['timestamp']))
    ];
}

echo json_encode($pessoas_dentro);
?>
