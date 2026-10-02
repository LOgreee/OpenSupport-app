<?php require("../config.php");
connectionCheck();

//Check current team
if(isset($_GET['team_id']) && $_GET['team_id'] != ""){
    if(verifyTeamAccess($_GET['team_id'])==false){
        header("Location: ../../dashboard/");
        exit();
    }
} else {
    header("Location: ../dashboard/");
    exit();
}

// Language manager
$translations = [
    'fr' => [
        'page_title' => 'Paramètres',
        'page_subtitle' => 'Personnalisez les différents paramètres de votre équipe.',
        'btn_modify' => 'Modifier',
        'nav_team_settings' => 'Paramètres de l\'équipe',
        'nav_forms_rules' => 'Formulaire & règles d\'équipe',
        'nav_messages_template' => 'Messages pré-définis',
        'nav_integration_api' => 'Intégration & API',
        'nav_data' => 'Data',
        'nav_advanced_settings' => 'Paramètres avancés',
        'error_required_fields' => 'Veuillez compléter les champs obligatoires (*).',
        'error_slug_exists' => 'Ce lien de formulaire support est déjà utilisé par une autre équipe.',
        'error_logo_size' => 'Le logo dépasse la taille limite autorisée de :size Mo.',
        'error_logo_upload' => 'Erreur lors du téléversement du fichier (Code: :code).',
        'error_logo_webp' => 'Erreur lors de la conversion de l\'image en WebP.',
        'error_logo_process' => 'Impossible de traiter l\'image sélectionnée.',
        'error_logo_format' => 'Format d\'image non supporté (utilisez .jpg, .png ou .webp).',
        'success_team_update' => 'Les paramètres de l\'équipe ont été mis à jour avec succès.',
        'error_generic' => 'Une erreur est survenue, veuillez réessayer.',
        'success_templates_update' => 'Les messages templates ont été mis à jour avec succès.',
        'label_team_name' => 'Nom de l\'équipe (Affiché au client) *',
        'label_support_link' => 'Lien du support *',
        'label_privacy_link' => 'Lien politique de confidentialité',
        'label_main_color' => 'Couleur principale',
        'label_team_logo' => 'Logo de l\'équipe',
        'title_change_logo' => 'Changer le logo',
        'title_delete_logo' => 'Supprimer le logo',
        'dropzone_main_text' => '<strong>Cliquez pour télécharger</strong> ou glissez l\'image ici',
        'dropzone_sub_text' => 'PNG, JPG ou WEBP (Max :size Mo)',
        'js_invalid_image' => 'Veuillez sélectionner un fichier image valide.',
        'forms_rules_title' => 'Formulaire & règles d\'équipe',
        'forms_rules_desc' => 'Modifiez les champs de votre formulaire de contact support et définissez des règles d’équipes pour répartir automatiquement les nouveaux tickets.',
        'templates_title' => 'Messages pré-définis',
        'templates_desc' => 'Modifiez les messages pré-définis pour votre équipe.',
        'th_template_name' => 'Nom',
        'th_template_message' => 'Message',
        'btn_add_template' => 'Ajouter',
        'integration_title' => 'Intégration & API',
        'label_width' => 'Largeur (px) *',
        'label_height' => 'Hauteur (px) *',
        'label_theme' => 'Thème *',
        'theme_light' => 'Lumineux',
        'theme_dark' => 'Sombre',
        'label_transparent_bg' => 'Fond transparent',
        'label_iframe_code' => 'Code à intégrer',
        'btn_copy_code' => 'Copier le code',
        'label_api_url' => 'URL de l\'API',
        'btn_copy_link' => 'Copier le lien',
        'label_api_key' => 'Clé secrète d\'API',
        'btn_generate_api' => 'Générer une nouvelle clé',
        'btn_copied' => 'Copié !',
        'btn_generating' => 'Génération...',
        'confirm_generate_api' => "Êtes-vous sûr de vouloir générer une nouvelle clé d'API ?\rL'ancienne clé cessera immédiatement de fonctionner.",
        'link_api_doc' => 'Documentation API',
        'link_api_secret_info' => 'Pourquoi garder la clé d\'API secrète ?',
        'link_opensupport_privacy' => 'Politique de confidentialité de OpenSupport',
        'link_gdpr_info' => 'Règlement Général sur la Protection des Données (RGPD)',
        'link_anonymize_tickets' => 'Anonymiser les données des tickets fermés (automatique après 30 jours)',
        'link_delete_attachments' => 'Supprimer les pièces jointes des tickets fermés (automatique après 30 jours)',
        'link_export_support' => 'Exporter les données du support',
        'advanced_transfer_team' => 'Transférer l\'équipe',
        'advanced_quit_team' => 'Quitter l\'équipe',
        'advanced_delete_team' => 'Supprimer l\'équipe'
    ],
    'en' => [
        'page_title' => 'Settings',
        'page_subtitle' => 'Customize your team settings.',
        'btn_modify' => 'Update',
        'nav_team_settings' => 'Team Settings',
        'nav_forms_rules' => 'Forms & Team Rules',
        'nav_messages_template' => 'Pre-defined messages',
        'nav_integration_api' => 'Integration & API',
        'nav_data' => 'Data',
        'nav_advanced_settings' => 'Advanced Settings',
        'error_required_fields' => 'Please fill in all mandatory fields (*).',
        'error_slug_exists' => 'This support form slug is already in use by another team.',
        'error_logo_size' => 'The logo exceeds the maximum allowed size of :size MB.',
        'error_logo_upload' => 'Error uploading file (Code: :code).',
        'error_logo_webp' => 'Error converting image to WebP format.',
        'error_logo_process' => 'Could not process the selected image.',
        'error_logo_format' => 'Unsupported image format (use .jpg, .png, or .webp).',
        'success_team_update' => 'Team settings successfully updated.',
        'error_generic' => 'An error occurred, please try again.',
        'success_templates_update' => 'Canned responses successfully updated.',
        'label_team_name' => 'Team Name (Displayed to client) *',
        'label_support_link' => 'Support Link *',
        'label_privacy_link' => 'Privacy Policy Link',
        'label_main_color' => 'Primary Color',
        'label_team_logo' => 'Team Logo',
        'title_change_logo' => 'Change logo',
        'title_delete_logo' => 'Delete logo',
        'dropzone_main_text' => '<strong>Click to upload</strong> or drag and drop image here',
        'dropzone_sub_text' => 'PNG, JPG or WEBP (Max :size MB)',
        'js_invalid_image' => 'Please select a valid image file.',
        'forms_rules_title' => 'Forms & Team Rules',
        'forms_rules_desc' => 'Customize support form fields and configure routing rules to automatically dispatch new tickets.',
        'templates_title' => 'Canned Responses',
        'templates_desc' => 'Manage pre-defined message templates for your team.',
        'th_template_name' => 'Name',
        'th_template_message' => 'Message',
        'btn_add_template' => 'Add',
        'integration_title' => 'Integration & API',
        'label_width' => 'Width (px) *',
        'label_height' => 'Height (px) *',
        'label_theme' => 'Theme *',
        'theme_light' => 'Light',
        'theme_dark' => 'Dark',
        'label_transparent_bg' => 'Transparent background',
        'label_iframe_code' => 'Embed code',
        'btn_copy_code' => 'Copy code',
        'label_api_url' => 'API URL',
        'btn_copy_link' => 'Copy link',
        'label_api_key' => 'Secret API Key',
        'btn_generate_api' => 'Generate new key',
        'btn_copied' => 'Copied!',
        'btn_generating' => 'Generating...',
        'confirm_generate_api' => "Are you sure you want to generate a new API key?\rThe old key will immediately stop working.",
        'link_api_doc' => 'API Documentation',
        'link_api_secret_info' => 'Why keep your API key secret?',
        'link_opensupport_privacy' => 'OpenSupport Privacy Policy',
        'link_gdpr_info' => 'General Data Protection Regulation (GDPR)',
        'link_anonymize_tickets' => 'Anonymize closed tickets data (automatic after 30 days)',
        'link_delete_attachments' => 'Delete closed tickets attachments (automatic after 30 days)',
        'link_export_support' => 'Export support data',
        'advanced_transfer_team' => 'Transfer team',
        'advanced_quit_team' => 'Leave team',
        'advanced_delete_team' => 'Delete team'
    ],
    'es' => [
        'page_title' => 'Ajustes',
        'page_subtitle' => 'Personalice los diferentes ajustes de su equipo.',
        'btn_modify' => 'Modificar',
        'nav_team_settings' => 'Ajustes del equipo',
        'nav_forms_rules' => 'Formulario y reglas del equipo',
        'nav_messages_template' => 'Respuestas predefinidas',
        'nav_integration_api' => 'Integración y API',
        'nav_data' => 'Datos',
        'nav_advanced_settings' => 'Ajustes avanzados',
        'error_required_fields' => 'Por favor, complete los campos obligatorios (*).',
        'error_slug_exists' => 'Este enlace de formulario ya está siendo utilizado por otro equipo.',
        'error_logo_size' => 'El logo supera el tamaño límite permitido de :size MB.',
        'error_logo_upload' => 'Error al subir el archivo (Código: :code).',
        'error_logo_webp' => 'Error al convertir la imagen a formato WebP.',
        'error_logo_process' => 'No se pudo procesar la imagen seleccionada.',
        'error_logo_format' => 'Formato de imagen no compatible (use .jpg, .png o .webp).',
        'success_team_update' => 'Los ajustes del equipo se actualizaron con éxito.',
        'error_generic' => 'Ha ocurrido un error, por favor inténtelo de nuevo.',
        'success_templates_update' => 'Las respuestas predefinidas se actualizaron con éxito.',
        'label_team_name' => 'Nombre del equipo (Visible para el cliente) *',
        'label_support_link' => 'Enlace de soporte *',
        'label_privacy_link' => 'Enlace de la política de privacidad',
        'label_main_color' => 'Color principal',
        'label_team_logo' => 'Logo del equipo',
        'title_change_logo' => 'Cambiar el logo',
        'title_delete_logo' => 'Eliminar el logo',
        'dropzone_main_text' => '<strong>Haga clic para subir</strong> o arrastre la imagen aquí',
        'dropzone_sub_text' => 'PNG, JPG o WEBP (Máx :size MB)',
        'js_invalid_image' => 'Por favor, seleccione un archivo de imagen válido.',
        'forms_rules_title' => 'Formulario y reglas del equipo',
        'forms_rules_desc' => 'Modifique los campos del formulario de soporte y defina reglas para repartir automáticamente los nuevos tickets.',
        'templates_title' => 'Respuestas predefinidas',
        'templates_desc' => 'Gestione las plantillas de mensajes predefinidos para su equipo.',
        'th_template_name' => 'Nombre',
        'th_template_message' => 'Mensaje',
        'btn_add_template' => 'Añadir',
        'integration_title' => 'Integración y API',
        'label_width' => 'Anchura (px) *',
        'label_height' => 'Altura (px) *',
        'label_theme' => 'Tema *',
        'theme_light' => 'Claro',
        'theme_dark' => 'Oscuro',
        'label_transparent_bg' => 'Fondo transparente',
        'label_iframe_code' => 'Código de inserción',
        'btn_copy_code' => 'Copiar código',
        'label_api_url' => 'URL de la API',
        'btn_copy_link' => 'Copiar enlace',
        'label_api_key' => 'Clave secreta de API',
        'btn_generate_api' => 'Generar nueva clave',
        'btn_copied' => '¡Copiado!',
        'btn_generating' => 'Generando...',
        'confirm_generate_api' => "¿Está seguro de querer generar una nueva clave de API?\rLa clave anterior dejará de funcionar inmediatamente.",
        'link_api_doc' => 'Documentación de la API',
        'link_api_secret_info' => '¿Por qué mantener secreta la clave de API?',
        'link_opensupport_privacy' => 'Política de privacidad de OpenSupport',
        'link_gdpr_info' => 'Reglamento General de Protección de Datos (RGPD)',
        'link_anonymize_tickets' => 'Anonimizar los datos de tickets cerrados (automático tras 30 días)',
        'link_delete_attachments' => 'Eliminar archivos adjuntos de tickets cerrados (automático tras 30 días)',
        'link_export_support' => 'Exportar datos de soporte',
        'advanced_transfer_team' => 'Transferir el equipo',
        'advanced_quit_team' => 'Salir del equipo',
        'advanced_delete_team' => 'Eliminar el equipo'
    ]
];
$t = $translations[$lang];

