<?php
include 'config.php';

$cpf = $_GET['cpf'];

// Excluir registros associados à pessoa
$sql = "DELETE FROM registros WHERE cpf='$cpf'";
mysqli_query($conn, $sql);

// Excluir a pessoa
$sql = "DELETE FROM pessoas WHERE cpf='$cpf'";
if (mysqli_query($conn, $sql)) {
    $msg = "Pessoa excluída com sucesso.";
} else {
    $msg = "Erro ao excluir pessoa: " . mysqli_error($conn);
}

header("Location: listar_pessoas_cadastradas.php?msg=" . urlencode($msg));
exit;
?>
