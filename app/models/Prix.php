<?php

namespace app\models;

use flight\database\PdoWrapper;

class Prix
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les prix avec le type de besoin
     */
    public function getAll(): array
    {
        $sql = "SELECT p.id, p.name, p.prix_unitaire, p.unite, t.name AS type_name
                FROM prix p
                LEFT JOIN typeBesoin t ON p.id_typeBesoin = t.id
                ORDER BY p.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère un prix par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM prix WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Insère un nouveau prix
     */
    public function insert(string $name, int $id_typeBesoin, float $prix_unitaire, string $unite): bool
    {
        $sql = "INSERT INTO prix (name, id_typeBesoin, prix_unitaire, unite) 
                VALUES (:name, :id_typeBesoin, :prix_unitaire, :unite)";
        $this->db->runQuery($sql, [
            ':name' => $name,
            ':id_typeBesoin' => $id_typeBesoin,
            ':prix_unitaire' => $prix_unitaire,
            ':unite' => $unite,
        ]);
        return true;
    }

    /**
     * Met à jour un prix
     */
    public function update(int $id, string $name, int $id_typeBesoin, float $prix_unitaire, string $unite): bool
    {
        $sql = "UPDATE prix SET name = :name, id_typeBesoin = :id_typeBesoin, 
                prix_unitaire = :prix_unitaire, unite = :unite WHERE id = :id";
        $this->db->runQuery($sql, [
            ':id' => $id,
            ':name' => $name,
            ':id_typeBesoin' => $id_typeBesoin,
            ':prix_unitaire' => $prix_unitaire,
            ':unite' => $unite,
        ]);
        return true;
    }

    /**
     * Supprime un prix
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM prix WHERE id = :id";
        $this->db->runQuery($sql, [':id' => $id]);
        return true;
    }

    /**
     * Récupère les types nature et materiel uniquement
     */
    public function getTypesNatureMateriel(): array
    {
        $sql = "SELECT id, name FROM typeBesoin WHERE name IN ('nature', 'materiel') ORDER BY name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }
}
