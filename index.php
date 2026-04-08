<?php
session_start();
if (isset($_SESSION['logado'])) header("Location: cadastro.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Login - Sistema</title>
</head>
<body>
    <div class="main-wrapper">
        <div class="card login-card">
            <div class="card-header">👤 Login - Cadastro de Funcionários</div>
            <form action="login_backend.php" method="POST">
                <div class="form-group" style="margin-bottom: 15px;">
                    <input type="text" name="usuario" placeholder="👤 Usuário" required style="width:100%; padding:10px;">
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <input type="password" name="senha" placeholder="🔒 Senha" required style="width:100%; padding:10px;">
                </div>
                <button type="submit" class="btn-blue btn-full">Entrar</button>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="#" style="color: #4a76ba; text-decoration: none; font-size: 13px;">Esqueci minha senha</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>