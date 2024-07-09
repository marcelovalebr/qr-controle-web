<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pessoas Cadastradas - QGControle</title>
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
        .btn-refresh, .btn-back {
            background-color: #343a40;
            border: none;
            color: white;
            margin-right: 10px;
        }
        .btn-refresh:hover, .btn-back:hover {
            background-color: #495057;
        }
        .btn-edit, .btn-delete {
            margin-right: 5px;
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
        function fetchPessoasCadastradas() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "buscar_pessoas_cadastradas.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        var listGroup = document.getElementById("pessoas-list");
                        listGroup.innerHTML = "";
                        if (response.length > 0) {
                            response.forEach(function (pessoa) {
                                var listItem = document.createElement("li");
                                listItem.className = "list-group-item d-flex justify-content-between align-items-center";
                                listItem.textContent = pessoa.nome + " - CPF: " + pessoa.cpf;

                                var btnGroup = document.createElement("div");

                                var editBtn = document.createElement("button");
                                editBtn.className = "btn btn-primary btn-edit";
                                editBtn.textContent = "Alterar";
                                editBtn.onclick = function () {
                                    window.location.href = 'alterar_pessoa.php?cpf=' + pessoa.cpf;
                                };

                                var deleteBtn = document.createElement("button");
                                deleteBtn.className = "btn btn-danger btn-delete";
                                deleteBtn.textContent = "Excluir";
                                deleteBtn.onclick = function () {
                                    if (confirm("Tem certeza que deseja excluir " + pessoa.nome + "?")) {
                                        window.location.href = 'excluir_pessoa.php?cpf=' + pessoa.cpf;
                                    }
                                };

                                btnGroup.appendChild(editBtn);
                                btnGroup.appendChild(deleteBtn);
                                listItem.appendChild(btnGroup);

                                listGroup.appendChild(listItem);
                            });
                        } else {
                            var listItem = document.createElement("li");
                            listItem.className = "list-group-item";
                            listItem.textContent = "Nenhuma pessoa encontrada.";
                            listGroup.appendChild(listItem);
                        }
                    }
                }
            };
            xhr.send();
        }

        document.addEventListener("DOMContentLoaded", function() {
            fetchPessoasCadastradas();

            document.getElementById("refresh-btn").addEventListener("click", function() {
                fetchPessoasCadastradas();
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <img src="logo.png" alt="QGControle Logo">
        <h1>Pessoas Cadastradas</h1>
    </div>
    <div class="mb-3">
        <button id="refresh-btn" class="btn btn-refresh">Atualizar Lista</button>
        <a href="index.php" class="btn btn-back">Voltar</a>
    </div>
    <ul id="pessoas-list" class="list-group"></ul>
</div>
<footer>
    Desenvolvido pela seção de informática da 14ª Brigada de Infantaria Motorizada 2024
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
