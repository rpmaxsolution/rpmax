<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    die('Acesso negado.');
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    die("Produto não encontrado.");
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = floatval($_POST['preco']);
    $categoria_id = intval($_POST['categoria_id']);
    $imagem_nome = $produto['imagem'];

    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../imagens/" . $imagem_nome);
    }

    $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, imagem=?, categoria_id=? WHERE id=?");
    if ($stmt->execute([$nome, $descricao, $preco, $imagem_nome, $categoria_id, $id])) {
        header('Location: index.php');
        exit;
    } else {
        $erro = "Erro ao atualizar produto.";
    }
}

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Editar Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-4">
  <h2>Editar Produto #<?= $produto['id'] ?></h2>
  <?php if ($erro): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
  <?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label class="form-label">Nome</label>
      <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" required><?= htmlspecialchars($produto['descricao']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Preço</label>
      <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Categoria</label>
      <select name="categoria_id" class="form-select" required>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['id'] ?>" <?= ($produto['categoria_id'] == $c['id']) ? 'selected' : '' ?>><?= $c['nome'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Imagem atual</label><br>
      <?php if ($produto['imagem']): ?>
        <img src="../imagens/<?= $produto['imagem'] ?>" width="150" style="object-fit:cover;"><br><br>
      <?php else: ?>
        Nenhuma imagem
      <?php endif; ?>
      <label class="form-label mt-2">Nova imagem (opcional)</label>
      <input type="file" name="imagem" class="form-control" accept="image/*">
    </div>
    <button class="btn btn-primary" type="submit">Salvar Alterações</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>
