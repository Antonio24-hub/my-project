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
<h1>Modifier Prix</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($prix)): ?>
<form action="/prix/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($prix['id']) ?>">
    <p>
        <label for="name">Nom du produit :</label><br>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($prix['name']) ?>" required>
    </p>
    <p>
        <label for="id_typeBesoin">Type :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>"
                    <?= ($t['id'] == $prix['id_typeBesoin']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="prix_unitaire">Prix unitaire (Ariary) :</label><br>
        <input type="number" name="prix_unitaire" id="prix_unitaire" step="0.01" value="<?= htmlspecialchars($prix['prix_unitaire']) ?>" required>
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite" required>
            <option value="">-- Choisir une unité --</option>
            <?php
            $unites = ['kg', 'litre', 'sac', 'pièce', 'unité', 'm²'];
            foreach ($unites as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>" <?= (($prix['unite'] ?? '') === $u) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <button type="submit">Mettre à jour</button>
        <a href="/prix/list">Annuler</a>
    </p>
</form>
<?php else: ?>
    <p>Prix introuvable.</p>
    <a href="/prix/list">Retour à la liste</a>
<?php endif; ?>

</body>
</html>