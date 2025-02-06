<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opération Bancaire Effectuée</title>
</head>
<body>
    <h2>Bonjour {{ $compte->user->name }},</h2>

    <p>Nous vous informons que l'opération suivante a été effectuée sur votre compte bancaire :</p>

    <ul>
        <li><strong>Compte :</strong> {{ $compte->numero_compte }}</li>
        <li><strong>Type d'opération :</strong> {{ ucfirst($operation->type) }}</li>
        <li><strong>Montant :</strong> {{ number_format($operation->montant, 2) }} €</li>
        <li><strong>Ancien solde :</strong> {{ number_format($compte->solde + $operation->montant, 2) }} €</li>
        <li><strong>Nouveau solde :</strong> {{ number_format($operation->solde, 2) }} €</li>
        <li><strong>Date :</strong> {{ $operation->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Si vous n'êtes pas à l'origine de cette opération, veuillez contacter notre service clientèle immédiatement.</p>

    <p>Cordialement,</p>
    <p><strong>Votre Banque</strong></p>
</body>
</html>