$form_feedback = [
    'global' => '',
    'templates' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_section']) && $_POST['form_section'] === 'global') {
        // Team settings update
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $privacy_policy_url = trim($_POST['privacy_policy_url'] ?? '');
        $color = trim($_POST['color'] ?? '#635bff');
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '', str_replace(' ', '-', $slug)));
        
        if (empty($name) || empty($slug)) {
            $form_feedback['global'] = $t['error_required_fields'];
        } else {
            try {
                $stmtCheckSlug = $dbco->prepare("SELECT teams_id FROM teams WHERE teams_form_url = :slug AND teams_id != :team_id LIMIT 1");
                $stmtCheckSlug->execute(['slug' => $slug, 'team_id' => $_SESSION['team_id']]);
                if ($stmtCheckSlug->fetch()) {
                    $form_feedback['global'] = $t['error_slug_exists'];
                } else {
                    $logo_uploaded = 0;
                    $maxSizeBytes = ($opensupport_max_file_size ?? 10) * 1024 * 1024;
                    if (isset($_FILES['logo']) && $_FILES['logo']['error'] !== UPLOAD_ERR_NO_FILE) {
                        if ($_FILES['logo']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['logo']['error'] === UPLOAD_ERR_FORM_SIZE || $_FILES['logo']['size'] > $maxSizeBytes) {
                            $form_feedback['global'] = str_replace(':size', ($opensupport_max_file_size ?? 10), $t['error_logo_size']);
                        } elseif ($_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                            $form_feedback['global'] = str_replace(':code', $_FILES['logo']['error'], $t['error_logo_upload']);
                        } else {
                            $tmpPath = $_FILES['logo']['tmp_name'];
                            $mimeType = function_exists('finfo_open') 
                                ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tmpPath) 
                                : (function_exists('mime_content_type') ? mime_content_type($tmpPath) : $_FILES['logo']['type']);
                            $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp'];
                            if (in_array($mimeType, $allowedImageTypes)) {
                                $uploadDir = dirname(__DIR__) . '/up/teams/';
                                if (!is_dir($uploadDir)) {
                                    mkdir($uploadDir, 0777, true);
                                }
                                $destFileName = $_SESSION['team_id'] . '.webp';
                                $destPath = $uploadDir . $destFileName;
                                $srcImage = null;
                                if ($mimeType === 'image/jpeg') {
                                    $srcImage = @imagecreatefromjpeg($tmpPath);
                                } elseif ($mimeType === 'image/png') {
                                    $srcImage = @imagecreatefrompng($tmpPath);
                                } elseif ($mimeType === 'image/webp') {
                                    $srcImage = @imagecreatefromwebp($tmpPath);
                                }

                                if ($srcImage) {
                                    imagepalettetotruecolor($srcImage);
                                    imagealphablending($srcImage, true);
                                    imagesavealpha($srcImage, true);

                                    if (imagewebp($srcImage, $destPath, 90)) {
                                        $logo_uploaded = 1;
                                    } else {
                                        $form_feedback['global'] = $t['error_logo_webp'];
                                    }
                                    imagedestroy($srcImage);
                                } else {
                                    $form_feedback['global'] = $t['error_logo_process'];
                                }
                            } else {
                                $form_feedback['global'] = $t['error_logo_format'];
                            }
                        }
                    }
                    if (empty($form_feedback['global'])) {
                        $sql = "UPDATE teams SET teams_name = :name, teams_form_url = :slug, teams_privacy_policy_url = :privacy_url, teams_color = :color";
                        $params = ['name' => $name, 'slug' => $slug, 'privacy_url' => $privacy_policy_url, 'color' => $color, 'team_id' => $_SESSION['team_id']];
                        $delete_logo = (isset($_POST['delete_logo']) && $_POST['delete_logo'] === "1");
                        if ($delete_logo) {
                            $filePath = dirname(__DIR__) . '/up/teams/' . $_SESSION['team_id'] . '.webp';
                            if (file_exists($filePath)) {
                                unlink($filePath);
                            }
                            $sql .= ", teams_logo = 0";
                        } elseif ($logo_uploaded == 1) {
                            $sql .= ", teams_logo = 1";
                        }
                        $sql .= " WHERE teams_id = :team_id";
                        $stmtUpdate = $dbco->prepare($sql);
                        $stmtUpdate->execute($params);
                        $form_feedback['global'] = $t['success_team_update'];
                    }
                }
            } catch (PDOException $e) {
                $form_feedback['global'] = $t['error_generic'];
            }
        }
        
    } elseif (isset($_POST['form_section']) && $_POST['form_section'] === 'messages_template') {
        // Message templates update
        $names = $_POST['message_name'] ?? [];
        $contents = $_POST['message_content'] ?? [];
        $templatesToSave = [];
        $limit = min(count($names), 50);
        for ($i = 0; $i < $limit; $i++) {
            $name = trim($names[$i] ?? '');
            $content = trim($contents[$i] ?? '');
            if (!empty($name) && !empty($content)) {
                $templatesToSave[] = [
                    'name' => $name,
                    'message' => $content
                ];
            }
        }
        $jsonToSave = json_encode($templatesToSave, JSON_UNESCAPED_UNICODE);
        try {
            $stmt = $dbco->prepare("UPDATE teams SET teams_messages_template = :template WHERE teams_id = :team_id");
            $stmt->execute(['template' => $jsonToSave, 'team_id' => $_SESSION['team_id']]);
            $form_feedback['templates'] = $t['success_templates_update'];
        } catch (PDOException $e) {
            $form_feedback['templates'] = $t['error_generic'];
        }
    }
}

