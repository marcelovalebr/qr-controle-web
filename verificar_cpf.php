<?php
include 'config.php';

if (isset($_POST['cpf'])) {
    $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
    
    $sql = "SELECT nome FROM pessoas WHERE cpf = '$cpf'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(['status' => 'exists', 'nome' => $row['nome']]);
    } else {
        echo json_encode(['status' => 'not_exists']);
    }
}
?>
