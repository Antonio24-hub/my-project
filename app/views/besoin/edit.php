<h1>Modifier Besoin</h1>

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
        <label for="id_region">Région :</label><br>
        <select name="id_region" id="id_region" required>
            <option value="">-- Choisir une région --</option>
            <?php foreach ($regions as $r): ?>
                <option value="<?= htmlspecialchars($r['id']) ?>"
                    <?= ($r['id'] == $besoin['id_region']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du besoin :</label><br>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($besoin['name']) ?>" required>
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

<script>
document.getElementById('id_ville').addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var regionId = selected.getAttribute('data-region');
    if (regionId) {
        document.getElementById('id_region').value = regionId;
    }
});
</script>
<?php else: ?>
    <p>Besoin introuvable.</p>
    <a href="/besoin/list">Retour à la liste</a>
<?php endif; ?>
