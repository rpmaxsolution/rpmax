<?php
session_start();
require 'conexao.php';

$produtos = [];
$total = 0;

if (!empty($_SESSION['carrinho'])) {
    $ids = implode(',', array_keys($_SESSION['carrinho']));
    $stmt = $pdo->query("SELECT * FROM produtos WHERE id IN ($ids)");
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aqui você pode salvar o pedido no banco, enviar e-mail, etc.
    $_SESSION['carrinho'] = [];
    $mensagem = "Pedido realizado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Finalizar Compra</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">Minha Loja</a>
  </div>
</nav>

<div class="container">
  <h2 class="mb-4">Finalizar Compra</h2>

  <?php if (isset($mensagem)): ?>
    <div class="alert alert-success"><?= $mensagem ?></div>
    <a href="index.php" class="btn btn-primary">Voltar para a Loja</a>
  <?php elseif (empty($produtos)): ?>
    <div class="alert alert-warning">Seu carrinho está vazio.</div>
    <a href="index.php" class="btn btn-secondary">Voltar à Loja</a>
  <?php else: ?>
    <ul class="list-group mb-4">
      <?php foreach ($produtos as $produto): 
        $qtd = $_SESSION['carrinho'][$produto['id']];
        $subtotal = $produto['preco'] * $qtd;
        $total += $subtotal;
      ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <?= $produto['nome'] ?> (x<?= $qtd ?>)
          <span class="fw-bold">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
        </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
        <strong>Total:</strong>
        <strong class="text-success">R$ <?= number_format($total, 2, ',', '.') ?></strong>
      </li>
    </ul>

    <form method="post">
      <div class="mb-3">
        <label class="form-label">Nome Completo</label>
        <input type="text" name="nome" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Endereço de Entrega</label>
        <textarea name="endereco" class="form-control" required></textarea>
      </div>
      <button type="submit" class="btn btn-success">Confirmar Pedido</button>
    </form>
  <?php endif; ?>
</div>

<footer class="bg-dark text-light text-center py-3 mt-5">
  &copy; <?= date('Y') ?> Minha Loja. Todos os direitos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
