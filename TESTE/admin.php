<?php
require '../conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = floatval($_POST['preco']);

    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $descricao, $preco]);

    echo "Produto cadastrado com sucesso!";
}
?>

<form method="post">
    Nome: <input type="text" name="nome"><br>
    Descrição: <textarea name="descricao"></textarea><br>
    Preço: <input type="text" name="preco"><br>
    <button type="submit">Cadastrar</button>
</form>
