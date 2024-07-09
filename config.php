<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quartel";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define o charset para utf8
$conn->set_charset("utf8mb4");
?>
