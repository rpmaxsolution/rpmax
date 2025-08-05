<?php
session_start();
require '../conexao.php';

if (!isset($_SESSION['admin_id'])) {
    die('Acesso negado.');
}

$id = intval($_GET['id'] ?? 0);

if ($id) {
    // Opcional: apagar arquivo da imagem
    $stmt = $pdo->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
    $imagem = $stmt->fetchColumn();

    if ($imagem && file_exists("../imagens/$imagem")) {
        unlink("../imagens/$imagem");
    }

    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
