<h1>Modifier Don</h1>

<?php if (!empty($don)): ?>
<form action="/don/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($don['id']) ?>">
    <p>
        <label for="id_typeBesoin">Type :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>"
                    <?= ($t['id'] == $don['id_typeBesoin']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($t['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du don :</label><br>
        <input type="text" name="name" id="name" value="<?= htmlspecialchars($don['name']) ?>" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" value="<?= htmlspecialchars($don['quantite']) ?>" required>
    </p>
    <p>
        <label for="date">Date :</label><br>
        <input type="date" name="date" id="date" value="<?= htmlspecialchars($don['date']) ?>" required>
    </p>
    <p>
        <button type="submit">Mettre à jour</button>
        <a href="/don/list">Annuler</a>
    </p>
</form>
<?php else: ?>
    <p>Don introuvable.</p>
    <a href="/don/list">Retour à la liste</a>
<?php endif; ?>
