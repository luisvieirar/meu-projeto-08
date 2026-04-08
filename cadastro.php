<?php
include('config.php');
session_start();
if (!isset($_SESSION['logado'])) header("Location: index.php");

$id_editar = isset($_GET['editar']) ? intval($_GET['editar']) : 0;
$dados = ['nome'=>'', 'cargo'=>'', 'email'=>'', 'telefone'=>'', 'situacao'=>'t'];

// Carregar dados para edição
if ($id_editar > 0) {
    $query = pg_query($db, "SELECT * FROM funcionarios WHERE id = $id_editar");
    $dados = pg_fetch_assoc($query);
}

// Salvar (Insert ou Update)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['salvar'])) {
    $nome = pg_escape_string($_POST['nome']);
    $cargo = pg_escape_string($_POST['cargo']);
    $email = pg_escape_string($_POST['email']);
    $telefone = pg_escape_string($_POST['telefone']);
    $situacao = $_POST['sit'] == 't' ? 't' : 'f';
    
    if ($id_editar > 0) {
        // UPDATE
        $sql = "UPDATE funcionarios SET 
                nome='$nome', cargo='$cargo', email='$email', 
                telefone='$telefone', situacao='$situacao' 
                WHERE id=$id_editar";
    } else {
        // INSERT
        $sql = "INSERT INTO funcionarios (nome, cargo, email, telefone, situacao) 
                VALUES ('$nome', '$cargo', '$email', '$telefone', '$situacao')";
    }
    
    if (pg_query($db, $sql)) {
        echo "<script>alert('Salvo com sucesso!'); window.location='listagem.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar: " . pg_last_error($db) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro de Funcionários</title>
</head>
<body>
    <div class="navbar">
        <div><strong>🌐 Cadastro de Funcionários</strong></div>
        <div class="nav-links">
            <a href="listagem.php">Listagem</a>
        </div>
        <div>Olá, <?php echo $_SESSION['user_admin']; ?> ▾</div>
    </div>

    <div class="main-wrapper">
        <h2 style="color: #3b66a6; width: 100%; max-width: 900px; margin-bottom: 20px;">
            <?php echo $id_editar > 0 ? 'Editar' : 'Cadastro'; ?> de Funcionários
        </h2>
        
        <div class="card form-card">
            <div class="card-header">👤 <?php echo $id_editar > 0 ? 'Editar' : 'Novo'; ?> Funcionário</div>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>ID: <?php echo $id_editar > 0 ? "#$id_editar" : 'Automático'; ?></label>
                        <input type="text" value="<?php echo $id_editar > 0 ? $id_editar : 'Novo'; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" required value="<?php echo htmlspecialchars($dados['nome']); ?>" placeholder="Nome do Funcionário">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Cargo *</label>
                        <select name="cargo" required>
                            <option value="">Selecione...</option>
                            <option <?php echo $dados['cargo']=='Administrador' ? 'selected' : ''; ?>>Administrador</option>
                            <option <?php echo $dados['cargo']=='Gerente' ? 'selected' : ''; ?>>Gerente</option>
                            <option <?php echo $dados['cargo']=='Assistente' ? 'selected' : ''; ?>>Assistente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>E-mail *</label>
                        <input type="email" name="email" required value="<?php echo htmlspecialchars($dados['email']); ?>" placeholder="exemplo@email.com">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" value="<?php echo htmlspecialchars($dados['telefone']); ?>" placeholder="(00) 00000-0000">
                    </div>
                    <div class="form-group">
                        <label>Situação</label>
                        <div style="margin-top: 10px; display: flex; gap: 20px;">
                            <label style="font-weight: normal;">
                                <input type="radio" name="sit" value="t" <?php echo $dados['situacao']=='t' ? 'checked' : ''; ?>> Ativo
                            </label>
                            <label style="font-weight: normal;">
                                <input type="radio" name="sit" value="f" <?php echo $dados['situacao']=='f' ? 'checked' : ''; ?>> Inativo
                            </label>
                        </div>
                    </div>
                </div>

                <div class="button-container">
                    <button type="submit" name="salvar" class="btn-blue">💾 Salvar</button>
                    <button type="reset" class="btn-gray">🗑️ Limpar</button>
                    <button type="button" class="btn-gray" onclick="location.href='listagem.php'">◀ Voltar</button>
                    <button type="button" class="btn-gray" onclick="window.close()">❌ Fechar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>