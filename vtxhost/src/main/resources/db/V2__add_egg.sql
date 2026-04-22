-- Migration V2: Adicionar suporte a Egg em produtos

ALTER TABLE products ADD COLUMN egg VARCHAR(3) NULL;

-- Criar índice para buscar por egg
CREATE INDEX idx_products_egg ON products(egg);
