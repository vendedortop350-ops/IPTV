-- Banco de Dados Elit IPTV
CREATE DATABASE IF NOT EXISTS elit_iptv;
USE elit_iptv;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de clientes
CREATE TABLE IF NOT EXISTS clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    valor_mensalidade DECIMAL(10,2) NOT NULL,
    nome_servico VARCHAR(100) NOT NULL,
    vendas VARCHAR(100) NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de devedores
CREATE TABLE IF NOT EXISTS devedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    data_vencimento DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Atualização do banco de dados para Elit System IPTV
USE elit_iptv;
-- Adiciona a coluna 'vendas' apenas se não existir
SET @col_exists := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'elit_iptv' AND TABLE_NAME = 'clientes' AND COLUMN_NAME = 'vendas');
SET @sql := IF(@col_exists = 0, 'ALTER TABLE clientes ADD COLUMN vendas VARCHAR(100) NULL;', 'SELECT "Coluna vendas já existe.";');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
-- Fim da atualização