<?php

namespace app\models;

use flight\database\PdoWrapper;

class Distribution
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère toutes les distributions avec le nom du besoin associé
     */
    public function getAll(): array
    {
        $sql = "SELECT d.id, d.date, d.quantite, b.name AS besoin_name
                FROM distribution d
                LEFT JOIN besoin b ON d.id_besoin = b.id
                ORDER BY d.date DESC";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère une distribution par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM distribution WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Insère une nouvelle distribution
     */
    public function insert(int $id_besoin, string $date, float $quantite): bool
    {
        $sql = "INSERT INTO distribution (id_besoin, date, quantite) VALUES (:id_besoin, :date, :quantite)";
        $this->db->runQuery($sql, [
            ':id_besoin' => $id_besoin,
            ':date' => $date,
            ':quantite' => $quantite,
        ]);
        return true;
    }

    /**
     * Met à jour une distribution
     */
    public function update(int $id, int $id_besoin, string $date, float $quantite): bool
    {
        $sql = "UPDATE distribution SET id_besoin = :id_besoin, date = :date, quantite = :quantite WHERE id = :id";
        $this->db->runQuery($sql, [
            ':id' => $id,
            ':id_besoin' => $id_besoin,
            ':date' => $date,
            ':quantite' => $quantite,
        ]);
        return true;
    }

    /**
     * Supprime une distribution
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM distribution WHERE id = :id";
        $this->db->runQuery($sql, [':id' => $id]);
        return true;
    }

    /**
     * Récupère tous les besoins (pour le select du formulaire)
     */
    public function getAllBesoins(): array
    {
        $sql = "SELECT b.id, b.name, b.quantite, b.unite
                FROM besoin b
                WHERE b.id NOT IN (SELECT DISTINCT id_besoin FROM distribution WHERE id_besoin IS NOT NULL)
                ORDER BY b.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }
}
