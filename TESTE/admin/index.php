<?php
require '../conexao.php';

$produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Painel Administrativo - Produtos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <h1 class="mb-4">Painel Administrativo</h1>
  <a href="cadastrar.php" class="btn btn-success mb-3">+ Cadastrar Novo Produto</a>

  <?php if (count($produtos) > 0): ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Imagem</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): ?>
            <tr>
              <td><?= $p['id'] ?></td>
              <td>
                <?php if ($p['imagem']): ?>
                  <img src="../imagens/<?= $p['imagem'] ?>" width="60">
                <?php else: ?>
                  Sem imagem
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($p['nome']) ?></td>
              <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
              <td><?= nl2br(htmlspecialchars($p['descricao'])) ?></td>
              <td>
                <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="excluir.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este produto?')">Excluir</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <div class="alert alert-warning">Nenhum produto cadastrado ainda.</div>
  <?php endif; ?>
</div>

</body>
</html>



