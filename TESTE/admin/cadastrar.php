

<?php
require '../conexao.php';

$mensagem = '';

// Buscar categorias para o select
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $preco = str_replace(',', '.', $_POST['preco'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $categoria_id = $_POST['categoria_id'] ?? null;
    $imagem = '';

    // Upload da imagem
    if (!empty($_FILES['imagem']['name'])) {
        $ext = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $permitidas)) {
            $imagem = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], '../imagens/' . $imagem);
        } else {
            $mensagem = "Tipo de imagem não permitido.";
        }
    }

    if (!$mensagem) {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, preco, descricao, categoria_id, imagem) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$nome, $preco, $descricao, $categoria_id, $imagem])) {
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
  <meta charset="UTF-8" />
  <title>Cadastrar Produto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
  <h2>Cadastrar Produto</h2>

  <?php if ($mensagem): ?>
    <div class="alert alert-info"><?= $mensagem ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm bg-white">
    <div class="mb-3">
      <label class="form-label">Nome do Produto</label>
      <input type="text" name="nome" class="form-control" required />
    </div>

    <div class="mb-3">
      <label class="form-label">Preço (ex: 49.90)</label>
      <input type="text" name="preco" class="form-control" required />
    </div>

    <div class="mb-3">
      <label class="form-label">Descrição</label>
      <textarea name="descricao" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Categoria</label>
      <select name="categoria_id" class="form-select" required>
        <option value="">Selecione uma categoria</option>
        <?php foreach ($categorias as $cat): ?>
          <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Imagem</label>
      <input type="file" name="imagem" class="form-control" />
    </div>

    <button type="submit" class="btn btn-primary">Cadastrar</button>
    <a href="index.php" class="btn btn-secondary">Voltar</a>
  </form>
</div>

</body>
</html>
