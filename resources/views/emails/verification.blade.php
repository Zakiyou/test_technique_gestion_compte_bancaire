<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'inscription</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f9; padding: 20px;">

    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 10px;">
        <h2 style="text-align: center; color: #333;">Bienvenue sur notre site</h2>
        <p>Bonjour,</p>
        <p>Merci de vous être inscrit sur notre plateforme. Afin de finaliser votre inscription, veuillez entrer le code de vérification ci-dessous :</p>
        
        <div style="text-align: center; margin: 20px;">
            <h3 style="font-size: 24px; color: #2d87f0;">{{ $code }}</h3>
        </div>


        <p>Merci,</p>

        <hr style="border: 1px solid #e0e0e0;">
        <p style="font-size: 12px; text-align: center; color: #777;">
            Ce message a été envoyé automatiquement, veuillez ne pas y répondre directement.
        </p>
    </div>

</body>
</html>
