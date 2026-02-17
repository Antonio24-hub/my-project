<?php

namespace app\models;

use flight\database\PdoWrapper;

class Recap
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère le nombre total de besoins
     */
    public function getTotalBesoins(): int
    {
        $result = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM besoin")->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Récupère le nombre total de distributions (besoins satisfaits)
     */
    public function getTotalDistributions(): int
    {
        $result = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM distribution")->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Récupère le nombre total de dons
     */
    public function getTotalDons(): int
    {
        $result = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM don")->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Récupère le nombre d'éléments en stock (quantité > 0)
     */
    public function getDonsEnStock(): int
    {
        $result = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM stock WHERE quantite > 0")->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Récupère le nombre total d'achats
     */
    public function getTotalAchats(): int
    {
        $result = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM achat")->fetch();
        return (int)($result['cnt'] ?? 0);
    }

    /**
     * Récupère le montant total des achats
     */
    public function getMontantAchats(): float
    {
        $result = $this->db->runQuery("SELECT SUM(montant) AS total FROM achat")->fetch();
        return (float)($result['total'] ?? 0);
    }

    /**
     * Récupère les détails par type de besoin
     * Utilise id_typeBesoin directement sur distribution (pas seulement via besoin)
     */
    public function getDetailsParType(): array
    {
        $sql = "
            SELECT 
                t.id AS type_id,
                t.name AS type_name,
                COALESCE(besoins.qte, 0) AS qte_besoins,
                COALESCE(dons.qte, 0) AS qte_dons,
                COALESCE(distrib.qte, 0) AS qte_distribues
            FROM typeBesoin t
            LEFT JOIN (
                SELECT id_typeBesoin, SUM(quantite) AS qte FROM besoin GROUP BY id_typeBesoin
            ) besoins ON besoins.id_typeBesoin = t.id
            LEFT JOIN (
                SELECT id_typeBesoin, SUM(quantite) AS qte FROM don GROUP BY id_typeBesoin
            ) dons ON dons.id_typeBesoin = t.id
            LEFT JOIN (
                SELECT id_typeBesoin, SUM(quantite) AS qte 
                FROM distribution 
                GROUP BY id_typeBesoin
            ) distrib ON distrib.id_typeBesoin = t.id
            ORDER BY t.name
        ";
        return $this->db->runQuery($sql)->fetchAll();
    }

    /**
     * Récupère toutes les statistiques du récapitulatif
     */
    public function getAllStats(): array
    {
        $totalBesoins = $this->getTotalBesoins();
        $totalDistributions = $this->getTotalDistributions();
        $besoinsRestants = $totalBesoins - $totalDistributions;
        $totalDons = $this->getTotalDons();
        $donsDispatches = $totalDistributions;
        $donsEnStock = $this->getDonsEnStock();
        $totalAchats = $this->getTotalAchats();
        $montantAchats = $this->getMontantAchats();
        $detailsParType = $this->getDetailsParType();

        return [
            'totaux' => [
                'total_besoins' => $totalBesoins,
                'besoins_satisfaits' => $totalDistributions,
                'besoins_restants' => $besoinsRestants,
                'total_dons' => $totalDons,
                'dons_dispatches' => $donsDispatches,
                'dons_en_stock' => $donsEnStock,
                'total_achats' => $totalAchats,
                'montant_achats' => $montantAchats,
            ],
            'details_par_type' => $detailsParType,
        ];
    }
}
