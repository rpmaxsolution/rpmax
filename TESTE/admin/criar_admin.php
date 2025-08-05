<?php
require '../conexao.php';

$email = 'admin@loja.com';
$novaSenha = 'admin123';
$hash = password_hash($novaSenha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("UPDATE admins SET senha = ? WHERE email = ?");
if ($stmt->execute([$hash, $email])) {
    echo "Senha atualizada com sucesso para o admin: $email";
} else {
    echo "Erro ao atualizar senha.";
}
