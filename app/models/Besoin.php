<?php

namespace app\models;

use flight\database\PdoWrapper;

class Besoin
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les besoins avec ville, région et type
     */
    public function getAll(): array
    {
        $sql = "SELECT b.id, b.name, b.quantite, b.unite,
                       v.name AS ville_name, r.name AS region_name, t.name AS type_name
                FROM besoin b
                LEFT JOIN ville v ON b.id_ville = v.id
                LEFT JOIN region r ON b.id_region = r.id
                LEFT JOIN typeBesoin t ON b.id_typeBesoin = t.id
                ORDER BY b.id DESC";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère un besoin par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM besoin WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Insère un nouveau besoin
     */
    public function insert(int $id_typeBesoin, int $id_ville, int $id_region, string $name, float $quantite, string $unite): bool
    {
        $sql = "INSERT INTO besoin (id_typeBesoin, id_ville, id_region, name, quantite, unite) 
                VALUES (:id_typeBesoin, :id_ville, :id_region, :name, :quantite, :unite)";
        $this->db->runQuery($sql, [
            ':id_typeBesoin' => $id_typeBesoin,
            ':id_ville' => $id_ville,
            ':id_region' => $id_region,
            ':name' => $name,
            ':quantite' => $quantite,
            ':unite' => $unite,
        ]);
        return true;
    }

    /**
     * Met à jour un besoin
     */
    public function update(int $id, int $id_typeBesoin, int $id_ville, int $id_region, string $name, float $quantite, string $unite): bool
    {
        $sql = "UPDATE besoin SET id_typeBesoin = :id_typeBesoin, id_ville = :id_ville, 
                id_region = :id_region, name = :name, quantite = :quantite, unite = :unite WHERE id = :id";
        $this->db->runQuery($sql, [
            ':id' => $id,
            ':id_typeBesoin' => $id_typeBesoin,
            ':id_ville' => $id_ville,
            ':id_region' => $id_region,
            ':name' => $name,
            ':quantite' => $quantite,
            ':unite' => $unite,
        ]);
        return true;
    }

    /**
     * Supprime un besoin
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM besoin WHERE id = :id";
        $this->db->runQuery($sql, [':id' => $id]);
        return true;
    }

    /**
     * Récupère toutes les villes
     */
    public function getAllVilles(): array
    {
        $sql = "SELECT v.id, v.name, v.region_id, r.name AS region_name 
                FROM ville v LEFT JOIN region r ON v.region_id = r.id ORDER BY v.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère toutes les régions
     */
    public function getAllRegions(): array
    {
        $sql = "SELECT id, name FROM region ORDER BY name";
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

    /**
     * Récupère les noms distincts de besoin groupés par type
     */
    public function getDistinctNames(): array
    {
        $sql = "SELECT DISTINCT b.name, t.name AS type_name
                FROM besoin b
                LEFT JOIN typeBesoin t ON b.id_typeBesoin = t.id
                ORDER BY t.name, b.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }
}
