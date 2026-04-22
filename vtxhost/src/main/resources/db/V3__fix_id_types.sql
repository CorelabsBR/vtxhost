-- Migration V3: Corrigir tipos de colunas ID de INT para BIGINT

-- Alterar tabelas que têm referências externas primeiro

-- 1. Alterar order_items (tem FK para orders)
ALTER TABLE order_items MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE order_items MODIFY order_id BIGINT;
ALTER TABLE order_items MODIFY product_id BIGINT;

-- 2. Alterar cart_items (tem FK para user e product)
ALTER TABLE cart_items MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE cart_items MODIFY user_id BIGINT;
ALTER TABLE cart_items MODIFY product_id BIGINT;

-- 3. Alterar orders (tem FK para user)
ALTER TABLE orders MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE orders MODIFY user_id BIGINT;

-- 4. Alterar products (tem FKs para categoria, jogo, local)
ALTER TABLE products MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE products MODIFY categoria_id BIGINT;
ALTER TABLE products MODIFY jogo_id BIGINT;
ALTER TABLE products MODIFY local_id BIGINT;

-- 5. Alterar as demais tabelas principais
ALTER TABLE user MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE categoria_prod MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE jogos_prod MODIFY id BIGINT AUTO_INCREMENT;
ALTER TABLE location_prod MODIFY id BIGINT AUTO_INCREMENT;
