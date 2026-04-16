INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'cPanel Start BR', 'cpanel', 'brasil', '2 vCPU', '2 GB', '20 GB NVMe', 'Ilimitado', 'L3/L4', 29.90, 'Painel oficial com SSL grátis', 1, 1
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'cPanel Start BR');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Host Turbo BR', 'host', 'brasil', '4 vCPU', '4 GB', '50 GB NVMe', 'Ilimitado', 'L3/L4/L7', 39.90, 'Hospedagem veloz para projetos nacionais', 1, 2
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Host Turbo BR');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Host Turbo CA', 'host', 'canada', '4 vCPU', '4 GB', '50 GB NVMe', 'Ilimitado', 'L3/L4/L7', 44.90, 'Entrega internacional com baixa latência', 1, 3
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Host Turbo CA');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'VPS Core BR', 'vps', 'brasil', '6 vCPU', '8 GB', '120 GB NVMe', '10 TB', 'L3/L4/L7', 89.90, 'Root total para workloads no Brasil', 1, 4
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'VPS Core BR');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'VPS Core CA', 'vps', 'canada', '6 vCPU', '8 GB', '120 GB NVMe', '10 TB', 'L3/L4/L7', 94.90, 'Performance estável na América do Norte', 1, 5
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'VPS Core CA');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Plano SMP', 'host', 'brasil', '2 vCPU Ryzen', '3 GB DDR4', '40 GB NVMe', 'Ilimitado', 'L3/L4', 24.49, 'Recomendado para até 4 jogadores com estabilidade diária', 0, 20
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Plano SMP');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Plano SMP+', 'host', 'brasil', '3 vCPU Ryzen', '6 GB DDR4', '60 GB NVMe', 'Ilimitado', 'L3/L4', 37.74, 'Perfeito para até 8 jogadores com folga para mods', 0, 21
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Plano SMP+');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Plano Pro', 'host', 'brasil', '4 vCPU Ryzen', '10 GB DDR4', '90 GB NVMe', 'Ilimitado', 'L3/L4/L7', 53.32, 'Ideal para até 16 jogadores com processamento avancado', 1, 22
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Plano Pro');

INSERT INTO products (name, category, location, cpu, ram, storage, bandwidth, ddos_protection, price_monthly, highlight, featured, sort_order)
SELECT 'Plano Premium', 'host', 'brasil', '6 vCPU Ryzen', '16 GB DDR4', '140 GB NVMe', 'Ilimitado', 'L3/L4/L7', 83.91, 'Pensado para grandes comunidades com muitos jogadores', 0, 23
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Plano Premium');