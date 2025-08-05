<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
echo "<h2>Olá, " . $_SESSION['cliente_nome'] . "!</h2>";
echo "<a href='logout.php'>Sair</a><br><br>";

// Listar pedidos
$stmt = $pdo->prepare("SELECT * FROM pedidos WHERE cliente_id = ? ORDER BY data DESC");
$stmt->execute([$cliente_id]);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pedidos as $pedido) {
    echo "<h4>Pedido #{$pedido['id']} - {$pedido['data']} - {$pedido['status']}</h4>";

    $stmt_itens = $pdo->prepare("SELECT i.*, p.nome FROM itens_pedido i JOIN produtos p ON i.produto_id = p.id WHERE i.pedido_id = ?");
    $stmt_itens->execute([$pedido['id']]);
    $itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itens as $item) {
        echo "{$item['nome']} - {$item['quantidade']}x R$ " . number_format($item['preco_unitario'], 2, ',', '.') . "<br>";
    }

    echo "<hr>";
}