$stmtTeam = $dbco->prepare("SELECT * FROM teams WHERE teams_id = :team_id AND (teams_deleted = 0 OR teams_deleted IS NULL) LIMIT 1");
$stmtTeam->execute(['team_id' => $_GET['team_id']]);
$current_settings = $stmtTeam->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport | <?= $t['page_title'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="dashboard">

    <?php include("../src/php/dashboard_nav.php");?>
    
    <main>
        <header>
            <div>
                <h1><?= $t['page_title'] ?></h1>
                <p><?= $t['page_subtitle'] ?></p>
            </div>
        </header>

        <div class="grid-cols-4">
            <div class="summary">
                <a href="#team_settings"><?= $t['nav_team_settings'] ?></a>
                <a href="#forms_rules"><?= $t['nav_forms_rules'] ?></a>
                <a href="#messages_template"><?= $t['nav_messages_template'] ?></a>
                <a href="#integration_api"><?= $t['nav_integration_api'] ?></a>
                <a href="#data"><?= $t['nav_data'] ?></a>
                <a href="#advenced_settings"><?= $t['nav_advanced_settings'] ?></a>
            </div>
            
            <div class="content">
                <!-- Team settings -->
                <section class="card" id="team_settings">
                    <header>
                        <h2><?= $t['nav_team_settings'] ?></h2>
                    </header>
                    <form method="POST" enctype="multipart/form-data">
                        <?php if ($form_feedback['global'] != ""): ?>
                            <p class="alert"><?= htmlspecialchars($form_feedback['global']); ?></p>
                        <?php endif; ?>
                        <input type="hidden" name="form_section" value="global" required>
                        <div>
                            <label><?= $t['label_team_name'] ?></label>
                            <input type="text" name="name" value="<?= htmlspecialchars($current_settings['teams_name'] ?? '') ?>" required>
                        </div>
                        <div>
                            <label><?= $t['label_support_link'] ?></label>
                            <div class="input-pre-fill">
                                <span><?= $opensupport_link?>/form/</span>
                                <input type="text" name="slug" value="<?= htmlspecialchars($current_settings['teams_form_url'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div>
                            <label><?= $t['label_privacy_link'] ?></label>
                            <input type="text" name="privacy_policy_url" value="<?= htmlspecialchars($current_settings['teams_privacy_policy_url'] ?? '') ?>">
                        </div>
                        <div class="grid-cols-2">
                            <div>
                                <label><?= $t['label_main_color'] ?></label>
                                <input type="color" name="color" value="<?= htmlspecialchars($current_settings['teams_color'] ?? '#635bff') ?>">
                            </div>
                            <div class="team-logo">
                                <label><?= $t['label_team_logo'] ?></label>
                                <?php $has_logo = (!empty($current_settings['teams_logo']) && $current_settings['teams_logo'] == "1"); ?>
                                <input type="hidden" name="delete_logo" id="delete_logo" value="0">
                                <input type="file" id="logo_input" name="logo" accept="image/png, image/jpeg, image/webp" style="display: none;">

                                <div class="logo-uploader-card <?= $has_logo ? 'has-image' : '' ?>" id="logo_dropzone">
                                    <div class="logo-preview-wrapper" id="logo_preview_wrapper" style="<?= $has_logo ? '' : 'display: none;' ?>">
                                        <img src="<?= $has_logo ? ($opensupport_link . '/up/teams/' . (int)$_GET['team_id'] . '.webp?t=' . time()) : '' ?>" alt="Logo" id="logo_preview_img" class="logo-preview-img">
                                        <div class="logo-actions-overlay">
                                            <button type="button" class="logo-action-btn change" id="btn_change_logo" title="<?= $t['title_change_logo'] ?>">
                                                ✏️
                                            </button>
                                            <button type="button" class="logo-action-btn delete" id="btn_remove_logo" title="<?= $t['title_delete_logo'] ?>">
                                                🗑️
                                            </button>
                                        </div>
                                    </div>
                                    <div class="logo-empty-state" id="logo_empty_state" style="<?= $has_logo ? 'display: none;' : '' ?>">
                                        <div class="upload-icon">
                                            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242M12 12v9m-4-4 4-4 4 4"/>
                                            </svg>
                                        </div>
                                        <p class="primary-text"><?= $t['dropzone_main_text'] ?></p>
                                        <span class="sub-text"><?= str_replace(':size', ($opensupport_max_file_size ?? 10), $t['dropzone_sub_text']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', () => {
                                    const dropzone = document.getElementById('logo_dropzone');
                                    const fileInput = document.getElementById('logo_input');
                                    const previewWrapper = document.getElementById('logo_preview_wrapper');
                                    const previewImg = document.getElementById('logo_preview_img');
                                    const emptyState = document.getElementById('logo_empty_state');
                                    const deleteInput = document.getElementById('delete_logo');
                                    const changeBtn = document.getElementById('btn_change_logo');
                                    const removeBtn = document.getElementById('btn_remove_logo');
                                    if (!dropzone || !fileInput) return;
                                    function displayPreview(file) {
                                        if (!file.type.startsWith('image/')) {
                                            alert(<?= json_encode($t['js_invalid_image']) ?>);
                                            return;
                                        }
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            previewImg.src = e.target.result;
                                            emptyState.style.display = 'none';
                                            previewWrapper.style.display = 'flex';
                                            dropzone.classList.add('has-image');
                                            deleteInput.value = "0";
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                    dropzone.addEventListener('click', () => {
                                        if (!dropzone.classList.contains('has-image')) {
                                            fileInput.click();
                                        }
                                    });
                                    changeBtn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        fileInput.click();
                                    });
                                    removeBtn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        fileInput.value = '';
                                        previewImg.src = '';
                                        previewWrapper.style.display = 'none';
                                        emptyState.style.display = 'flex';
                                        dropzone.classList.remove('has-image');
                                        deleteInput.value = "1";
                                    });
                                    fileInput.addEventListener('change', () => {
                                        if (fileInput.files && fileInput.files[0]) {
                                            displayPreview(fileInput.files[0]);
                                        }
                                    });
                                    dropzone.addEventListener('dragover', (e) => {
                                        e.preventDefault();
                                        dropzone.classList.add('dragover');
                                    });
                                    dropzone.addEventListener('dragleave', () => {
                                        dropzone.classList.remove('dragover');
                                    });
                                    dropzone.addEventListener('drop', (e) => {
                                        e.preventDefault();
                                        dropzone.classList.remove('dragover');
                                        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                                            fileInput.files = e.dataTransfer.files;
                                            displayPreview(e.dataTransfer.files[0]);
                                        }
                                    });
                                });
                            </script>
                        </div>
                        <button type="submit"><?= $t['btn_modify'] ?></button>
                    </form>
                </section>
                
                <!-- Forms & rules settings -->
                <section class="card" id="forms_rules">
                    <header>
                        <h2><?= $t['forms_rules_title'] ?></h2>
                        <p><?= $t['forms_rules_desc'] ?></p>
                    </header>
                    <a href="form_editor" class="btn"><?= $t['btn_modify'] ?> ➔</a>
                </section>
                
                <!-- Messages template -->
                <section class="card" id="messages_template">
                    <header>
                        <h2><?= $t['templates_title'] ?></h2>
                        <p><?= $t['templates_desc'] ?></p>
                    </header>
                    <form method="POST">
                        <?php if ($form_feedback['templates'] != ""): ?>
                            <p class="alert"><?= htmlspecialchars($form_feedback['templates']); ?></p>
                        <?php endif; ?>
                        <input type="hidden" name="form_section" value="messages_template" required>
                        <div class="template-list-header">
                            <div style="flex: 1;"><label><?= $t['th_template_name'] ?></label></div>
                            <div style="flex: 2;"><label><?= $t['th_template_message'] ?></label></div>
                            <div style="width: 45px;"></div>
                        </div>
                        <div id="template-list" class="template-list">
                            <?php $existing_templates = [];
                            if (isset($_SESSION['team_id'])) {
                                if (!empty($current_settings['teams_messages_template'])) {
                                    $decoded = json_decode($current_settings['teams_messages_template'], true);
                                    if (is_array($decoded)) {
                                        $existing_templates = $decoded;
                                    }
                                }
                            }
                            if (empty($existing_templates)) {
                                $existing_templates[] = ['name' => '', 'message' => ''];
                            }
                            foreach ($existing_templates as $template): ?>
                                <div class="template-row" draggable="true">
                                    <div class="drag-handle">☰</div>
                                    <div style="flex: 1;">
                                        <input type="text" name="message_name[]" value="<?php echo htmlspecialchars($template['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <div style="flex: 2;">
                                        <input type="text" name="message_content[]" value="<?php echo htmlspecialchars($template['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                                    </div>
                                    <button type="button" class="remove-btn" title="Delete">🗑️</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="javascript:void(0)" id="add-template-btn"><?= $t['btn_add_template'] ?></a>
                        <span id="template-count" style="margin-left: 10px; color: #666; font-size: 0.9em;"></span>
                        <button type="submit"><?= $t['btn_modify'] ?></button>
                    </form>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const list = document.getElementById('template-list');
                            const addBtn = document.getElementById('add-template-btn');
                            const countDisplay = document.getElementById('template-count');
                            const MAX_TEMPLATES = 50;
                            
                            function updateCount() {
                                const rowCount = list.querySelectorAll('.template-row').length;
                                countDisplay.textContent = `${rowCount} / ${MAX_TEMPLATES}`;
                                addBtn.style.display = rowCount >= MAX_TEMPLATES ? 'none' : 'inline-block';
                            }
                            
                            list.addEventListener('click', function(e) {
                                if (e.target.classList.contains('remove-btn')) {
                                    const row = e.target.closest('.template-row');
                                    if (list.querySelectorAll('.template-row').length > 1) {
                                        row.remove();
                                        updateCount();
                                    } else {
                                        row.querySelector('input[name="message_name[]"]').value = '';
                                        row.querySelector('input[name="message_content[]"]').value = '';
                                    }
                                }
                            });
                            
                            addBtn.addEventListener('click', function() {
                                const rows = list.querySelectorAll('.template-row');
                                if (rows.length < MAX_TEMPLATES) {
                                    const newRow = rows[rows.length - 1].cloneNode(true);
                                    newRow.querySelector('input[name="message_name[]"]').value = '';
                                    newRow.querySelector('input[name="message_content[]"]').value = '';
                                    list.appendChild(newRow);
                                    updateCount();
                                }
                            });

                            let draggedItem = null;
                            let isHandleClicked = false;
                            list.addEventListener('mousedown', function(e) {
                                isHandleClicked = e.target.classList.contains('drag-handle');
                            });
                            list.addEventListener('dragstart', function(e) {
                                const row = e.target.closest('.template-row');
                                if (row && isHandleClicked) {
                                    draggedItem = row;
                                    setTimeout(() => row.style.opacity = '0.4', 0);
                                } else {
                                    e.preventDefault();
                                }
                            });
                            list.addEventListener('dragend', function() {
                                if (draggedItem) {
                                    draggedItem.style.opacity = '1';
                                    draggedItem = null;
                                }
                            });
                            list.addEventListener('dragover', function(e) {
                                e.preventDefault();
                                if (!draggedItem) return;
                                const afterElement = getDragAfterElement(list, e.clientY);
                                if (afterElement == null) {
                                    list.appendChild(draggedItem);
                                } else {
                                    list.insertBefore(draggedItem, afterElement);
                                }
                            });

                            function getDragAfterElement(container, y) {
                                const draggableElements = [...container.querySelectorAll('.template-row:not([style*="opacity: 0.4"])')];
                                return draggableElements.reduce((closest, child) => {
                                    const box = child.getBoundingClientRect();
                                    const offset = y - box.top - box.height / 2;
                                    if (offset < 0 && offset > closest.offset) {
                                        return { offset: offset, element: child };
                                    } else {
                                        return closest;
                                    }
                                }, { offset: Number.NEGATIVE_INFINITY }).element;
                            }
                            updateCount();
                        });
                    </script>
                </section>
                
                <!-- Integration & API -->
                <section class="card" id="integration_api">
                    <header>
                        <h2><?= $t['integration_title'] ?></h2>
                    </header>
                    <form method="POST">
                        <div class="grid-cols-2">
                            <div>
                                <div class="grid-cols-2">
                                    <div>
                                        <label for="iframe_width"><?= $t['label_width'] ?></label>
                                        <input type="number" id="iframe_width" name="width" min="200" value="960">
                                    </div>
                                    <div>
                                        <label for="iframe_height"><?= $t['label_height'] ?></label>
                                        <input type="number" id="iframe_height" name="height" min="200" value="700">
                                    </div>
                                </div>
                                <div>
                                    <label for="iframe_theme"><?= $t['label_theme'] ?></label>
                                    <select id="iframe_theme" name="theme">
                                        <option value="light" selected><?= $t['theme_light'] ?></option>
                                        <option value="dark"><?= $t['theme_dark'] ?></option>
                                    </select>
                                </div>
                                <div>
                                    <div class="check" style="margin-top: 10px;">
                                        <input type="checkbox" id="iframe_no_bg" name="no_bg">
                                        <label for="iframe_no_bg"><?= $t['label_transparent_bg'] ?></label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label for="iframe_code"><?= $t['label_iframe_code'] ?></label>
                                <textarea id="iframe_code" readonly style="width: 100%; height: 120px; font-family: monospace; font-size: 12px; resize: none;"></textarea>
                                <button type="button" class="btn" id="btn_copy_iframe" style="margin-top: 5px;"><?= $t['btn_copy_code'] ?></button>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <label for="api_url_input"><?= $t['label_api_url'] ?></label>
                            <div class="api-input">
                                <input type="text" id="api_url_input" name="api_url" value="<?= $opensupport_link?>/api/v1/" readonly>
                                <button type="button" class="btn" id="btn_copy_api"><?= $t['btn_copy_link'] ?></button>
                            </div>
                        </div>
                        <div>
                            <label for="api_key_input"><?= $t['label_api_key'] ?></label>
                            <div class="api-input">
                                <input type="password" id="api_key_input" name="api_key" value="<?= htmlspecialchars($current_settings['teams_api_token'] ?? '') ?>" readonly>
                                <button type="button" class="btn" id="btn_generate_api"><?= $t['btn_generate_api'] ?></button>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const card = document.getElementById('integration_api');
                                if (!card) return;
                                const teamId = <?= (int)$_GET['team_id'] ?>;
                                const baseUrl = "<?= rtrim($opensupport_link, '/') ?>";
                                const teamSlug = "<?= rawurlencode($current_settings['teams_form_url'] ?? '') ?>";
                                const i18nApi = {
                                    copied: <?= json_encode($t['btn_copied']) ?>,
                                    generating: <?= json_encode($t['btn_generating']) ?>,
                                    defaultGenerate: <?= json_encode($t['btn_generate_api']) ?>,
                                    confirmGenerate: <?= json_encode($t['confirm_generate_api']) ?>,
                                    error: <?= json_encode($t['error_generic']) ?>
                                };

                                // Generate Iframe
                                const widthInput = document.getElementById('iframe_width');
                                const heightInput = document.getElementById('iframe_height');
                                const themeSelect = document.getElementById('iframe_theme');
                                const noBgCheckbox = document.getElementById('iframe_no_bg');
                                const codeTextarea = document.getElementById('iframe_code');
                                const copyBtn = document.getElementById('btn_copy_iframe');

                                function updateIframeCode() {
                                    const width = widthInput.value.trim() || '100%';
                                    const height = heightInput.value.trim() || '700';
                                    const theme = themeSelect.value;
                                    const noBg = noBgCheckbox.checked;
                                    const params = new URLSearchParams();
                                    params.append('type', 'iframe');
                                    params.append('theme', theme);
                                    if (noBg) {
                                        params.append('no_bg', '1');
                                    }
                                    const src = `${baseUrl}/form/${teamSlug}?${params.toString()}`;
                                    const widthAttr = width.endsWith('%') ? width : `${width}px`;
                                    const heightAttr = height.endsWith('%') ? height : `${height}px`;
                                    codeTextarea.value = `<iframe src="${src}" width="${widthAttr}" height="${heightAttr}" style="border:none; width: 100%; max-width: ${widthAttr};" allow="clipboard-write"></iframe>`;
                                }

                                [widthInput, heightInput, themeSelect, noBgCheckbox].forEach(el => {
                                    el.addEventListener('input', updateIframeCode);
                                    el.addEventListener('change', updateIframeCode);
                                });

                                copyBtn.addEventListener('click', () => {
                                    codeTextarea.select();
                                    navigator.clipboard.writeText(codeTextarea.value).then(() => {
                                        const originalText = copyBtn.textContent;
                                        copyBtn.textContent = i18nApi.copied;
                                        setTimeout(() => copyBtn.textContent = originalText, 2000);
                                    });
                                });

                                updateIframeCode();

                                // Generate API key
                                const generateBtn = document.getElementById('btn_generate_api');
                                const apiKeyInput = document.getElementById('api_key_input');

                                generateBtn.addEventListener('click', async () => {
                                    const confirmed = confirm(i18nApi.confirmGenerate);
                                    if (!confirmed) return;
                                    generateBtn.disabled = true;
                                    generateBtn.textContent = i18nApi.generating;
                                    try {
                                        const formData = new FormData();
                                        formData.append('team_id', teamId);
                                        const response = await fetch(`${baseUrl}/src/php/set_api_key.php`, {
                                            method: 'POST',
                                            body: formData
                                        });
                                        const data = await response.json();
                                        if (data.success) {
                                            apiKeyInput.value = data.api_key;
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        alert(i18nApi.error);
                                    } finally {
                                        generateBtn.disabled = false;
                                        generateBtn.textContent = i18nApi.defaultGenerate;
                                    }
                                });

                                // API URL
                                const copyAPIurlBtn = document.getElementById('btn_copy_api');
                                const apiUrlInput = document.getElementById('api_url_input');
                                copyAPIurlBtn.addEventListener('click', () => {
                                    apiUrlInput.select();
                                    navigator.clipboard.writeText(apiUrlInput.value).then(() => {
                                        const originalText = copyAPIurlBtn.textContent;
                                        copyAPIurlBtn.textContent = i18nApi.copied;
                                        setTimeout(() => copyAPIurlBtn.textContent = originalText, 2000);
                                    });
                                });
                            });
                        </script>
                    </form>
                    <div class="links">
                        <a href="" target="_blank"><?= $t['link_api_doc'] ?></a>
                        <a href="" target="_blank"><?= $t['link_api_secret_info'] ?></a>
                    </div>
                </section>
                
                <!-- Data -->
                <section class="card" id="data">
                    <header>
                        <h2><?= $t['nav_data'] ?></h2>
                    </header>
                    <div class="links">
                        <a href="<?= $opensupport_link?>/privacy_policy/" target="_blank"><?= $t['link_opensupport_privacy'] ?></a>
                        <a href="https://www.cnil.fr/en/official-texts" target="_blank"><?= $t['link_gdpr_info'] ?></a>
                        <a href="action/anonymize_team_tickets"><?= $t['link_anonymize_tickets'] ?></a>
                        <a href="action/delete_team_attachments"><?= $t['link_delete_attachments'] ?></a>
                        <a href="action/export_team"><?= $t['link_export_support'] ?></a>
                    </div>
                </section>
        
                <!-- Advanced settings -->
                <section class="card" id="advenced_settings">
                    <header>
                        <h2><?= $t['nav_advanced_settings'] ?></h2>
                    </header>
                    <div class="links">
                        <a href="action/transfer_team_admin" class="red"><?= $t['advanced_transfer_team'] ?></a>
                        <a href="action/quit_team" class="red"><?= $t['advanced_quit_team'] ?></a>
                        <a href="action/delete_team" class="red"><?= $t['advanced_delete_team'] ?></a>
                    </div>
                </section>
            </div>
        </div>
        
        <!-- Footer -->
        <?php include("../src/php/dashboard_footer.php");?>
        <script src="<?= $opensupport_link?>/src/js/main.js"></script>
    </main>
</body>
</html>