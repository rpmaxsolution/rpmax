<?php
require 'conexao.php';

$categoria_id = $_GET['categoria'] ?? null;
if ($categoria_id) {
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE categoria_id = ?");
    $stmt->execute([$categoria_id]);
} else {
    $stmt = $pdo->query("SELECT * FROM produtos");
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Loja Virtual</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Minha Loja</a>
    <a class="btn btn-outline-light ms-auto" href="carrinho.php">Carrinho</a>
  </div>
</nav>

<!-- Container principal -->
<div class="container">
  <form method="get" class="mb-4">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <select name="categoria" class="form-select" onchange="this.form.submit()">
          <option value="">Todas as categorias</option>
          <?php foreach ($categorias as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($categoria_id == $cat['id']) ? 'selected' : '' ?>>
              <?= $cat['nome'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </form>

  <div class="row">
    <?php foreach ($produtos as $produto): ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <img src="imagens/<?= $produto['imagem'] ?>" class="card-img-top" alt="<?= $produto['nome'] ?>" style="height: 200px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title"><?= $produto['nome'] ?></h5>
            <p class="card-text"><?= $produto['descricao'] ?></p>
            <p class="text-success fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            <a href="carrinho.php?add=<?= $produto['id'] ?>" class="btn btn-primary w-100">Adicionar ao Carrinho</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<footer class="bg-dark text-light text-center py-3 mt-5">
  &copy; <?= date('Y') ?> Minha Loja. Todos os direitos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
