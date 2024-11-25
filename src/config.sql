/*Isso aqui vc copia e cola no sql do phpmyadmin*/
CREATE DATABASE IF NOT EXISTS teste_db;

USE teste_db;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    failed_attempts INT DEFAULT 0,   -- Conta as tentativas falhas
    lock_time DATETIME DEFAULT NULL   -- Hora em que o usuário será desbloqueado
);

-- Inserindo os usuários com senhas diretamente (sem criptografia)
INSERT INTO usuarios (username, password) VALUES
('admin', '12345'),
('user', 'senha123');

