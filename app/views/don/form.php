<h1>Nouveau Don</h1>

<form action="/don/save" method="POST">
    <p>
        <label for="id_typeBesoin">Type :</label><br>
        <select name="id_typeBesoin" id="id_typeBesoin" required>
            <option value="">-- Choisir un type --</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t['id']) ?>"><?= htmlspecialchars($t['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label for="name">Nom du don :</label><br>
        <input type="text" name="name" id="name" required>
    </p>
    <p>
        <label for="quantite">Quantité :</label><br>
        <input type="number" name="quantite" id="quantite" step="0.01" required>
    </p>
    <p>
        <label for="date">Date :</label><br>
        <input type="date" name="date" id="date" required>
    </p>
    <p>
        <button type="submit">Enregistrer</button>
        <a href="/don/list">Annuler</a>
    </p>
</form>
