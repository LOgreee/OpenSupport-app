<?php require("config.php");
header_remove("X-Frame-Options");
header("Content-Security-Policy: frame-ancestors *");

// Language manager
$translations = [
    'fr' => [
        'message' => 'La page que vous cherchez n\'existe pas. <br> Vérifiez le lien du support que vous cherchez.',
        'back' => 'Retour',
    ],
    'en' => [
        'message' => 'The page you are looking for does not exist. <br> Check the link for the support you are looking for.',
        'back' => 'Return',
    ],
    'es' => [
        'message' => 'La página que buscas no existe. <br> Consulta el enlace de soporte que estás buscando.',
        'back' => 'Volver',
    ]
];
$t = $translations[$lang];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport</title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
</head>
<body class="dashboard">
    <main>
        <div class="error_page">
            <div>
                <h1>Uh oh...</h1>
                <p><?= $t['message'] ?></p>
                <a href="#" onclick="history.back()" class="btn"><?= $t['back'] ?></a>
            </div>
            <a href="<?= $opensupport_link?>"><img src="<?= $opensupport_link?>/src/opensupport_assets/opensupport_logo.svg" alt="Logo OpenSupport"></a>
        </div>
    </main>
    
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>