<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Controle de Acesso - QGControle</title>
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
        .list-group-item {
            font-weight: bold;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        .list-group-item:hover {
            background-color: #ced4da;
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
        <h1>Sistema de Controle de Acesso</h1>
    </div>
    <div class="list-group">
        <a href="cadastrar_pessoa.php" class="list-group-item list-group-item-action">Cadastrar Pessoa</a>
        <a href="registrar_entrada.php" class="list-group-item list-group-item-action">Registrar Entrada</a>
        <a href="registrar_saida.php" class="list-group-item list-group-item-action">Registrar Saída</a>
        <a href="listar_pessoas_dentro.php" class="list-group-item list-group-item-action">Listar Pessoas Dentro do QG</a>
        <a href="listar_pessoas_cadastradas.php" class="list-group-item list-group-item-action">Listar Pessoas Cadastradas</a>
        <a href="listar_movimentacoes_dia.php" class="list-group-item list-group-item-action">Listar Movimentações do Dia</a>
        <a href="gerar_relatorio.php" class="list-group-item list-group-item-action">Gerar Relatório</a>
    </div>
</div>
<footer>
    Desenvolvido pela seção de informática da 14ª Brigada de Infantaria Motorizada 2024
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
