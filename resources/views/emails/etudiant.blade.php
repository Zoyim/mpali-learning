<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>MPALI</h2>
<p>Bonjour {{ $etudiants->name }} {{ $etudiants->prenom }},</p>
<p>Nous vous remercions de l'interêt que vous portez à la plate-forme MPALI. Nous vous communiquons vos paramètres de connexion:</p>
<ul>
    <li><strong>Numéro de téléphone</strong> : {{ $etudiants->mobile }}</li>
    <li><strong>Mot de passe</strong> : {{ $etudiants->mobile }}</li>
</ul>
<!--<p>Veuillez cliquer sur ce lien pour vous connecter avec vos paramètres: http://127.0.0.1:8000/dashboard/login</p>-->
</body>
</html>