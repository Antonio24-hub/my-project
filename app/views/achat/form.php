<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Gestion des catastrophes</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/effects.css">
</head>
<body>
<h1>Nouvel Achat</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <p style="color: green; font-weight: bold;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form action="/achat/save" method="POST">
    <p>
        <label for="id_prix">Produit (prix) :</label><br>
        <select name="id_prix" id="id_prix" required>
            <option value="">-- Choisir un produit --</option>
            <?php foreach ($prix_list as $p): ?>
                <option value="<?= htmlspecialchars($p['id']) ?>">
                    <?= htmlspecialchars($p['name']) ?> — <?= number_format((float)$p['prix_unitaire'], 2, ',', ' ') ?> Ar/<?= htmlspecialchars($p['unite']) ?> (<?= htmlspecialchars($p['type_name'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="id_ville">Ville :</label><br>
        <select name="id_ville" id="id_ville" required>
            <option value="">-- Choisir une ville --</option>
            <?php if (!empty($villes)): ?>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= htmlspecialchars($v['id']) ?>">
                        <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['region_name'] ?? '') ?>)
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </p>
    <p>
        <label for="quantite">Quantité à acheter :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" required>
    </p>
    <p>
        <label for="date">Date :</label><br>
        <input type="date" name="date" id="date" required>
    </p>
    <p>
        <em>Le montant sera calculé automatiquement : quantité × prix unitaire.</em><br>
        <em>Le stock d'argent sera débité et le stock du produit sera crédité.</em>
    </p>
    <p>
        <button type="submit">Enregistrer l'achat</button>
        <a href="/achat/list">Annuler</a>
    </p>
</form>

</body>
</html>