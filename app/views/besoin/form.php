<h1>Nouveau Besoin</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php $old = $old ?? []; ?>

<form action="/besoin/save" method="POST">
    <p>
        <label for="id_typeBesoin">Type de besoin :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>"
                    <?= (($old['id_typeBesoin'] ?? '') == $t['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="id_ville">Ville :</label><br>
        <select name="id_ville" id="id_ville" required>
            <option value="">-- Choisir une ville --</option>
            <?php foreach ($villes as $v): ?>
                <option value="<?= htmlspecialchars($v['id']) ?>" data-region="<?= htmlspecialchars($v['region_id']) ?>"
                    <?= (($old['id_ville'] ?? '') == $v['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['region_name'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du besoin :</label><br>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" value="<?= htmlspecialchars($old['quantite'] ?? '') ?>" required>
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite" required>
            <option value="">-- Choisir une unité --</option>
            <?php
            $unites = ['Ariary', 'kg', 'litre', 'sac', 'pièce', 'unité', 'm²'];
            foreach ($unites as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>"
                    <?= (($old['unite'] ?? '') === $u) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <button type="submit">Enregistrer</button>
        <a href="/besoin/list">Annuler</a>
    </p>
</form>
