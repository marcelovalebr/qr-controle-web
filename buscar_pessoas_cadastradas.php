<?php
include 'config.php';

$sql = "SELECT cpf, nome FROM pessoas";
$result = mysqli_query($conn, $sql);

$pessoas_cadastradas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pessoas_cadastradas[] = [
        'cpf' => $row['cpf'],
        'nome' => $row['nome']
    ];
}

echo json_encode($pessoas_cadastradas);
?>
