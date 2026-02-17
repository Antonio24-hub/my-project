<?php

namespace app\models;

use flight\database\PdoWrapper;

class Achat
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les achats avec prix, besoin et ville
     * Filtre optionnel par ville
     */
    public function getAll(): array
    {
        $sql = "SELECT a.id, a.quantite, a.montant, a.date,
                       p.name AS produit_name, p.prix_unitaire, p.unite,
                       t.name AS type_name, v.name AS ville_name
                FROM achat a
                LEFT JOIN prix p ON a.id_prix = p.id
                LEFT JOIN typeBesoin t ON p.id_typeBesoin = t.id
                LEFT JOIN ville v ON a.id_ville = v.id
                ORDER BY a.date DESC";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
    }

    /**
     * Récupère un achat par son id
     */
    public function getById(int $id): array|false
    {
        $sql = "SELECT * FROM achat WHERE id = :id";
        $statement = $this->db->runQuery($sql, [':id' => $id]);
        return $statement->fetch();
    }

    /**
     * Insère un nouvel achat
     */
    public function insert(int $id_prix, ?int $id_ville, float $quantite, float $montant, string $date): bool
    {
        $sql = "INSERT INTO achat (id_prix, id_ville, quantite, montant, date) 
                VALUES (:id_prix, :id_ville, :quantite, :montant, :date)";
        $this->db->runQuery($sql, [
            ':id_prix' => $id_prix,
            ':id_ville' => $id_ville,
            ':quantite' => $quantite,
            ':montant' => $montant,
            ':date' => $date,
        ]);
        return true;
    }

    /**
     * Supprime un achat
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM achat WHERE id = :id";
        $this->db->runQuery($sql, [':id' => $id]);
        return true;
    }

    /**
     * Récupère les prix disponibles
     */
    public function getAllPrix(): array
    {
        $sql = "SELECT p.id, p.name, p.prix_unitaire, p.unite, t.name AS type_name
                FROM prix p
                LEFT JOIN typeBesoin t ON p.id_typeBesoin = t.id
                ORDER BY p.name";
        $statement = $this->db->runQuery($sql);
        return $statement->fetchAll();
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
     * Récupère les achats filtrés par ville (si id_ville fourni)
     */
    public function getByVille(?int $id_ville): array
    {
        if ($id_ville === null) {
            return $this->getAll();
        }

        $sql = "SELECT a.id, a.quantite, a.montant, a.date,
                       p.name AS produit_name, p.prix_unitaire, p.unite,
                       t.name AS type_name, v.name AS ville_name
                FROM achat a
                LEFT JOIN prix p ON a.id_prix = p.id
                LEFT JOIN typeBesoin t ON p.id_typeBesoin = t.id
                LEFT JOIN ville v ON a.id_ville = v.id
                WHERE a.id_ville = :id_ville
                ORDER BY a.date DESC";
        $statement = $this->db->runQuery($sql, [':id_ville' => $id_ville]);
        return $statement->fetchAll();
    }

}
