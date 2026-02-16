<h1>Modifier Besoin</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($besoin)): ?>
<form action="/besoin/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($besoin['id']) ?>">
    <p>
        <label for="id_typeBesoin">Type de besoin :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>"
                    data-name="<?= htmlspecialchars($t['name']) ?>"
                    <?= ($t['id'] == $besoin['id_typeBesoin']) ? 'selected' : '' ?>>
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
                <option value="<?= htmlspecialchars($v['id']) ?>"
                    data-region="<?= htmlspecialchars($v['region_id']) ?>"
                    <?= ($v['id'] == $besoin['id_ville']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['region_name'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du besoin :</label><br>
        <select name="name" id="name" required>
            <option value="">-- Choisir un nom --</option>
            <?php
            $grouped = [];
            foreach (($noms ?? []) as $n) {
                $grouped[$n['type_name'] ?? 'Autre'][] = $n['name'];
            }
            foreach ($grouped as $type => $names): ?>
                <optgroup label="<?= htmlspecialchars($type) ?>">
                    <?php foreach ($names as $name): ?>
                        <option value="<?= htmlspecialchars($name) ?>"
                            <?= (($besoin['name'] ?? '') === $name) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($name) ?>
                        </option>
                    <?php endforeach; ?>
                </optgroup>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" value="<?= htmlspecialchars($besoin['quantite']) ?>" required>
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite" required>
            <option value="">-- Choisir une unité --</option>
            <?php
            $unites = ['Ariary', 'kg', 'litre', 'sac', 'pièce', 'unité', 'm²'];
            foreach ($unites as $u): ?>
                <option value="<?= htmlspecialchars($u) ?>" <?= (($besoin['unite'] ?? '') === $u) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <button type="submit">Mettre à jour</button>
        <a href="/besoin/list">Annuler</a>
    </p>
</form>

<?php else: ?>
    <p>Besoin introuvable.</p>
    <a href="/besoin/list">Retour à la liste</a>
<?php endif; ?>
