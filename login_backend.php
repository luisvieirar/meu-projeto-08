<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = pg_escape_string($_POST['usuario']);
    $senha = pg_escape_string($_POST['senha']);
    
    // Primeiro, vamos verificar se existe a tabela usuarios
    $check = pg_query($db, "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senha'");
    
    if ($check && pg_num_rows($check) > 0) {
        $_SESSION['logado'] = true;
        $_SESSION['user_admin'] = $usuario;
        header("Location: cadastro.php");
        exit;
    } else {
        echo "<script>alert('Usuário ou Senha inválidos!'); window.location='index.php';</script>";
    }
} else {
    header("Location: index.php");
}
?>