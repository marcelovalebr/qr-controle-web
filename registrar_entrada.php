<?php
include 'config.php';

date_default_timezone_set('America/Sao_Paulo'); // Definindo o fuso horário de Brasília

$success = false;
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];

    if (empty($cpf)) {
        $msg = "CPF é obrigatório.";
    } else {
        // Evitar SQL Injection escapando as entradas
        $cpf = mysqli_real_escape_string($conn, $cpf);

        // Verificar se o CPF está cadastrado
        $sql_check = "SELECT nome FROM pessoas WHERE cpf = '$cpf'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) == 0) {
            $msg = "CPF não cadastrado. Por favor, cadastre a pessoa primeiro.";
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
                $row_entrada = mysqli_fetch_assoc($result_check_entrada_sem_saida);
                $timestamp = strtotime($row_entrada['timestamp']);
                $data_hora = date('d/m/Y \à\s H:i', $timestamp);
                $msg = "Já existe uma entrada em aberto para $nome registrada em $data_hora. Registre uma saída antes de registrar uma nova entrada.";
            } else {
                // Registrar a entrada
                $sql = "INSERT INTO registros (cpf, acao) VALUES ('$cpf', 'entrada')";
                if (mysqli_query($conn, $sql)) {
                    $success = true;
                    $msg = "Entrada registrada com sucesso para $nome.";
                } else {
                    $msg = "Erro ao registrar entrada: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada - QGControle</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header img {
            width: 150px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #343a40;
            font-weight: bold;
        }
        .form-group label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
        }
        .btn-primary:hover {
            background-color: #495057;
        }
        .alert {
            margin-top: 20px;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #343a40;
            color: #fff;
            margin-top: 30px;
            border-radius: 10px;
        }
    </style>
    <script>
        function showAlert(message) {
            alert(message);
        }

        function checkCPF() {
            var cpf = document.getElementById("cpf").value;
            if (cpf.length == 11) { // Verifica se o CPF tem 11 dígitos
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "verificar_cpf.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            var nomeField = document.getElementById("nome");
                            if (response.status === "exists") {
                                nomeField.value = response.nome;
                            } else if (response.status === "not_exists") {
                                nomeField.value = "CPF não cadastrado.";
                            }
                        }
                    }
                };
                xhr.send("cpf=" + cpf);
            }
        }
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="logo.png" alt="QGControle Logo">
        <h1>Registrar Entrada</h1>
    </div>
    <?php if ($msg): ?>
        <script>
            showAlert('<?php echo $msg; ?>');
        </script>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" required onkeyup="checkCPF()">
        </div>
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" readonly>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
<footer>
    Desenvolvido pela seção de informática da 14ª Brigada de Infantaria Motorizada 2024
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
