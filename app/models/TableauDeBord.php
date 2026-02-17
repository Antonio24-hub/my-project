<?php

namespace app\models;

use flight\database\PdoWrapper;

class TableauDeBord
{
    protected PdoWrapper $db;

    public function __construct(PdoWrapper $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère les statistiques par ville (besoins, distributions)
     */
    public function getStatistiquesParVille(): array
    {
        $sql = "
            SELECT 
                v.id,
                v.name AS ville_name,
                r.name AS region_name,
                COUNT(DISTINCT b.id) AS total_besoins,
                COUNT(DISTINCT d.id) AS total_distributions,
                (COUNT(DISTINCT b.id) - COUNT(DISTINCT d.id)) AS reste
            FROM ville v
            LEFT JOIN region r ON v.region_id = r.id
            LEFT JOIN besoin b ON b.id_ville = v.id
            LEFT JOIN distribution d ON d.id_ville = v.id
            GROUP BY v.id, v.name, r.name
            ORDER BY v.name
        ";
        return $this->db->runQuery($sql)->fetchAll();
    }

    /**
     * Récupère les statistiques des dons par type
     */
    public function getDonsParType(): array
    {
        $sql = "
            SELECT 
                t.name AS type_name,
                SUM(d.quantite) AS total_quantite,
                d.unite,
                COUNT(d.id) AS nb_dons
            FROM don d
            LEFT JOIN typeBesoin t ON d.id_typeBesoin = t.id
            GROUP BY t.name, d.unite
            ORDER BY t.name
        ";
        return $this->db->runQuery($sql)->fetchAll();
    }

    /**
     * Récupère les statistiques des achats par type
     */
    public function getAchatsParType(): array
    {
        $sql = "
            SELECT 
                t.name AS type_name,
                SUM(a.montant) AS total_montant,
                COUNT(a.id) AS nb_achats
            FROM achat a
            LEFT JOIN prix p ON a.id_prix = p.id
            LEFT JOIN typeBesoin t ON p.id_typeBesoin = t.id
            GROUP BY t.name
            ORDER BY t.name
        ";
        return $this->db->runQuery($sql)->fetchAll();
    }

    /**
     * Récupère les totaux généraux
     */
    public function getTotaux(): array
    {
        $totalBesoins = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM besoin")->fetch()['cnt'] ?? 0;
        $totalDistributions = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM distribution")->fetch()['cnt'] ?? 0;
        $totalDons = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM don")->fetch()['cnt'] ?? 0;
        $totalAchats = $this->db->runQuery("SELECT COUNT(*) AS cnt FROM achat")->fetch()['cnt'] ?? 0;
        $montantAchats = $this->db->runQuery("SELECT SUM(montant) AS total FROM achat")->fetch()['total'] ?? 0;

        return [
            'total_besoins' => (int)$totalBesoins,
            'total_distributions' => (int)$totalDistributions,
            'total_dons' => (int)$totalDons,
            'total_achats' => (int)$totalAchats,
            'montant_achats' => (float)$montantAchats,
        ];
    }

    /**
     * Récupère toutes les statistiques du tableau de bord
     */
    public function getAllStats(): array
    {
        return [
            'villes_stats' => $this->getStatistiquesParVille(),
            'dons_stats' => $this->getDonsParType(),
            'achats_stats' => $this->getAchatsParType(),
            'totaux' => $this->getTotaux(),
        ];
    }
}
