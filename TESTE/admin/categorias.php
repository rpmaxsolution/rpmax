<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    die('Acesso negado.');
}

$erro = '';
$sucesso = '';

// Inserir categoria
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_nome'])) {
    $novo_nome = trim($_POST['novo_nome']);
    if ($novo_nome) {
        $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (?)");
        if ($stmt->execute([$novo_nome])) {
            $sucesso = "Categoria adicionada com sucesso.";
        } else {
            $erro = "Erro ao adicionar categoria.";
        }
    } else {
        $erro = "Nome da categoria não pode ser vazio.";
    }
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $idExcluir = intval($_GET['excluir']);
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$idExcluir]);
    header('Location: categorias.php');
    exit;
}

// Listar categorias
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Gerenciar Categorias</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Painel Admin</a>
    <a href="logout.php" class="btn btn-outline-light ms-auto">Sair</a>
  </div>
</nav>

<div class="container">
  <h2>Gerenciar Categorias</h2>

  <?php if ($erro): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
  <?php elseif ($sucesso): ?>
    <div class="alert alert-success"><?= $sucesso ?></div>
  <?php endif; ?>

  <form method="post" class="mb-4">
    <div class="input-group">
      <input type="text" name="novo_nome" class="form-control" placeholder="Nova categoria" required>
      <button type="submit" class="btn btn-primary">Adicionar</button>
    </div>
  </form>

  <table class="table table-striped table-bordered text-center">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($categorias as $cat): ?>
      <tr>
        <td><?= $cat['id'] ?></td>
        <td><?= htmlspecialchars($cat['nome']) ?></td>
        <td>
          <a href="categorias.php?excluir=<?= $cat['id'] ?>" onclick="return confirm('Excluir categoria?');" class="btn btn-danger btn-sm">Excluir</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <a href="index.php" class="btn btn-secondary mt-3">Voltar</a>
</div>

</body>
</html>
