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
    id_ville INT NULL,
    id_typeBesoin INT NULL,
    name VARCHAR(255),
    unite VARCHAR(50),
    date DATE,
    quantite DECIMAL(10, 2),
    FOREIGN KEY (id_besoin) REFERENCES besoin(id),
    FOREIGN KEY (id_ville) REFERENCES ville(id),
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
); 

CREATE TABLE don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_typeBesoin INT,
    name VARCHAR(255),
    quantite DECIMAL(10, 2),
    unite VARCHAR(50),
    date DATE,
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
);

CREATE TABLE stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_typeBesoin INT,
    name VARCHAR(255),
    quantite DECIMAL(10, 2),
    unite VARCHAR(50),
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
);

CREATE TABLE prix (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    id_typeBesoin INT,
    prix_unitaire DECIMAL(10, 2),
    unite VARCHAR(50),
    FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id)
);

CREATE TABLE achat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_prix INT,
    quantite DECIMAL(10, 2),
    montant DECIMAL(15, 2),
    date DATE,
    FOREIGN KEY (id_prix) REFERENCES prix(id)
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
INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) VALUES (2, 2, 2, 'Tôles', 150.00, 'm/carré');
INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) VALUES (3, 3, 3, 'Aide financière', 5000000.00, 'Ariary');

-- Distributions (avec besoin)
INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) VALUES (1, 1, 1, 'Riz', 'kg', '2026-02-10', 200.00);
INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) VALUES (2, 2, 2, 'Tôles', 'm²', '2026-02-12', 80.00);
INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) VALUES (3, 3, 3, 'Aide financière', 'Ariary', '2026-02-15', 2000000.00);
-- Distribution (sans besoin)
INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) VALUES (NULL, 1, 1, 'Riz', 'kg', '2026-02-16', 50.00);

-- Dons
INSERT INTO don (id_typeBesoin, name, quantite, unite, date) VALUES (1, 'Riz', 300.00, 'kg', '2026-02-08');
INSERT INTO don (id_typeBesoin, name, quantite, unite, date) VALUES (2, 'Tôles', 100.00, 'm²', '2026-02-09');
INSERT INTO don (id_typeBesoin, name, quantite, unite, date) VALUES (3, 'Aide financière', 3000000.00, 'Ariary', '2026-02-11');

-- Stock (initialisé à partir des dons moins les distributions)
INSERT INTO stock (id_typeBesoin, name, quantite, unite) VALUES (1, 'Riz', 100.00, 'kg');
INSERT INTO stock (id_typeBesoin, name, quantite, unite) VALUES (2, 'Tôles', 20.00, 'm²');
INSERT INTO stock (id_typeBesoin, name, quantite, unite) VALUES (3, 'Aide financière', 1000000.00, 'Ariary');

-- Prix unitaires (nature et materiel uniquement)
INSERT INTO prix (name, id_typeBesoin, prix_unitaire, unite) VALUES ('Riz', 1, 2500.00, 'kg');
INSERT INTO prix (name, id_typeBesoin, prix_unitaire, unite) VALUES ('Tôles', 2, 35000.00, 'm²');
INSERT INTO prix (name, id_typeBesoin, prix_unitaire, unite) VALUES ('Bâche', 2, 15000.00, 'pièce');

-- Achats
INSERT INTO achat (id_prix, quantite, montant, date) VALUES (1, 50.00, 125000.00, '2026-02-13');
INSERT INTO achat (id_prix, quantite, montant, date) VALUES (2, 10.00, 350000.00, '2026-02-14');

-- Migration: ajout colonnes sur distribution (si table existante)
ALTER TABLE distribution ADD COLUMN id_ville INT NULL AFTER id_besoin;
ALTER TABLE distribution ADD COLUMN id_typeBesoin INT NULL AFTER id_ville;
ALTER TABLE distribution ADD COLUMN name VARCHAR(255) AFTER id_typeBesoin;
ALTER TABLE distribution ADD COLUMN unite VARCHAR(50) AFTER name;
ALTER TABLE distribution ADD FOREIGN KEY (id_ville) REFERENCES ville(id);
ALTER TABLE distribution ADD FOREIGN KEY (id_typeBesoin) REFERENCES typeBesoin(id);

-- Migration: suppression id_besoin sur achat (si table existante)
ALTER TABLE achat DROP FOREIGN KEY achat_ibfk_2;
ALTER TABLE achat DROP COLUMN id_besoin;

-- Migration: ajout id_ville sur achat
ALTER TABLE achat ADD COLUMN id_ville INT NULL AFTER id_prix;
ALTER TABLE achat ADD FOREIGN KEY (id_ville) REFERENCES ville(id);
ALTER TABLE achat ADD FOREIGN KEY (id_ville) REFERENCES ville(id);

