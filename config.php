<?php
$host = "localhost";
$port = "5432";
$dbname = "site"; // Nome do banco que você criou
$user = "postgres";
$password = "123456"; 

$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$db) {
    die("Erro ao conectar ao banco de dados.");
}
?>