<h1>Nouvelle Distribution</h1>

<?php if (!empty($error)): ?>
    <p style="color: red; font-weight: bold;"><?= $error ?></p>
<?php endif; ?>

<form action="/distribution/save" method="POST">
    <p>
        <label for="id_besoin">Besoin :</label><br>
        <select name="id_besoin" id="id_besoin" required>
            <option value="">-- Choisir un besoin --</option>
            <?php foreach ($besoins as $b): ?>
                <option value="<?= htmlspecialchars($b['id']) ?>">
                    <?= htmlspecialchars($b['name']) ?> (qté: <?= htmlspecialchars($b['quantite']) ?> <?= htmlspecialchars($b['unite'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
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
