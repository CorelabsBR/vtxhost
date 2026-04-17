CREATE DATABASE vtxhost;
USE vtxhost;

-- USUÁRIO
CREATE TABLE user (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf_cnpj VARCHAR(20) NULL UNIQUE,
    celular VARCHAR(20) NULL,
    cep VARCHAR(20) NULL,
    rua VARCHAR(255) NULL,
    numero VARCHAR(20) NULL,
    complemento VARCHAR(255) NULL,
    bairro VARCHAR(255) NULL,
    cidade VARCHAR(255) NULL,
    estado VARCHAR(255) NULL,
    pais VARCHAR(255) NULL,
    data_nasc DATE NULL,
    tipo_usuario ENUM('cliente', 'root') NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- PRODUTOS
CREATE TABLE categoria_prod (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE jogos_prod (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE location_prod (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    localidade VARCHAR(255) NOT NULL,
    ativo BOOLEAN NOT NULL DEFAULT TRUE
);

CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,      -- campo único, removido o duplicado "price"
    categoria_id INT NOT NULL,
    jogo_id INT NOT NULL,
    local_id INT NOT NULL,
    ram VARCHAR(255) NOT NULL,
    cpu VARCHAR(255) NOT NULL,
    storage VARCHAR(255) NOT NULL,
    ddos_protection BOOLEAN NOT NULL DEFAULT FALSE,
    featured BOOLEAN NOT NULL DEFAULT FALSE,
    sort_order INT NOT NULL DEFAULT 1,  -- era "sort_orders" (sem sentido no plural)
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (categoria_id) REFERENCES categoria_prod(id),
    FOREIGN KEY (jogo_id) REFERENCES jogos_prod(id),
    FOREIGN KEY (local_id) REFERENCES location_prod(id)
);

-- PEDIDOS
CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pendente', 'pago', 'cancelado') NOT NULL DEFAULT 'pendente',
    mp_pagamento_id VARCHAR(255) NULL,  -- typo corrigido
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE order_items (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantidade INT NOT NULL,
    preco DECIMAL(10, 2) NOT NULL,  -- preço no momento da compra (snapshot)

    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- CARRINHO (simplificado em uma tabela só)
CREATE TABLE cart_items (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantidade INT NOT NULL,
    adicionado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY uq_user_product (user_id, product_id),  -- evita duplicatas
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);