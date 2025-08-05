<?php
session_start();
require 'conexao.php';

// Inicializa carrinho na sessão
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Adicionar produto ao carrinho via GET (exemplo: carrinho.php?add=3)
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
    header('Location: carrinho.php');
    exit;
}

// Remover produto do carrinho via GET (exemplo: carrinho.php?remover=3)
if (isset($_GET['remover'])) {
    $id = intval($_GET['remover']);
    if (isset($_SESSION['carrinho'][$id])) {
        unset($_SESSION['carrinho'][$id]);
    }
    header('Location: carrinho.php');
    exit;
}

// Atualizar quantidades via POST
if (isset($_POST['quantidade'])) {
    foreach ($_POST['quantidade'] as $id => $qtd) {
        $qtd = intval($qtd);
        if ($qtd <= 0) {
            unset($_SESSION['carrinho'][$id]);
        } else {
            $_SESSION['carrinho'][$id] = $qtd;
        }
    }
    header('Location: carrinho.php');
    exit;
}

// Buscar produtos do carrinho no banco
$ids = array_keys($_SESSION['carrinho']);
$produtos = [];

if (count($ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $produtos = $stmt->fetchAll();
}

// Calcular total
$total = 0;
foreach ($produtos as $p) {
    $qtd = $_SESSION['carrinho'][$p['id']];
    $total += $p['preco'] * $qtd;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Carrinho de Compras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
  <h1>Carrinho de Compras</h1>

  <?php if (count($produtos) === 0): ?>
    <div class="alert alert-info">Seu carrinho está vazio.</div>
    <a href="index.php" class="btn btn-primary">Continuar comprando</a>
  <?php else: ?>

    <form method="post">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Produto</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Remover</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): 
            $qtd = $_SESSION['carrinho'][$p['id']];
            $subtotal = $p['preco'] * $qtd;
          ?>
            <tr>
              <td><?= htmlspecialchars($p['nome']) ?></td>
              <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
              <td>
                <input type="number" name="quantidade[<?= $p['id'] ?>]" value="<?= $qtd ?>" min="0" class="form-control" style="width: 80px;" />
              </td>
              <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
              <td><a href="?remover=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover este item?')">X</a></td>
            </tr>
          <?php endforeach; ?>
          <tr>
            <td colspan="3" class="text-end"><strong>Total:</strong></td>
            <td colspan="2"><strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></td>
          </tr>
        </tbody>
      </table>

      <button type="submit" class="btn btn-primary">Atualizar Quantidades</button>
      <a href="checkout.php" class="btn btn-success">Finalizar Compra</a>
      <a href="index.php" class="btn btn-secondary">Continuar Comprando</a>
    </form>

  <?php endif; ?>
</div>

</body>
</html>
