CREATE DATABASE bngrc;
USE bngrc;

CREATE TABLE region (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE ville (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    region_id INT,
    FOREIGN KEY (region_id) REFERENCES region(id)
);

CREATE TABLE typeBesoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE besoin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_typeBesoin INT,
    id_ville INT,
    id_region INT,
    name VARCHAR(255),
    quantite DECIMAL(10, 2),
    unite VARCHAR(50),
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id),
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_region) REFERENCES region(id)
);

CREATE TABLE distribution (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_besoin INT NULL,
    date DATE,
    quantite DECIMAL(10, 2),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id)
); 

CREATE TABLE don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_typeBesoin INT,
    name VARCHAR(255),
    quantite DECIMAL(10, 2),
    date DATE,
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
);

CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_typeBesoin INT,
    name VARCHAR(255),
    quantite DECIMAL(10, 2),
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
);



-- Regions
INSERT INTO region (name) VALUES ('Analamanga');
INSERT INTO region (name) VALUES ('Vakinankaratra');
INSERT INTO region (name) VALUES ('Atsinanana');

-- Villes
INSERT INTO ville (name, region_id) VALUES ('Antananarivo', 1);
INSERT INTO ville (name, region_id) VALUES ('Antsirabe', 2);
INSERT INTO ville (name, region_id) VALUES ('Toamasina', 3);

-- Types de besoin
INSERT INTO typeBesoin (name) VALUES ('nature');
INSERT INTO typeBesoin (name) VALUES ('materiel');
INSERT INTO typeBesoin (name) VALUES ('argent');

-- Besoins
INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) VALUES (1, 1, 1, 'Riz', 500.00, 'kg');
INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) VALUES (2, 2, 2, 'Tôles', 150.00, 'm²');
INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) VALUES (3, 3, 3, 'Aide financière', 5000000.00, 'Ariary');

-- Distributions
INSERT INTO distribution (id_besoin, date, quantite) VALUES (1, '2026-02-10', 200.00);
INSERT INTO distribution (id_besoin, date, quantite) VALUES (2, '2026-02-12', 80.00);
INSERT INTO distribution (id_besoin, date, quantite) VALUES (3, '2026-02-15', 2000000.00);

-- Dons
INSERT INTO don (id_typeBesoin, name, quantite, date) VALUES (1, 'Riz', 300.00, '2026-02-08');
INSERT INTO don (id_typeBesoin, name, quantite, date) VALUES (2, 'Tôles', 100.00, '2026-02-09');
INSERT INTO don (id_typeBesoin, name, quantite, date) VALUES (3, 'Aide financière', 3000000.00, '2026-02-11');

-- Stock (initialisé à partir des dons moins les distributions)
INSERT INTO stock (id_typeBesoin, name, quantite) VALUES (1, 'Riz', 100.00);
INSERT INTO stock (id_typeBesoin, name, quantite) VALUES (2, 'Tôles', 20.00);
INSERT INTO stock (id_typeBesoin, name, quantite) VALUES (3, 'Aide financière', 1000000.00);
