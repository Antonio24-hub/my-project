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
<h1>Nouvelle Distribution</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>

<form action="/distribution/save" method="POST">

    <!-- Choix : avec ou sans besoin -->
    <p>
        <label for="id_besoin">Besoin (optionnel) :</label><br>
        <select name="id_besoin" id="id_besoin">
            <option value="">-- Sans besoin (distribution libre) --</option>
            <?php foreach ($besoins as $b): ?>
                <option value="<?= htmlspecialchars($b['id']) ?>">
                    <?= htmlspecialchars($b['name']) ?> — <?= htmlspecialchars($b['ville_name'] ?? '') ?> (<?= htmlspecialchars($b['type_name'] ?? '') ?>, qté: <?= htmlspecialchars($b['quantite']) ?> <?= htmlspecialchars($b['unite'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <hr>
    <p><em>Si aucun besoin n'est sélectionné, remplissez les champs ci-dessous :</em></p>

    <p>
        <label for="id_ville">Ville :</label><br>
        <select name="id_ville" id="id_ville">
            <option value="">-- Choisir une ville --</option>
            <?php foreach ($villes as $v): ?>
                <option value="<?= htmlspecialchars($v['id']) ?>">
                    <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['region_name'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="id_typeBesoin">Type :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin">
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>">
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du produit :</label><br>
        <input type="text" name="name" id="name">
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite">
            <option value="">-- Choisir une unité --</option>
            <?php
            $unites = ['Ariary', 'kg', 'litre', 'sac', 'pièce', 'unité', 'm²'];
            foreach ($unites as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>"><?= htmlspecialchars($u) ?></option>
            <?php endforeach; ?>
        </select>
    </p>

    <hr>

    <p>
        <label for="date">Date :</label><br>
        <input type="date" name="date" id="date" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" required>
    </p>
    <p>
        <button type="submit">Enregistrer</button>
        <a href="/distribution/list">Annuler</a>
    </p>
</form>

</body>
</html>