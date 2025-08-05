CREATE DATABASE loja_virtual CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE loja_virtual;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    imagem VARCHAR(255)
);
