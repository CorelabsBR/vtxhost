-- Migration V2: Adicionar suporte a Plan em produtos

ALTER TABLE products ADD COLUMN plan VARCHAR(3) NULL;

-- Criar índice para buscar por plan
CREATE INDEX idx_products_plan ON products(plan);
CREATE INDEX idx_pterodactyl_plan ON pterodactyl_plans(plan)
