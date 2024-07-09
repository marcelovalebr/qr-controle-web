<?php
include 'config.php';

if (isset($_POST['cpf'])) {
    $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
    
    // Verificar se o CPF está cadastrado
    $sql_check = "SELECT nome FROM pessoas WHERE cpf = '$cpf'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) == 0) {
        echo json_encode(['status' => 'not_exists']);
    } else {
        $row = mysqli_fetch_assoc($result_check);
        $nome = $row['nome'];

        // Verificar se há uma entrada sem uma saída correspondente
        $sql_check_entrada_sem_saida = "
            SELECT * FROM registros 
            WHERE cpf = '$cpf' 
            AND acao = 'entrada' 
            AND id > IFNULL(
                (SELECT MAX(id) FROM registros WHERE cpf = '$cpf' AND acao = 'saida'),
                0
            )
        ";
        $result_check_entrada_sem_saida = mysqli_query($conn, $sql_check_entrada_sem_saida);

        if (mysqli_num_rows($result_check_entrada_sem_saida) > 0) {
            echo json_encode(['status' => 'exists', 'nome' => $nome]);
        } else {
            echo json_encode(['status' => 'no_entry']);
        }
    }
}
?>
