<?php
include 'config.php';
require('fpdf/fpdf.php'); // Ajuste o caminho para a biblioteca FPDF

if (isset($_GET['data'])) {
    $data = mysqli_real_escape_string($conn, $_GET['data']);
    $data_inicio = date('Y-m-d 00:00:00', strtotime($data));
    $data_fim = date('Y-m-d 23:59:59', strtotime($data));
    $data_atual = date('Y-m-d');

    // Buscar entradas e saídas do dia
    $sql_movimentacoes = "
        SELECT p.nome, r.acao, r.timestamp
        FROM registros r
        JOIN pessoas p ON r.cpf = p.cpf
        WHERE r.timestamp BETWEEN '$data_inicio' AND '$data_fim'
        ORDER BY r.timestamp
    ";
    $result_movimentacoes = mysqli_query($conn, $sql_movimentacoes);

    if (!$result_movimentacoes) {
        die("Erro na consulta de movimentações: " . mysqli_error($conn));
    }

    // Verificar se há registros para a data fornecida
    if (mysqli_num_rows($result_movimentacoes) == 0) {
        echo "<script>alert('Nada encontrado para a data fornecida.'); window.location.href = 'gerar_relatorio.php';</script>";
        exit;
    }

    // Buscar pessoas dentro do quartel no momento, somente se for a data atual
    $result_pessoas_dentro = null;
    if ($data == $data_atual) {
        $sql_pessoas_dentro = "
            SELECT p.nome, r.timestamp 
            FROM pessoas p
            JOIN registros r ON p.cpf = r.cpf
            WHERE r.id IN (
                SELECT MAX(id) FROM registros GROUP BY cpf
            ) AND r.acao = 'entrada'
        ";
        $result_pessoas_dentro = mysqli_query($conn, $sql_pessoas_dentro);

        if (!$result_pessoas_dentro) {
            die("Erro na consulta de pessoas dentro: " . mysqli_error($conn));
        }
    }

    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Relatorio de Movimentacoes - QGControle', 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);

    // Movimentações do dia
    $pdf->Cell(0, 10, 'Movimentacoes do dia ' . date('d/m/Y', strtotime($data)), 0, 1, 'C');
    $pdf->Ln(5);
    $pdf->SetFillColor(200, 200, 200);
    $pdf->Cell(60, 10, 'Nome', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Acao', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Timestamp', 1, 0, 'C', true);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);

    while ($row = mysqli_fetch_assoc($result_movimentacoes)) {
        $pdf->Cell(60, 10, $row['nome'], 1);
        $pdf->Cell(30, 10, ucfirst($row['acao']), 1);
        $pdf->Cell(60, 10, date('d/m/Y H:i:s', strtotime($row['timestamp'])), 1);
        $pdf->Ln();
    }

    if ($data == $data_atual && $result_pessoas_dentro && mysqli_num_rows($result_pessoas_dentro) > 0) {
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Pessoas Dentro do Quartel', 0, 1, 'C');
        $pdf->Ln(5);
        $pdf->SetFillColor(200, 200, 200);
        $pdf->Cell(60, 10, 'Nome', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Entrada', 1, 0, 'C', true);
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);

        while ($row = mysqli_fetch_assoc($result_pessoas_dentro)) {
            $pdf->Cell(60, 10, $row['nome'], 1);
            $pdf->Cell(60, 10, date('d/m/Y H:i:s', strtotime($row['timestamp'])), 1);
            $pdf->Ln();
        }
    }

    // Output PDF
    ob_clean(); // Limpa o buffer de saída para evitar qualquer dado de saída anterior
    $pdf->Output('D', 'relatorio_' . date('d_m_Y', strtotime($data)) . '.pdf');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Relatório - QGControle</title>
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
        .btn-generate, .btn-back {
            background-color: #343a40;
            border: none;
            color: white;
            margin-right: 10px;
        }
        .btn-generate:hover, .btn-back:hover {
            background-color: #495057;
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
        <h1>Gerar Relatório</h1>
    </div>
    <div class="mb-3">
        <input type="date" id="data" class="form-control" style="display: inline-block; width: auto; margin-right: 10px;">
        <button id="generate-btn" class="btn btn-generate">Gerar Relatório</button>
        <a href="index.php" class="btn btn-back">Voltar</a>
    </div>
</div>
<footer>
    Desenvolvido pela seção de informática da 14ª Brigada de Infantaria Motorizada 2024
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('generate-btn').addEventListener('click', function () {
        var data = document.getElementById('data').value;
        if (data) {
            window.location.href = 'gerar_relatorio.php?data=' + data;
        } else {
            alert('Por favor, selecione uma data.');
        }
    });
</script>
</body>
</html>
