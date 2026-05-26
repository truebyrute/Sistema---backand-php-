<?php
$host = 'localhost'; 
$user = 'root';
$password = 'aluno'; 
$database = 'banco_noite';

// Criando conexão
$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Erro na conexão: " . mysqli_connect_error());
}

?>