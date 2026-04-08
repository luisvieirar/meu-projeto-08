<?php
include('config.php');
session_start();
if (!isset($_SESSION['logado'])) header("Location: index.php");

// Excluir
if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    pg_query($db, "DELETE FROM funcionarios WHERE id = $id");
    header("Location: listagem.php");
    exit;
}

$busca = isset($_GET['b']) ? pg_escape_string($_GET['b']) : '';
$sql = "SELECT * FROM funcionarios WHERE nome ILIKE '%$busca%' ORDER BY id ASC";
$result = pg_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Listagem de Funcionários</title>
</head>
<body>
    <div class="navbar">
        <div><strong>🌐 Sistema de Gestão</strong></div>
        <div><a href="cadastro.php">➕ Novo Cadastro</a> | <a href="logout.php">🚪 Sair</a></div>
    </div>
    <div class="main-wrapper">
        <div class="card" style="max-width: 1200px; width: 100%;">
            <h3 style="color:#3b66a6;">📋 Listagem de Funcionários</h3>
            
            <form method="GET" style="display:flex; gap:10px; margin-bottom:20px;">
                <input type="text" name="b" placeholder="🔍 Buscar funcionário..." value="<?php echo htmlspecialchars($busca); ?>" style="flex:1; padding:10px; border:1px solid #ddd; border-radius:4px;">
                <button type="submit" class="btn-blue">🔎 Pesquisar</button>
                <button type="button" class="btn-gray" onclick="location.href='listagem.php'">🔄 Limpar</button>
            </form>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Nome</th><th>Cargo</th><th>E-mail</th><th>Situação</th><th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ($result && pg_num_rows($result) > 0):
                        while($r = pg_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td style="font-weight:bold;"><?php echo htmlspecialchars($r['nome']); ?></td>
                        <td><?php echo htmlspecialchars($r['cargo']); ?></td>
                        <td><?php echo htmlspecialchars($r['email']); ?></td>
                        <td>
                            <span style="background: <?php echo $r['situacao']=='t' ? '#72b572' : '#dc3545'; ?>; color:white; padding:3px 8px; border-radius:3px;">
                                <?php echo $r['situacao']=='t' ? 'Ativo' : 'Inativo'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="cadastro.php?editar=<?php echo $r['id']; ?>" title="Editar" style="margin:0 5px; text-decoration:none;">✏️</a>
                            <a href="#" onclick="enviarEmail('<?php echo $r['email']; ?>')" title="Enviar E-mail" style="margin:0 5px; text-decoration:none;">📧</a>
                            <a href="?excluir=<?php echo $r['id']; ?>" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir <?php echo addslashes($r['nome']); ?>?')" style="margin:0 5px; text-decoration:none; color:#dc3545;">🗑️</a>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding:40px;">Nenhum funcionário encontrado</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    function enviarEmail(email) {
        window.location.href = "mailto:" + email;
    }
    </script>
</body>
</html>