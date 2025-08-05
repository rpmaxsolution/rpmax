<?php
session_start();
require '../conexao.php';

// Verifica se o admin está logado (você pode adaptar com sua lógica)
if (!isset($_SESSION['admin_id'])) {
    die('Acesso negado.');
}

// Processar o cadastro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = floatval($_POST['preco']);
    $categoria_id = intval($_POST['categoria_id']);
    $imagem_nome = null;

    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $imagem_nome = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], "../imagens/" . $imagem_nome);
    }

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $descricao, $preco, $imagem_nome, $categoria_id]);

    echo "Produto cadastrado com sucesso!";
}

// Carregar categorias
$categorias = $pdo->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Cadastro de Produto</h2>
<form method="post" enctype="multipart/form-data">
    Nome: <input type="text" name="nome" required><br>
    Descrição: <textarea name="descricao" required></textarea><br>
    Preço: <input type="number" step="0.01" name="preco" required><br>
    Categoria:
    <select name="categoria_id" required>
        <option value="">Selecione</option>
        <?php foreach ($categorias as $categoria): ?>
            <option value="<?= $categoria['id'] ?>"><?= $categoria['nome'] ?></option>
        <?php endforeach; ?>
    </select><br>
    Imagem: <input type="file" name="imagem"><br><br>
    <button type="submit">Cadastrar Produto</button>
</form>
