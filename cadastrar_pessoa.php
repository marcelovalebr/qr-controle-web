<?php
include 'config.php';

$success = false;
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $nome = $_POST['nome'];
    $militar = $_POST['militar'];
    $posto = isset($_POST['posto']) ? $_POST['posto'] : '';
    $secao = isset($_POST['secao']) ? $_POST['secao'] : '';
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    if (empty($cpf) || empty($nome)) {
        $msg = "CPF e Nome são obrigatórios.";
    } else {
        // Evitar SQL Injection escapando as entradas
        $cpf = mysqli_real_escape_string($conn, $cpf);
        $nome = mysqli_real_escape_string($conn, $nome);
        $militar = mysqli_real_escape_string($conn, $militar) == "sim" ? 1 : 0;
        $posto = mysqli_real_escape_string($conn, $posto);
        $secao = mysqli_real_escape_string($conn, $secao);
        $telefone = mysqli_real_escape_string($conn, $telefone);
        $email = mysqli_real_escape_string($conn, $email);

        $sql = "INSERT INTO pessoas (cpf, nome, militar, posto, secao, telefone, email) 
                VALUES ('$cpf', '$nome', '$militar', '$posto', '$secao', '$telefone', '$email')";

        if (mysqli_query($conn, $sql)) {
            $success = true;
            $msg = "Pessoa cadastrada com sucesso.";
        } else {
            $msg = "Erro ao cadastrar pessoa: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Pessoa - QGControle</title>
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
        function toggleSecaoPosto() {
            var militar = document.getElementById("militar").value;
            var secaoGroup = document.getElementById("secao-group");
            var postoGroup = document.getElementById("posto-group");
            if (militar == "sim") {
                secaoGroup.style.display = "block";
                postoGroup.style.display = "block";
            } else {
                secaoGroup.style.display = "none";
                postoGroup.style.display = "none";
            }
        }

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
                            if (response.status === "exists") {
                                showAlert("Pessoa já cadastrada: " + response.nome);
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
        <h1>Cadastrar Pessoa</h1>
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
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="militar">Militar</label>
            <select class="form-control" id="militar" name="militar" onchange="toggleSecaoPosto()" required>
                <option value="nao">Não</option>
                <option value="sim">Sim</option>
            </select>
        </div>
        <div class="form-group" id="posto-group" style="display: none;">
            <label for="posto">Posto/Graduação</label>
            <select class="form-control" id="posto" name="posto">
                <option value="Soldado">Soldado</option>
                <option value="Cabo">Cabo</option>
                <option value="3º Sargento">3º Sargento</option>
                <option value="2º Sargento">2º Sargento</option>
                <option value="1º Sargento">1º Sargento</option>
                <option value="Subtenente">Subtenente</option>
                <option value="2º Tenente">2º Tenente</option>
                <option value="1º Tenente">1º Tenente</option>
                <option value="Capitão">Capitão</option>
                <option value="Major">Major</option>
                <option value="Tenente-Coronel">Tenente-Coronel</option>
                <option value="Coronel">Coronel</option>
                <option value="General de Brigada">General de Brigada</option>
                <option value="General de Divisão">General de Divisão</option>
                <option value="General de Exército">General de Exército</option>
            </select>
        </div>
        <div class="form-group" id="secao-group" style="display: none;">
            <label for="secao">Seção</label>
            <input type="text" class="form-control" id="secao" name="secao">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone (opcional)</label>
            <input type="text" class="form-control" id="telefone" name="telefone">
        </div>
        <div class="form-group">
            <label for="email">Email (opcional)</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
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
