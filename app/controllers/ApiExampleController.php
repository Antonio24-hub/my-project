<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Distribution;
use app\models\Don;
use app\models\Stock;
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
		$this->app->render('besoin/form', [
			'villes' => $villes,
			'regions' => $regions,
			'types' => $types,
		]);
	}

	/**
	 * Enregistre un nouveau besoin
	 */
	public function saveBesoin(): void
	{
		$data = $this->app->request()->data;
		$model = new Besoin($this->app->db());

		// Validation type/unité
		$error = $this->validateUniteForType((int) $data->id_typeBesoin, $data->unite);
		if ($error !== null) {
			$villes = $model->getAllVilles();
			$regions = $model->getAllRegions();
			$types = $model->getAllTypes();
			$this->app->render('besoin/form', [
				'villes' => $villes,
				'regions' => $regions,
				'types' => $types,
				'error' => $error,
				'old' => [
					'id_typeBesoin' => $data->id_typeBesoin,
					'id_ville' => $data->id_ville,
					'id_region' => $data->id_region,
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
			(int) $data->id_region,
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
		$this->app->render('besoin/edit', [
			'besoin' => $besoin,
			'villes' => $villes,
			'regions' => $regions,
			'types' => $types,
		]);
	}

	/**
	 * Met à jour un besoin
	 */
	public function updateBesoin(): void
	{
		$data = $this->app->request()->data;
		$model = new Besoin($this->app->db());

		// Validation type/unité
		$error = $this->validateUniteForType((int) $data->id_typeBesoin, $data->unite);
		if ($error !== null) {
			$besoin = $model->getById((int) $data->id);
			$villes = $model->getAllVilles();
			$regions = $model->getAllRegions();
			$types = $model->getAllTypes();
			// Mettre à jour besoin avec les données soumises pour garder les valeurs
			$besoin['id_typeBesoin'] = $data->id_typeBesoin;
			$besoin['id_ville'] = $data->id_ville;
			$besoin['id_region'] = $data->id_region;
			$besoin['name'] = $data->name;
			$besoin['quantite'] = $data->quantite;
			$besoin['unite'] = $data->unite;
			$this->app->render('besoin/edit', [
				'besoin' => $besoin,
				'villes' => $villes,
				'regions' => $regions,
				'types' => $types,
				'error' => $error,
			]);
			return;
		}

		$model->update(
			(int) $data->id,
			(int) $data->id_typeBesoin,
			(int) $data->id_ville,
			(int) $data->id_region,
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
			$data->date
		);

		// Ajouter au stock
		$stockModel = new Stock($this->app->db());
		$stockModel->addToStock((int) $data->id_typeBesoin, $data->name, (float) $data->quantite);

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
		$this->app->render('distribution/form', ['besoins' => $besoins]);
	}

	/**
	 * Enregistre une nouvelle distribution
	 */
	public function saveDistribution(): void
	{
		$id_besoin = (int) $this->app->request()->data->id_besoin;
		$date = $this->app->request()->data->date;
		$quantite = (float) $this->app->request()->data->quantite;

		// Vérifier le stock disponible
		$besoinModel = new Besoin($this->app->db());
		$besoin = $besoinModel->getById($id_besoin);
		$stockModel = new Stock($this->app->db());
		$besoinName = $besoin ? $besoin['name'] : '';

		if (!$stockModel->removeFromStock($besoinName, $quantite)) {
			$model = new Distribution($this->app->db());
			$besoins = $model->getAllBesoins();
			$this->app->render('distribution/form', [
				'besoins' => $besoins,
				'error' => 'Stock insuffisant pour "' . htmlspecialchars($besoinName) . '". Vérifiez le stock disponible.',
			]);
			return;
		}

		$model = new Distribution($this->app->db());
		$model->insert($id_besoin, $date, $quantite);
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
		$this->app->render('distribution/edit', [
			'distribution' => $distribution,
			'besoins' => $besoins,
		]);
	}

	/**
	 * Met à jour une distribution
	 */
	public function updateDistribution(): void
	{
		$id = (int) $this->app->request()->data->id;
		$id_besoin = (int) $this->app->request()->data->id_besoin;
		$date = $this->app->request()->data->date;
		$quantite = (float) $this->app->request()->data->quantite;

		$model = new Distribution($this->app->db());
		$model->update($id, $id_besoin, $date, $quantite);
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
}