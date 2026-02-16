<h1>Modifier Distribution</h1>

<?php if (!empty($distribution)): ?>
<form action="/distribution/update" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($distribution['id']) ?>">
    <p>
        <label for="id_besoin">Besoin :</label><br>
        <select name="id_besoin" id="id_besoin" required>
            <option value="">-- Choisir un besoin --</option>
            <?php foreach ($besoins as $b): ?>
                <option value="<?= htmlspecialchars($b['id']) ?>"
                    <?= ($b['id'] == $distribution['id_besoin']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($b['name']) ?> (qté: <?= htmlspecialchars($b['quantite']) ?> <?= htmlspecialchars($b['unite'] ?? '') ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </p>
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
