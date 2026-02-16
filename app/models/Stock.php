<?php

namespace app\models;

use flight\database\PdoWrapper;

class Stock
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tout le stock avec le type de besoin
     */
    public function getAll(): array
    {
        $sql = "SELECT s.id, s.name, s.quantite, t.name AS type_name
                FROM stock s
                LEFT JOIN typeBesoin t ON s.id_typeBesoin = t.id
                ORDER BY s.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère un stock par nom et type
     */
    public function getByNameAndType(string $name, int $id_typeBesoin): array|false
    {
        $sql = "SELECT * FROM stock WHERE name = :name AND id_typeBesoin = :id_typeBesoin";
        $statement = $this->db->runQuery($sql, [
            ':name' => $name,
            ':id_typeBesoin' => $id_typeBesoin,
        ]);
        return $statement->fetch();
    }

    /**
     * Récupère un stock par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM stock WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Ajoute au stock (quand un don est reçu)
     * Si le produit existe déjà dans le stock, on augmente la quantité
     * Sinon on crée une nouvelle entrée
     */
    public function addToStock(int $id_typeBesoin, string $name, float $quantite): bool
    {
        $existing = $this->getByNameAndType($name, $id_typeBesoin);
        if ($existing !== false) {
            $sql = "UPDATE stock SET quantite = quantite + :quantite WHERE id = :id";
            $this->db->runQuery($sql, [
                ':quantite' => $quantite,
                ':id' => $existing['id'],
            ]);
        } else {
            $sql = "INSERT INTO stock (id_typeBesoin, name, quantite) VALUES (:id_typeBesoin, :name, :quantite)";
            $this->db->runQuery($sql, [
                ':id_typeBesoin' => $id_typeBesoin,
                ':name' => $name,
                ':quantite' => $quantite,
            ]);
        }
        return true;
    }

    /**
     * Retire du stock (quand une distribution est faite)
     * Retourne false si stock insuffisant
     */
    public function removeFromStock(string $name, float $quantite): bool
    {
        // Chercher le stock par nom
        $sql = "SELECT * FROM stock WHERE name = :name";
        $statement = $this->db->runQuery($sql, [':name' => $name]);
        $stock = $statement->fetch();

        if ($stock === false || (float) $stock['quantite'] < $quantite) {
            return false; // Stock insuffisant
        }

        $sql = "UPDATE stock SET quantite = quantite - :quantite WHERE id = :id";
        $this->db->runQuery($sql, [
            ':quantite' => $quantite,
            ':id' => $stock['id'],
        ]);
        return true;
    }

    /**
     * Vérifie le stock disponible pour un besoin donné
     */
    public function getStockByBesoinName(string $name): float
    {
        $sql = "SELECT COALESCE(SUM(quantite), 0) AS total FROM stock WHERE name = :name";
        $statement = $this->db->runQuery($sql, [':name' => $name]);
        $result = $statement->fetch();
        return (float) ($result['total'] ?? 0);
    }
}
