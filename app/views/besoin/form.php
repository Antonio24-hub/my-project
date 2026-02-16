<h1>Nouveau Besoin</h1>

<form action="/besoin/save" method="POST">
    <p>
        <label for="id_typeBesoin">Type de besoin :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>" data-name="<?= htmlspecialchars($t['name']) ?>">
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
                <option value="<?= htmlspecialchars($v['id']) ?>" data-region="<?= htmlspecialchars($v['region_id']) ?>">
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
                <option value="<?= htmlspecialchars($r['id']) ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du besoin :</label><br>
        <input type="text" name="name" id="name" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" required>
    </p>
    <p>
        <label for="unite">Unité :</label><br>
        <select name="unite" id="unite" required>
            <option value="">-- Choisir une unité --</option>
            <option value="Ariary">Ariary</option>
            <option value="kg">kg</option>
            <option value="litre">litre</option>
            <option value="sac">sac</option>
            <option value="pièce">pièce</option>
            <option value="unité">unité</option>
            <option value="m²">m²</option>
        </select>
    </p>
    <p>
        <button type="submit">Enregistrer</button>
        <a href="/besoin/list">Annuler</a>
    </p>
</form>

<script>
// Auto-select region when ville is selected
document.getElementById('id_ville').addEventListener('change', function() {
    var selected = this.options[this.selectedIndex];
    var regionId = selected.getAttribute('data-region');
    if (regionId) {
        document.getElementById('id_region').value = regionId;
    }
});
</script>
