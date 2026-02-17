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
     * Récupère toutes les distributions avec infos complètes
     */
    public function getAll(): array
    {
        $sql = "SELECT d.id, d.date, d.quantite, d.name, d.unite, d.id_besoin,
                       v.name AS ville_name, t.name AS type_name,
                       b.name AS besoin_name
                FROM distribution d
                LEFT JOIN besoin b ON d.id_besoin = b.id
                LEFT JOIN ville v ON d.id_ville = v.id
                LEFT JOIN typeBesoin t ON d.id_typeBesoin = t.id
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
     * Insère une distribution liée à un besoin
     */
    public function insertWithBesoin(int $id_besoin, int $id_ville, int $id_typeBesoin, string $name, string $unite, string $date, float $quantite): bool
    {
        $sql = "INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) 
                VALUES (:id_besoin, :id_ville, :id_typeBesoin, :name, :unite, :date, :quantite)";
        $this->db->runQuery($sql, [
            ':id_besoin' => $id_besoin,
            ':id_ville' => $id_ville,
            ':id_typeBesoin' => $id_typeBesoin,
            ':name' => $name,
            ':unite' => $unite,
            ':date' => $date,
            ':quantite' => $quantite,
        ]);
        return true;
    }

    /**
     * Insère une distribution sans besoin (distribution libre)
     */
    public function insertWithoutBesoin(int $id_ville, int $id_typeBesoin, string $name, string $unite, string $date, float $quantite): bool
    {
        $sql = "INSERT INTO distribution (id_besoin, id_ville, id_typeBesoin, name, unite, date, quantite) 
                VALUES (NULL, :id_ville, :id_typeBesoin, :name, :unite, :date, :quantite)";
        $this->db->runQuery($sql, [
            ':id_ville' => $id_ville,
            ':id_typeBesoin' => $id_typeBesoin,
            ':name' => $name,
            ':unite' => $unite,
            ':date' => $date,
            ':quantite' => $quantite,
        ]);
        return true;
    }

    /**
     * Met à jour une distribution
     */
    public function update(int $id, ?int $id_besoin, int $id_ville, int $id_typeBesoin, string $name, string $unite, string $date, float $quantite): bool
    {
        $sql = "UPDATE distribution SET id_besoin = :id_besoin, id_ville = :id_ville, id_typeBesoin = :id_typeBesoin, 
                name = :name, unite = :unite, date = :date, quantite = :quantite WHERE id = :id";
        $this->db->runQuery($sql, [
            ':id' => $id,
            ':id_besoin' => $id_besoin,
            ':id_ville' => $id_ville,
            ':id_typeBesoin' => $id_typeBesoin,
            ':name' => $name,
            ':unite' => $unite,
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
     * Récupère les besoins non encore satisfaits (pour le select)
     */
    public function getAllBesoins(): array
    {
        $sql = "SELECT b.id, b.name, b.quantite, b.unite, b.id_ville, b.id_typeBesoin,
                       v.name AS ville_name, t.name AS type_name
                FROM besoin b
                LEFT JOIN ville v ON b.id_ville = v.id
                LEFT JOIN typeBesoin t ON b.id_typeBesoin = t.id
                WHERE b.id NOT IN (SELECT DISTINCT id_besoin FROM distribution WHERE id_besoin IS NOT NULL)
                ORDER BY b.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère toutes les villes
     */
    public function getAllVilles(): array
    {
        $sql = "SELECT v.id, v.name, r.name AS region_name 
                FROM ville v 
                LEFT JOIN region r ON v.region_id = r.id 
                ORDER BY v.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère tous les types de besoin
     */
    public function getAllTypes(): array
    {
        $sql = "SELECT id, name FROM typeBesoin ORDER BY name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }
}
