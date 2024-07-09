<?php
include 'config.php';

$cpf = $_GET['cpf'];
$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $militar = $_POST['militar'];
    $posto = $_POST['posto'];
    $secao = $_POST['secao'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    // Evitar SQL Injection escapando as entradas
    $nome = mysqli_real_escape_string($conn, $nome);
    $militar = mysqli_real_escape_string($conn, $militar) == "sim" ? 1 : 0;
    $posto = mysqli_real_escape_string($conn, $posto);
    $secao = mysqli_real_escape_string($conn, $secao);
    $telefone = mysqli_real_escape_string($conn, $telefone);
    $email = mysqli_real_escape_string($conn, $email);

    $sql = "UPDATE pessoas SET nome='$nome', militar='$militar', posto='$posto', secao='$secao', telefone='$telefone', email='$email' WHERE cpf='$cpf'";

    if (mysqli_query($conn, $sql)) {
        $msg = "Pessoa alterada com sucesso.";
    } else {
        $msg = "Erro ao alterar pessoa: " . mysqli_error($conn);
    }
}

// Buscar os dados da pessoa para exibir no formulário
$sql = "SELECT * FROM pessoas WHERE cpf='$cpf'";
$result = mysqli_query($conn, $sql);
$pessoa = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Pessoa - QGControle</title>
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
</head>
<body>
<div class="container">
    <div class="header">
        <img src="logo.png" alt="QGControle Logo">
        <h1>Alterar Pessoa</h1>
    </div>
    <?php if ($msg): ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo $pessoa['cpf']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $pessoa['nome']; ?>" required>
        </div>
        <div class="form-group">
            <label for="militar">Militar</label>
            <select class="form-control" id="militar" name="militar" onchange="toggleSecaoPosto()" required>
                <option value="nao" <?php if ($pessoa['militar'] == 0) echo 'selected'; ?>>Não</option>
                <option value="sim" <?php if ($pessoa['militar'] == 1) echo 'selected'; ?>>Sim</option>
            </select>
        </div>
        <div class="form-group" id="posto-group" style="<?php if ($pessoa['militar'] == 0) echo 'display: none;'; ?>">
            <label for="posto">Posto/Graduação</label>
            <select class="form-control" id="posto" name="posto">
                <option value="Soldado" <?php if ($pessoa['posto'] == 'Soldado') echo 'selected'; ?>>Soldado</option>
                <option value="Cabo" <?php if ($pessoa['posto'] == 'Cabo') echo 'selected'; ?>>Cabo</option>
                <option value="3º Sargento" <?php if ($pessoa['posto'] == '3º Sargento') echo 'selected'; ?>>3º Sargento</option>
                <option value="2º Sargento" <?php if ($pessoa['posto'] == '2º Sargento') echo 'selected'; ?>>2º Sargento</option>
                <option value="1º Sargento" <?php if ($pessoa['posto'] == '1º Sargento') echo 'selected'; ?>>1º Sargento</option>
                <option value="Subtenente" <?php if ($pessoa['posto'] == 'Subtenente') echo 'selected'; ?>>Subtenente</option>
                <option value="2º Tenente" <?php if ($pessoa['posto'] == '2º Tenente') echo 'selected'; ?>>2º Tenente</option>
                <option value="1º Tenente" <?php if ($pessoa['posto'] == '1º Tenente') echo 'selected'; ?>>1º Tenente</option>
                <option value="Capitão" <?php if ($pessoa['posto'] == 'Capitão') echo 'selected'; ?>>Capitão</option>
                <option value="Major" <?php if ($pessoa['posto'] == 'Major') echo 'selected'; ?>>Major</option>
                <option value="Tenente-Coronel" <?php if ($pessoa['posto'] == 'Tenente-Coronel') echo 'selected'; ?>>Tenente-Coronel</option>
                <option value="Coronel" <?php if ($pessoa['posto'] == 'Coronel') echo 'selected'; ?>>Coronel</option>
                <option value="General de Brigada" <?php if ($pessoa['posto'] == 'General de Brigada') echo 'selected'; ?>>General de Brigada</option>
                <option value="General de Divisão" <?php if ($pessoa['posto'] == 'General de Divisão') echo 'selected'; ?>>General de Divisão</option>
                <option value="General de Exército" <?php if ($pessoa['posto'] == 'General de Exército') echo 'selected'; ?>>General de Exército</option>
            </select>
        </div>
        <div class="form-group" id="secao-group" style="<?php if ($pessoa['militar'] == 0) echo 'display: none;'; ?>">
            <label for="secao">Seção</label>
            <input type="text" class="form-control" id="secao" name="secao" value="<?php echo $pessoa['secao']; ?>">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone (opcional)</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo $pessoa['telefone']; ?>">
        </div>
        <div class="form-group">
            <label for="email">Email (opcional)</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $pessoa['email']; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Alterar</button>
        <a href="listar_pessoas_cadastradas.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
<footer>
    Desenvolvido pela seção de informática da 14ª Brigada de Infantaria Motorizada 2024
</footer>
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
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
