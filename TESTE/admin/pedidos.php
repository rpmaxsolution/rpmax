<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    die('Acesso restrito.');
}

echo "<h2>Pedidos Recebidos</h2>";

$stmt = $pdo->query("SELECT p.*, c.nome AS cliente FROM pedidos p JOIN clientes c ON p.cliente_id = c.id ORDER BY p.data DESC");
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($pedidos as $pedido) {
    echo "<h4>Pedido #{$pedido['id']} - Cliente: {$pedido['cliente']} - {$pedido['data']} - Status: {$pedido['status']}</h4>";

    $stmt_itens = $pdo->prepare("SELECT i.*, pr.nome FROM itens_pedido i JOIN produtos pr ON i.produto_id = pr.id WHERE i.pedido_id = ?");
    $stmt_itens->execute([$pedido['id']]);
    $itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

    foreach ($itens as $item) {
        echo "{$item['nome']} - {$item['quantidade']}x R$ " . number_format($item['preco_unitario'], 2, ',', '.') . "<br>";
    }

    echo "<hr>";
}
