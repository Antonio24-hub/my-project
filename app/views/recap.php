<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Récapitulatif Global</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/theme.css">
    <link rel="stylesheet" href="/css/effects.css">
</head>
<body>
<h1>Récapitulatif Global</h1>

<div class="refresh-container">
    <button id="btn-actualiser" class="btn-refresh">
        <span class="btn-icon">&#x21bb;</span> Actualiser
    </button>
    <span id="refresh-status" class="refresh-status">Mis à jour !</span>
</div>

<nav>
<a href="/">Accueil</a> <a href="/besoin/list">Besoins</a> <a href="/don/list">Dons</a> <a href="/distribution/list">Distributions</a> <a href="/stock/list">Stock</a> <a href="/prix/list">Prix</a> <a href="/achat/list">Achats</a> <a href="/tableau-de-bord">Tableau de bord</a> <b>Récap</b>
</nav>

<div class="recap-container">
    
    <section class="recap-section">
        <h2>Besoins</h2>
        <div class="recap-grid">
            <div class="recap-card besoins">
                <div class="label">Besoins Totaux</div>
                <div class="value" id="recap-besoins-total"><?= htmlspecialchars($totaux['total_besoins'] ?? 0) ?></div>
            </div>
            <div class="recap-card satisfaits">
                <div class="label">Besoins Satisfaits</div>
                <div class="value" id="recap-besoins-satisfaits"><?= htmlspecialchars($totaux['besoins_satisfaits'] ?? 0) ?></div>
            </div>
            <div class="recap-card restants">
                <div class="label">Besoins Restants</div>
                <div class="value" id="recap-besoins-restants"><?= htmlspecialchars($totaux['besoins_restants'] ?? 0) ?></div>
            </div>
        </div>
    </section>

    <section class="recap-section">
        <h2>Dons</h2>
        <div class="recap-grid">
            <div class="recap-card dons-recus">
                <div class="label">Dons Reçus</div>
                <div class="value" id="recap-dons-recus"><?= htmlspecialchars($totaux['total_dons'] ?? 0) ?></div>
            </div>
            <div class="recap-card dons-dispatches">
                <div class="label">Dons Dispatchés</div>
                <div class="value" id="recap-dons-dispatches"><?= htmlspecialchars($totaux['dons_dispatches'] ?? 0) ?></div>
            </div>
            <div class="recap-card dons-stock">
                <div class="label">Dons en Stock</div>
                <div class="value" id="recap-dons-stock"><?= htmlspecialchars($totaux['dons_en_stock'] ?? 0) ?></div>
            </div>
        </div>
    </section>

    <section class="recap-section">
        <h2>Achats</h2>
        <div class="recap-grid">
            <div class="recap-card achats">
                <div class="label">Nombre d'Achats</div>
                <div class="value" id="recap-achats-total"><?= htmlspecialchars($totaux['total_achats'] ?? 0) ?></div>
            </div>
            <div class="recap-card montant">
                <div class="label">Montant Total</div>
                <div class="value small" id="recap-achats-montant"><?= number_format((float)($totaux['montant_achats'] ?? 0), 0, ',', ' ') ?> Ar</div>
            </div>
        </div>
    </section>

    <section class="recap-section">
        <h2>Détails par Type de Besoin</h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Quantité Besoins</th>
                    <th>Quantité Dons</th>
                    <th>Quantité Distribuée</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($details_par_type)): ?>
                    <?php foreach ($details_par_type as $type): ?>
                        <tr>
                            <td id="recap-type-<?= htmlspecialchars($type['type_id']) ?>-name"><?= htmlspecialchars($type['type_name']) ?></td>
                            <td id="recap-type-<?= htmlspecialchars($type['type_id']) ?>-besoins"><?= number_format((float)($type['qte_besoins'] ?? 0), 2, ',', ' ') ?></td>
                            <td id="recap-type-<?= htmlspecialchars($type['type_id']) ?>-dons"><?= number_format((float)($type['qte_dons'] ?? 0), 2, ',', ' ') ?></td>
                            <td id="recap-type-<?= htmlspecialchars($type['type_id']) ?>-distribues"><?= number_format((float)($type['qte_distribues'] ?? 0), 2, ',', ' ') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune donnée trouvée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

</div>

<script nonce="<?= htmlspecialchars($csp_nonce ?? '') ?>">
(function() {
    const btnActualiser = document.getElementById('btn-actualiser');
    const refreshStatus = document.getElementById('refresh-status');

    function formatNumber(num, decimals = 0) {
        return new Intl.NumberFormat('fr-FR', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(num);
    }

    async function actualiserDonnees() {
        btnActualiser.disabled = true;
        btnActualiser.innerHTML = '<span class="btn-icon spinning">&#x21bb;</span> Chargement...';
        refreshStatus.style.display = 'none';

        try {
            const response = await fetch('/api/recap');
            if (!response.ok) throw new Error('Erreur réseau');
            const data = await response.json();

            document.getElementById('recap-besoins-total').textContent = data.totaux.total_besoins;
            document.getElementById('recap-besoins-satisfaits').textContent = data.totaux.besoins_satisfaits;
            document.getElementById('recap-besoins-restants').textContent = data.totaux.besoins_restants;
            document.getElementById('recap-dons-recus').textContent = data.totaux.total_dons;
            document.getElementById('recap-dons-dispatches').textContent = data.totaux.dons_dispatches;
            document.getElementById('recap-dons-stock').textContent = data.totaux.dons_en_stock;
            document.getElementById('recap-achats-total').textContent = data.totaux.total_achats;
            document.getElementById('recap-achats-montant').textContent = formatNumber(data.totaux.montant_achats) + ' Ar';

            data.details_par_type.forEach(type => {
                const nameEl = document.getElementById('recap-type-' + type.type_id + '-name');
                const besoinsEl = document.getElementById('recap-type-' + type.type_id + '-besoins');
                const donsEl = document.getElementById('recap-type-' + type.type_id + '-dons');
                const distribuesEl = document.getElementById('recap-type-' + type.type_id + '-distribues');

                if (nameEl) nameEl.textContent = type.type_name;
                if (besoinsEl) besoinsEl.textContent = formatNumber(parseFloat(type.qte_besoins), 2);
                if (donsEl) donsEl.textContent = formatNumber(parseFloat(type.qte_dons), 2);
                if (distribuesEl) distribuesEl.textContent = formatNumber(parseFloat(type.qte_distribues), 2);
            });

            refreshStatus.style.display = 'inline';
            setTimeout(() => { refreshStatus.style.display = 'none'; }, 3000);

        } catch (error) {
            console.error('Erreur lors de l\'actualisation:', error);
            alert('Erreur lors de l\'actualisation des données');
        } finally {
            btnActualiser.disabled = false;
            btnActualiser.innerHTML = '<span class="btn-icon">&#x21bb;</span> Actualiser';
        }
    }

    btnActualiser.addEventListener('click', actualiserDonnees);
})();
</script>

</body>
</html>
