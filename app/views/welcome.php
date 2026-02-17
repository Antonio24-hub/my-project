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
<h1>Welcome to the FlightPHP Skeleton Example!</h1>
<?php if(!empty($message)) { ?>
<h3><?=$message?></h3>
<?php } ?>

<h2>Navigation</h2>
<ul>
    <li><a href="/besoin/list">Besoins des sinistrés</a></li>
    <li><a href="/don/list">Dons</a></li>
    <li><a href="/distribution/list">Distributions</a></li>
    <li><a href="/stock/list">Stock</a></li>
    <li><a href="/prix/list">Prix unitaires</a></li>
    <li><a href="/achat/list">Achats</a></li>
    <li><a href="/tableau-de-bord">Tableau de bord</a></li>
    <li><a href="/recap">Récap</a></li>
</ul>
</body>
</html>