CREATE TABLE pterodactyl_nodes (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fqdn varchar(255) NOT NULL,
    locationid int NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (locationid) REFERENCES pterodactyl_location(id)

);
CREATE TABLE pterodactyl_eggs (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    egg_id int(20) NOT NULL,
    name varchar(255) NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE pterodactyl_location (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(3) NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE plans (
    plan INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL, 
    jogo_id INT NOT NULL,
    ram INT NOT NULL,
    disk INT NOT NULL,
    egg INT NULL,
    cpu INT NULL, -- NULL = ilimitado
    location_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (jogo_id) REFERENCES jogos_prod(id),
    FOREIGN KEY (location_id) REFERENCES location_prod(id),
    FOREIGN KEY (egg) REFERENCES pterodactyl_eggs(id)
);


INSERT INTO location_prod (localidade) VALUES ('Brasil');
INSERT INTO jogos_prod (nome) VALUES ('Minecraft Java');
INSERT INTO jogos_prod (nome) VALUES ('Minecraft Bedrock');
INSERT INTO JOGOS_PROD (nome) VALUES ('Terraria');
INSERT INTO JOGOS_PROD (nome) VALUES ('GTA V');
INSERT INTO JOGOS_PROD (nome) VALUES ('GTA SA');
INSERT INTO JOGOS_PROD (nome) VALUES ('BeamNG Drive');
INSERT INTO JOGOS_PROD(nome) VALUES('RDR2');
INSERT INTO JOGOS_PROD(nome) VALUES('Hogwarts Legacy');
INSERT INTO JOGOS_PROD(nome) VALUES('ARMA 3');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (5,   'Minecraft Java');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (78, 'Minecraft Bedrock');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (60, 'Terraria');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (63, 'FiveM');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (66, 'MTA');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (79, 'BeamMP');
INSERT into pterodactyl_eggs (egg_id, name) VALUES (71, 'RedM');
INSERT INTO pterodactyl_eggs (egg_id, name) VALUES (70, 'Hogwarp');
INSERT INTO pterodactyl_eggs (egg_id, name) VALUES (69, 'ARMA3');

INSERT INTO pterodactyl_location (name) VALUES ('vtx-sa-1');
INSERT INTO pterodactyl_nodes (fqdn, locationid) VALUES ('node.ion.vortex.corelabs.dev.br', 1);

-- JAVA
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Carvão', 1, 2000, 40000, NULL, 1, 1),
('Tier Ferro', 1, 4000, 80000, NULL, 1, 1),
('Tier Ouro', 1, 8000, 160000, NULL, 1, 1),
('Tier Diamante', 1, 12000, 320000, NULL, 1, 1),
('Tier Netherite', 1, 16000, 640000, NULL, 1, 1);

-- BEDROCK
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Carvão Bedrock', 2, 2000, 40000, NULL, 1, 2),
('Tier Ferro Bedrock', 2, 4000, 80000, NULL, 1, 2),
('Tier Ouro Bedrock', 2, 8000, 160000, NULL, 1, 2),
('Tier Diamante Bedrock', 2, 12000, 320000, NULL, 1, 2),
('Tier Netherite Bedrock', 2, 16000, 640000, NULL, 1, 2);

-- TERRARIA
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Rei Slime', 3, 2000, 20000, NULL, 1, 3),
('Tier Olho de Cthulhu', 3, 4000, 40000, NULL, 1, 3),
('Tier Devorador de Mundos', 3, 8000, 60000, NULL, 1, 3),
('Tier Mechdusa', 3, 12000, 80000, NULL, 1, 3),
('Tier EverScream', 3, 16000, 100000, NULL, 1, 3);

-- GTA V
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Vagos', 4, 4000, 100000, NULL, 1, 4),
('Tier Ballas', 4, 8000, 150000, NULL, 1, 4),
('Tier Marabunta', 4, 12000, 200000, NULL, 1, 4),
('Tier Aztecas', 4, 16000, 0, NULL, 1, 4),
('Tier Merriweather', 4, 0, 0, NULL, 1, 4);

-- GTA SA
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Grove Street', 5, 2000, 10000, 2, 1, 5),
('Tier Ballas', 5, 4000, 40000, 4, 1, 5),
('Tier Los Santos Vagos', 5, 6000, 60000, 6, 1, 5),
('Tier Vagos', 5, 8000, 60000, NULL, 1, 5),
('Tier Aztecas', 5, 12000, 80000, NULL, 1, 5),
('Tier San Fierro Rifa', 5, 16000, 100000, NULL, 1, 5);

-- BEAMNG DRIVE
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Pigeon', 6, 2000, 40000, NULL, 1, 6),
('Tier Wigeon', 6, 4000, 60000, NULL, 1, 6),
('Tier Piccolina', 6, 6000, 0, NULL, 1, 6),
('Tier Bastion', 6, 8000, 0, NULL, 1, 6),
('Tier Bolide', 6, 0, 0, NULL, 1, 6);

--RDR2
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Van der Linde', 7, 4000, 40000, NULL, 1, 7),
('Tier O’Driscoll', 7, 8000, 80000, NULL, 1, 7),
('Tier Murfree Brood', 7, 12000, 120000, NULL, 1, 7),
('Tier Lemoyne Raiders', 7, 16000, 160000, NULL, 1, 7),
('Tier Del Lobo', 7, 0, 0, NULL, 1, 7);

--HOQWARTS LEGACY
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier Sonserina', 8, 4000, 40000, NULL, 1, 8),
('Tier Corvinal', 8, 8000, 80000, NULL, 1, 8),
('Tier Lufa-Lufa', 8, 12000, 120000, NULL, 1, 8),
('Tier Grifinoria', 8, 16000, 0, NULL, 1, 8),
('Tier Pukwudgie', 8, 0, 0, NULL, 1, 8);

--ARMA 3
INSERT INTO plans (name, jogo_id, ram, disk, cpu, location_id, egg) VALUES
('Tier CSAT', 9, 4000, 40000, NULL, 1, 9),
('Tier NATO', 9, 8000, 80000, NULL, 1, 9),
('Tier Independent', 9, 12000, 120000, NULL, 1, 9),
('Tier Civilian', 9, 16000, 0, NULL, 1, 9),
('Tier Guerilla', 9, 0, 0, NULL, 1, 9);
