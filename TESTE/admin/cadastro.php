<?php
require '../conexao.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $imagem = '';

    // Upload da imagem (se existir)
    if (!empty($_FILES['imagem']['name'])) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $imagem = uniqid() . '.' . $extensao;
            move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens/' . $imagem);
        } else {
            $mensagem = "Tipo de imagem não permitido.";
        }
    }

    // Inserir no banco
    if (!$mensagem) {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, descricao, imagem) VALUES (?, ?, ?, ?)");
        $ok = $stmt->execute([$nome, $preco, $descricao, $imagem]);

        if ($ok) {
            $mensagem = "✅ Produto cadastrado com sucesso!";
        } else {
            $mensagem = "❌ Erro ao cadastrar o produto.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastrar Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">Cadastrar Produto</h2>

  <?php if (!empty($mensagem)): ?>
    <div class="alert alert-info"><?= $mensagem ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white">
    <div class="mb-3">
      <label class="form-label">Nome do Produto</label>
      <input type="text" name="nome" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Preço (ex: 49.90)</label>
      <input type="text" name="preco" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem</label>
      <input type="file" name="imagem" class="form-control">
    </div>

    <button type="submit" class="btn btn-primary">Cadastrar</button>
    <a href="index.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

</body>
</html>
