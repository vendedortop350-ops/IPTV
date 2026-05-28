-- Atualização do banco de dados para Elit System IPTV
USE elit_iptv;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS vendas VARCHAR(100);
-- Fim da atualização