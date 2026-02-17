<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Distribution;
use app\models\Don;
use app\models\Stock;
use app\models\Prix;
use app\models\Achat;
use app\models\TableauDeBord;
use app\models\Recap;
use flight\Engine;

class ApiExampleController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	// ===================== BESOIN =====================

	/**
	 * Affiche la liste des besoins
	 */
	public function listBesoins(): void
	{
		$model = new Besoin($this->app->db());
		$besoins = $model->getAll();
		$this->app->render('besoin/list', ['besoins' => $besoins]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un besoin
	 */
	public function formBesoin(): void
	{
		$model = new Besoin($this->app->db());
		$villes = $model->getAllVilles();
		$regions = $model->getAllRegions();
		$types = $model->getAllTypes();
		$noms = $model->getDistinctNames();
		$this->app->render('besoin/form', [
			'villes' => $villes,
			'regions' => $regions,
			'types' => $types,
			'noms' => $noms,
		]);
	}

	/**
	 * Enregistre un nouveau besoin
	 */
	public function saveBesoin(): void
	{
		$data = $this->app->request()->data;
		$model = new Besoin($this->app->db());

		// Récupérer la région depuis la ville
		$id_region = $this->getRegionFromVille((int) $data->id_ville);

		// Validation type/unité
		$error = $this->validateUniteForType((int) $data->id_typeBesoin, $data->unite);
		if ($error !== null) {
			$villes = $model->getAllVilles();
			$regions = $model->getAllRegions();
			$types = $model->getAllTypes();
			$noms = $model->getDistinctNames();
			$this->app->render('besoin/form', [
				'villes' => $villes,
				'regions' => $regions,
				'types' => $types,
				'noms' => $noms,
				'error' => $error,
				'old' => [
					'id_typeBesoin' => $data->id_typeBesoin,
					'id_ville' => $data->id_ville,
					'id_region' => $id_region,
					'name' => $data->name,
					'quantite' => $data->quantite,
					'unite' => $data->unite,
				],
			]);
			return;
		}

		$model->insert(
			(int) $data->id_typeBesoin,
			(int) $data->id_ville,
			$id_region,
			$data->name,
			(float) $data->quantite,
			$data->unite
		);
		$this->app->redirect('/besoin/list');
	}

	/**
	 * Affiche le formulaire d'édition d'un besoin
	 */
	public function editBesoin(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Besoin($this->app->db());
		$besoin = $model->getById($id);
		$villes = $model->getAllVilles();
		$regions = $model->getAllRegions();
		$types = $model->getAllTypes();
		$noms = $model->getDistinctNames();
		$this->app->render('besoin/edit', [
			'besoin' => $besoin,
			'villes' => $villes,
			'regions' => $regions,
			'types' => $types,
			'noms' => $noms,
		]);
	}

	/**
	 * Met à jour un besoin
	 */
	public function updateBesoin(): void
	{
		$data = $this->app->request()->data;
		$model = new Besoin($this->app->db());

		// Récupérer la région depuis la ville
		$id_region = $this->getRegionFromVille((int) $data->id_ville);

		// Validation type/unité
		$error = $this->validateUniteForType((int) $data->id_typeBesoin, $data->unite);
		if ($error !== null) {
			$besoin = $model->getById((int) $data->id);
			$villes = $model->getAllVilles();
			$regions = $model->getAllRegions();
			$types = $model->getAllTypes();
			$noms = $model->getDistinctNames();
			$besoin['id_typeBesoin'] = $data->id_typeBesoin;
			$besoin['id_ville'] = $data->id_ville;
			$besoin['name'] = $data->name;
			$besoin['quantite'] = $data->quantite;
			$besoin['unite'] = $data->unite;
			$this->app->render('besoin/edit', [
				'besoin' => $besoin,
				'villes' => $villes,
				'regions' => $regions,
				'types' => $types,
				'noms' => $noms,
				'error' => $error,
			]);
			return;
		}

		$model->update(
			(int) $data->id,
			(int) $data->id_typeBesoin,
			(int) $data->id_ville,
			$id_region,
			$data->name,
			(float) $data->quantite,
			$data->unite
		);
		$this->app->redirect('/besoin/list');
	}

	/**
	 * Supprime un besoin
	 */
	public function deleteBesoin(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Besoin($this->app->db());
		$model->delete($id);
		$this->app->redirect('/besoin/list');
	}

	/**
	 * Valide la correspondance type besoin / unité
	 */
	private function validateUniteForType(int $typeId, ?string $unite): ?string
	{
		$allowed = [
			'argent'   => ['Ariary'],
			'materiel' => ['pièce', 'unité', 'kg', 'm²'],
			'nature'   => ['kg', 'litre', 'sac', 'unité'],
		];

		// Récupérer le nom du type
		$db = $this->app->db();
		$stmt = $db->runQuery('SELECT name FROM typeBesoin WHERE id = ?', [$typeId]);
		$type = $stmt->fetch();

		if (!$type) {
			return 'Type de besoin introuvable.';
		}

		$typeName = $type['name'];
		if (!isset($allowed[$typeName])) {
			return 'Type de besoin inconnu.';
		}

		if (!in_array($unite, $allowed[$typeName], true)) {
			$unitesValides = implode(', ', $allowed[$typeName]);
			return "L'unité \"$unite\" n'est pas valide pour le type \"$typeName\". Unités autorisées : $unitesValides.";
		}

		return null;
	}

	/**
	 * Récupère le region_id à partir de l'id de la ville
	 */
	private function getRegionFromVille(int $villeId): int
	{
		$db = $this->app->db();
		$stmt = $db->runQuery('SELECT region_id FROM ville WHERE id = ?', [$villeId]);
		$ville = $stmt->fetch();
		return $ville ? (int) $ville['region_id'] : 0;
	}

	// ===================== DON =====================

	/**
	 * Affiche la liste des dons
	 */
	public function listDons(): void
	{
		$model = new Don($this->app->db());
		$dons = $model->getAll();
		$this->app->render('don/list', ['dons' => $dons]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un don
	 */
	public function formDon(): void
	{
		$model = new Don($this->app->db());
		$types = $model->getAllTypes();
		$this->app->render('don/form', ['types' => $types]);
	}

	/**
	 * Enregistre un nouveau don
	 */
	public function saveDon(): void
	{
		$data = $this->app->request()->data;
		$model = new Don($this->app->db());
		$model->insert(
			(int) $data->id_typeBesoin,
			$data->name,
			(float) $data->quantite,
			$data->unite,
			$data->date
		);

		// Ajouter au stock
		$stockModel = new Stock($this->app->db());
		$stockModel->addToStock((int) $data->id_typeBesoin, $data->name, (float) $data->quantite, $data->unite);

		$this->app->redirect('/don/list');
	}

	/**
	 * Affiche le formulaire d'édition d'un don
	 */
	public function editDon(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Don($this->app->db());
		$don = $model->getById($id);
		$types = $model->getAllTypes();
		$this->app->render('don/edit', [
			'don' => $don,
			'types' => $types,
		]);
	}

	/**
	 * Met à jour un don
	 */
	public function updateDon(): void
	{
		$data = $this->app->request()->data;
		$model = new Don($this->app->db());
		$model->update(
			(int) $data->id,
			(int) $data->id_typeBesoin,
			$data->name,
			(float) $data->quantite,
			$data->unite,
			$data->date
		);
		$this->app->redirect('/don/list');
	}

	/**
	 * Supprime un don
	 */
	public function deleteDon(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Don($this->app->db());
		$model->delete($id);
		$this->app->redirect('/don/list');
	}

	// ===================== DISTRIBUTION =====================
	public function listDistributions(): void
	{
		$model = new Distribution($this->app->db());
		$distributions = $model->getAll();
		$this->app->render('distribution/list', ['distributions' => $distributions]);
	}

	/**
	 * Affiche le formulaire d'ajout d'une distribution
	 */
	public function formDistribution(): void
	{
		$model = new Distribution($this->app->db());
		$besoins = $model->getAllBesoins();
		$villes = $model->getAllVilles();
		$types = $model->getAllTypes();
		$this->app->render('distribution/form', [
			'besoins' => $besoins,
			'villes' => $villes,
			'types' => $types,
		]);
	}

	/**
	 * Enregistre une nouvelle distribution
	 * Avec ou sans besoin associé
	 */
	public function saveDistribution(): void
	{
		$data = $this->app->request()->data;
		$id_besoin_raw = $data->id_besoin;
		$date = $data->date;
		$quantite = (float) $data->quantite;

		$model = new Distribution($this->app->db());
		$stockModel = new Stock($this->app->db());

		if ($id_besoin_raw !== '' && $id_besoin_raw !== null) {
			// Distribution liée à un besoin
			$id_besoin = (int) $id_besoin_raw;
			$besoinModel = new Besoin($this->app->db());
			$besoin = $besoinModel->getById($id_besoin);

			if (!$besoin) {
				$this->renderDistributionFormWithError('Besoin introuvable.');
				return;
			}

			$name = $besoin['name'];
			$id_ville = (int) $besoin['id_ville'];
			$id_typeBesoin = (int) $besoin['id_typeBesoin'];
			$unite = $besoin['unite'];

			// Vérifier le stock
			if (!$stockModel->removeFromStock($name, $quantite)) {
				$this->renderDistributionFormWithError(
					'Stock insuffisant pour "' . htmlspecialchars($name) . '". Vérifiez le stock disponible.'
				);
				return;
			}

			$model->insertWithBesoin($id_besoin, $id_ville, $id_typeBesoin, $name, $unite, $date, $quantite);
		} else {
			// Distribution libre (sans besoin)
			$id_ville = (int) $data->id_ville;
			$id_typeBesoin = (int) $data->id_typeBesoin;
			$name = trim($data->name);
			$unite = $data->unite;

			if ($id_ville === 0 || $id_typeBesoin === 0 || $name === '' || $unite === '' || $unite === null) {
				$this->renderDistributionFormWithError(
					'Sans besoin, vous devez remplir : ville, type, nom du produit et unité.'
				);
				return;
			}

			// Vérifier le stock
			if (!$stockModel->removeFromStock($name, $quantite)) {
				$this->renderDistributionFormWithError(
					'Stock insuffisant pour "' . htmlspecialchars($name) . '". Vérifiez le stock disponible.'
				);
				return;
			}

			$model->insertWithoutBesoin($id_ville, $id_typeBesoin, $name, $unite, $date, $quantite);
		}

		$this->app->redirect('/distribution/list');
	}

	/**
	 * Affiche le formulaire d'édition d'une distribution
	 */
	public function editDistribution(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Distribution($this->app->db());
		$distribution = $model->getById($id);
		$besoins = $model->getAllBesoins();
		$villes = $model->getAllVilles();
		$types = $model->getAllTypes();
		$this->app->render('distribution/edit', [
			'distribution' => $distribution,
			'besoins' => $besoins,
			'villes' => $villes,
			'types' => $types,
		]);
	}

	/**
	 * Met à jour une distribution
	 */
	public function updateDistribution(): void
	{
		$data = $this->app->request()->data;
		$id = (int) $data->id;
		$id_besoin_raw = $data->id_besoin;
		$date = $data->date;
		$quantite = (float) $data->quantite;

		if ($id_besoin_raw !== '' && $id_besoin_raw !== null) {
			$id_besoin = (int) $id_besoin_raw;
			$besoinModel = new Besoin($this->app->db());
			$besoin = $besoinModel->getById($id_besoin);
			$id_ville = (int) $besoin['id_ville'];
			$id_typeBesoin = (int) $besoin['id_typeBesoin'];
			$name = $besoin['name'];
			$unite = $besoin['unite'];
		} else {
			$id_besoin = null;
			$id_ville = (int) $data->id_ville;
			$id_typeBesoin = (int) $data->id_typeBesoin;
			$name = trim($data->name);
			$unite = $data->unite;
		}

		$model = new Distribution($this->app->db());
		$model->update($id, $id_besoin, $id_ville, $id_typeBesoin, $name, $unite, $date, $quantite);
		$this->app->redirect('/distribution/list');
	}

	/**
	 * Supprime une distribution
	 */
	public function deleteDistribution(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Distribution($this->app->db());
		$model->delete($id);
		$this->app->redirect('/distribution/list');
	}

	/**
	 * Helper: ré-affiche le formulaire distribution avec une erreur
	 */
	private function renderDistributionFormWithError(string $error): void
	{
		$model = new Distribution($this->app->db());
		$besoins = $model->getAllBesoins();
		$villes = $model->getAllVilles();
		$types = $model->getAllTypes();
		$this->app->render('distribution/form', [
			'besoins' => $besoins,
			'villes' => $villes,
			'types' => $types,
			'error' => $error,
		]);
	}

	// ===================== STOCK =====================

	/**
	 * Affiche le stock actuel
	 */
	public function listStock(): void
	{
		$model = new Stock($this->app->db());
		$stocks = $model->getAll();
		$this->app->render('stock/list', ['stocks' => $stocks]);
	}

	// ===================== PRIX =====================

	/**
	 * Affiche la liste des prix
	 */
	public function listPrix(): void
	{
		$model = new Prix($this->app->db());
		$prix_list = $model->getAll();
		$this->app->render('prix/list', ['prix_list' => $prix_list]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un prix
	 */
	public function formPrix(): void
	{
		$model = new Prix($this->app->db());
		$types = $model->getTypesNatureMateriel();
		$this->app->render('prix/form', ['types' => $types]);
	}

	/**
	 * Enregistre un nouveau prix
	 */
	public function savePrix(): void
	{
		$data = $this->app->request()->data;
		$model = new Prix($this->app->db());
		$model->insert(
			$data->name,
			(int) $data->id_typeBesoin,
			(float) $data->prix_unitaire,
			$data->unite
		);
		$this->app->redirect('/prix/list');
	}

	/**
	 * Affiche le formulaire d'édition d'un prix
	 */
	public function editPrix(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Prix($this->app->db());
		$prix = $model->getById($id);
		$types = $model->getTypesNatureMateriel();
		$this->app->render('prix/edit', [
			'prix' => $prix,
			'types' => $types,
		]);
	}

	/**
	 * Met à jour un prix
	 */
	public function updatePrix(): void
	{
		$data = $this->app->request()->data;
		$model = new Prix($this->app->db());
		$model->update(
			(int) $data->id,
			$data->name,
			(int) $data->id_typeBesoin,
			(float) $data->prix_unitaire,
			$data->unite
		);
		$this->app->redirect('/prix/list');
	}

	/**
	 * Supprime un prix
	 */
	public function deletePrix(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Prix($this->app->db());
		$model->delete($id);
		$this->app->redirect('/prix/list');
	}

	// ===================== ACHAT =====================

	/**
	 * Affiche la liste des achats (filtrable par ville)
	 */
	public function listAchats(): void
	{
		$model = new Achat($this->app->db());
		
		// Récupérer le filtre ville depuis les paramètres GET
		$id_ville = $this->app->request()->query->id_ville;
		$id_ville = ($id_ville !== null && $id_ville !== '') ? (int) $id_ville : null;
		
		// Récupérer les achats (tous ou filtrés par ville)
		$achats = $model->getByVille($id_ville);
		
		// Récupérer la liste des villes pour le menu déroulant
		$villes = $model->getAllVilles();

		$this->app->render('achat/list', [
			'achats' => $achats,
			'villes' => $villes,
			'selected_ville' => $id_ville,
		]);
	}

	/**
	 * Affiche le formulaire d'ajout d'un achat
	 */
	public function formAchat(): void
	{
		$model = new Achat($this->app->db());
		$prix_list = $model->getAllPrix();
		$villes = $model->getAllVilles();

		$this->app->render('achat/form', [
			'prix_list' => $prix_list,
			'villes' => $villes,
		]);
	}

	/**
	 * Enregistre un nouvel achat
	 * Débite le stock argent, crédite le stock produit
	 */
	public function saveAchat(): void
	{
		$data = $this->app->request()->data;
		$id_prix = (int) $data->id_prix;
		$id_ville = $data->id_ville !== '' ? (int) $data->id_ville : null;
		$quantite = (float) $data->quantite;
		$date = $data->date;

		// Récupérer le prix unitaire
		$prixModel = new Prix($this->app->db());
		$prix = $prixModel->getById($id_prix);

		if (!$prix) {
			$this->renderAchatFormWithError('Prix introuvable.');
			return;
		}

		$montant = $quantite * (float) $prix['prix_unitaire'];

		// Vérifier le stock d'argent
		$stockModel = new Stock($this->app->db());
		$stockArgent = $stockModel->getStockByType('argent');

		if ($stockArgent < $montant) {
			$this->renderAchatFormWithError(
				'Stock d\'argent insuffisant. Disponible : ' . number_format($stockArgent, 2, ',', ' ') . 
				' Ar. Nécessaire : ' . number_format($montant, 2, ',', ' ') . ' Ar.'
			);
			return;
		}

		// Débiter le stock argent
		$stockModel->removeFromStockByType('argent', $montant);

		// Créditer le stock du produit acheté
		$stockModel->addToStock(
			(int) $prix['id_typeBesoin'],
			$prix['name'],
			$quantite,
			$prix['unite']
		);

		// Enregistrer l'achat
		$achatModel = new Achat($this->app->db());
		$achatModel->insert($id_prix, $id_ville, $quantite, $montant, $date);

		$this->app->redirect('/achat/list');
	}

	/**
	 * Supprime un achat
	 */
	public function deleteAchat(): void
	{
		$id = (int) $this->app->request()->query->id;
		$model = new Achat($this->app->db());
		$model->delete($id);
		$this->app->redirect('/achat/list');
	}

	/**
	 * Helper: ré-affiche le formulaire achat avec une erreur
	 */
	private function renderAchatFormWithError(string $error): void
	{
		$model = new Achat($this->app->db());
		$prix_list = $model->getAllPrix();
		$villes = $model->getAllVilles();

		$this->app->render('achat/form', [
			'prix_list' => $prix_list,
			'villes' => $villes,
			'error' => $error,
		]);
	}

	// ===================== TABLEAU DE BORD =====================

	/**
	 * Affiche le tableau de bord avec les statistiques par ville
	 */
	public function tableauDeBord(): void
	{
		$model = new TableauDeBord($this->app->db());
		$stats = $model->getAllStats();
		$this->app->render('tableau_de_bord', $stats);
	}

	// ===================== RECAP =====================

	/**
	 * Affiche le récapitulatif global
	 */
	public function recapGlobal(): void
	{
		$model = new Recap($this->app->db());
		$stats = $model->getAllStats();
		$stats['csp_nonce'] = $this->app->get('csp_nonce');
		$this->app->render('recap', $stats);
	}

	/**
	 * API endpoint pour récupérer les données du récapitulatif en JSON
	 */
	public function recapGlobalApi(): void
	{
		$model = new Recap($this->app->db());
		$this->app->json($model->getAllStats());
	}
}