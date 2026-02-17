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
<h1>Modifier Distribution</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>

<?php if (!empty($distribution)): ?>
<form action="/distribution/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($distribution['id']) ?>">

    <!-- Choix : avec ou sans besoin -->
    <p>
        <label for="id_besoin">Besoin (optionnel) :</label><br>
        <select name="id_besoin" id="id_besoin">
            <option value="">-- Sans besoin (distribution libre) --</option>
            <?php foreach ($besoins as $b): ?>
                <option value="<?= htmlspecialchars($b['id']) ?>"
                    <?= ($b['id'] == $distribution['id_besoin']) ? 'selected' : '' ?>>
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
                <option value="<?= htmlspecialchars($v['id']) ?>"
                    <?= ($v['id'] == ($distribution['id_ville'] ?? '')) ? 'selected' : '' ?>>
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
                <option value="<?= htmlspecialchars($t['id']) ?>"
                    <?= ($t['id'] == ($distribution['id_typeBesoin'] ?? '')) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du produit :</label><br>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($distribution['name'] ?? '') ?>">
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite">
            <option value="">-- Choisir une unité --</option>
            <?php
            $unites = ['Ariary', 'kg', 'litre', 'sac', 'pièce', 'unité', 'm²'];
            foreach ($unites as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>"
                    <?= (($distribution['unite'] ?? '') === $u) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>

    <hr>

    <p>
        <label for="date">Date :</label><br>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($distribution['date']) ?>" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" value="<?= htmlspecialchars($distribution['quantite']) ?>" required>
    </p>
    <p>
        <button type="submit">Mettre à jour</button>
        <a href="/distribution/list">Annuler</a>
    </p>
</form>
<?php else: ?>
    <p>Distribution introuvable.</p>
    <a href="/distribution/list">Retour à la liste</a>
<?php endif; ?>

</body>
</html>