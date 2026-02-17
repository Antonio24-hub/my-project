<?php

namespace app\models;

use flight\database\PdoWrapper;

class Don
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les dons avec le type de besoin
     */
    public function getAll(): array
    {
        $sql = "SELECT d.id, d.name, d.quantite, d.unite, d.date, t.name AS type_name
                FROM don d
                LEFT JOIN typeBesoin t ON d.id_typeBesoin = t.id
                ORDER BY d.date DESC";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère un don par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM don WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Insère un nouveau don
     */
    public function insert(int $id_typeBesoin, string $name, float $quantite, string $unite, string $date): bool
    {
        $sql = "INSERT INTO don (id_typeBesoin, name, quantite, unite, date) 
                VALUES (:id_typeBesoin, :name, :quantite, :unite, :date)";
        $this->db->runQuery($sql, [
            ':id_typeBesoin' => $id_typeBesoin,
            ':name' => $name,
            ':quantite' => $quantite,
            ':unite' => $unite,
            ':date' => $date,
        ]);
        return true;
    }

    /**
     * Met à jour un don
     */
    public function update(int $id, int $id_typeBesoin, string $name, float $quantite, string $unite, string $date): bool
    {
        $sql = "UPDATE don SET id_typeBesoin = :id_typeBesoin, name = :name, quantite = :quantite, 
                unite = :unite, date = :date WHERE id = :id";
        $this->db->runQuery($sql, [
            ':id' => $id,
            ':id_typeBesoin' => $id_typeBesoin,
            ':name' => $name,
            ':quantite' => $quantite,
            ':unite' => $unite,
            ':date' => $date,
        ]);
        return true;
    }

    /**
     * Supprime un don
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM don WHERE id = :id";
        $this->db->runQuery($sql, [':id' => $id]);
        return true;
    }

    /**
     * Récupère tous les types de besoin (pour le select)
     */
    public function getAllTypes(): array
    {
        $sql = "SELECT id, name FROM typeBesoin ORDER BY name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }
}
