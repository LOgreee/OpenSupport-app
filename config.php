<?php 
    //PHP error display
    /*ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);*/

    session_start();
    define('OPEN_SUPPORT_INIT', true);
    define('OPENSUPPORT_DEV_MODE', true);

    $installFile = __DIR__ . '/install.php';
    $currentScript = basename($_SERVER['SCRIPT_FILENAME'] ?? '');

    // Install verification
    if (file_exists($installFile) && (!defined('OPENSUPPORT_DEV_MODE') || !OPENSUPPORT_DEV_MODE)) {
        if ($currentScript !== 'install.php') {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $uriDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            header("Location: {$protocol}://{$host}/install");
            exit();
        }
    }

    $envFile = __DIR__ . '/config.env.php';
    if (!file_exists($envFile)) {
        if ($currentScript !== 'install.php') {
            exit("The OpenSupport application is not installed. Please run install.php.");
        }
    } else {
        $envConfig = require($envFile);

        // Software configuration
        date_default_timezone_set($envConfig['app']['timezone'] ?? 'Europe/Paris');
        $opensupport_link = rtrim($envConfig['app']['url'], '/');
        $opensupport_domain = $envConfig['app']['domain'];
        $opensupport_max_file_size = (int)($envConfig['app']['max_file_size'] ?? 10);
        $opensupport_max_files = (int)($envConfig['app']['max_files'] ?? 5);
        $opensupport_host_info = $envConfig['app']['host_info'] ?? [];

        //Data base connection informations
        $db_host = $envConfig['db']['host'];
        $db_port = $envConfig['db']['port'] ?? 3306;
        $db_name = $envConfig['db']['name'];
        $db_user = $envConfig['db']['user'];
        $db_pass = $envConfig['db']['pass'];

        try {
            $dbco = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false]);
        } catch (PDOException $e) {
            exit("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }


    //OLD DATA SETUP
    /*$opensupport_storage_dir = __DIR__ . '/up/attachments/';*/

    ///////////////////////////
    ///  Language Manager   ///
    ///////////////////////////

    $langs = ['fr', 'en', 'es'];
    $langs_name = ['Français', 'English', 'Español'];
    if (isset($_GET['lang']) && in_array($_GET['lang'], $langs)) $_SESSION['lang'] = $_GET['lang'];
    $lang = $_SESSION['lang'] ?? 'en';
    
    ////////////////////////////////////
    //FUNCTONS
    ////////////////////////////////////
    function dateformat($str){
        $year = substr($str, 0, 4);
        $month = substr($str, 5, 2);
        if($_COOKIE['TM_lang_save']=="fr"){
            if($month == "01"){
                $month = "Janvier";
            } else if($month == "02"){
                $month = "Février";
            } else if($month == "03"){
                $month = "Mars";
            } else if($month == "04"){
                $month = "Avril";
            } else if($month == "05"){
                $month = "Mai";
            } else if($month == "06"){
                $month = "Juin";
            } else if($month == "07"){
                $month = "Juillet";
            } else if($month == "08"){
                $month = "Août";
            } else if($month == "09"){
                $month = "Septembre";
            } else if($month == "10"){
                $month = "Octobre";
            } else if($month == "11"){
                $month = "Novembre";
            } else if($month == "12"){
                $month = "Décembre";
            } else {
                $month="";
            }
        } else {
            if($month == "01"){
                $month = "January";
            } else if($month == "02"){
                $month = "February";
            } else if($month == "03"){
                $month = "March";
            } else if($month == "04"){
                $month = "April";
            } else if($month == "05"){
                $month = "May";
            } else if($month == "06"){
                $month = "June";
            } else if($month == "07"){
                $month = "July";
            } else if($month == "08"){
                $month = "August";
            } else if($month == "09"){
                $month = "September";
            } else if($month == "10"){
                $month = "October";
            } else if($month == "11"){
                $month = "November";
            } else if($month == "12"){
                $month = "December";
            } else {
                $month="";
            }
        }
        return $month." ".$year;
    }

    function dateformatinput($str){
        $year = substr($str, -4);
        $month = substr($str, 0, -5);
        if($_COOKIE['TM_lang_save']=="fr"){
            if($month == "Janvier"){
                $month = "01";
            } else if($month == "Février"){
                $month = "02";
            } else if($month == "Mars"){
                $month = "03";
            } else if($month == "Avril"){
                $month = "04";
            } else if($month == "Mai"){
                $month = "05";
            } else if($month == "Juin"){
                $month = "06";
            } else if($month == "Juillet"){
                $month = "07";
            } else if($month == "Août"){
                $month = "08";
            } else if($month == "Septembre"){
                $month = "09";
            } else if($month == "Octobre"){
                $month = "10";
            } else if($month == "Novembre"){
                $month = "11";
            } else if($month == "Décembre"){
                $month = "12";
            } else {
                $month="";
            }
        } else {
            if($month == "January"){
                $month = "01";
            } else if($month == "February"){
                $month = "02";
            } else if($month == "March"){
                $month = "03";
            } else if($month == "Aprill"){
                $month = "04";
            } else if($month == "May"){
                $month = "05";
            } else if($month == "June"){
                $month = "06";
            } else if($month == "July"){
                $month = "07";
            } else if($month == "August"){
                $month = "08";
            } else if($month == "September"){
                $month = "09";
            } else if($month == "October"){
                $month = "10";
            } else if($month == "November"){
                $month = "11";
            } else if($month == "December"){
                $month = "12";
            } else {
                $month="";
            }
        }
        return $year."-".$month;
    }

    /**
     * Generate a random string made of lowercase letters, uppercase letters and numbers.
     *
     * @param int $characters Lenght of the generated string.
     * @return bool
     */
    function random_str($characters) {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxyzABCDEGHIJKLMNOPQRSTUVWXYZ0123456789";
        srand((double)microtime()*1000000);
        for($i=0; $i<$characters; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }

    /**
     * Verify if the current user has been logged in for less than 6 hours.
     *
     * @return bool
     */
    function connectionCheck(){
         if(isset($_SESSION['connected']) && $_SESSION['connected']==="true"){
            $now = new DateTime();
            $connectionDate = DateTime::createFromFormat('m/d/Y h:i:s a', $_SESSION['connection_datetime']);
            $date_int = $now->diff($connectionDate);
            $hours = ($date_int->days * 24) + ($date_int->h) + ($date_int->i / 60);
            if ($_SESSION['connection_datetime'] < $now && $hours < 6) {
                 return true;
             }else{
                header("Location: {$GLOBALS['opensupport_link']}/dashboard/logout");
                exit();
             }
         } else {
            header("Location: {$GLOBALS['opensupport_link']}/dashboard/login");
            exit();
         }
    };

    /**
     * Verify if current user have access to a specified team.
     *
     * @param int $team_id
     * @return bool
     */
    function verifyTeamAccess($team_id){
        $sth = $GLOBALS['dbco']->prepare("SELECT t.teams_id, IF(t.teams_owner = {$_SESSION['user_id']}, 'admin', 'user') AS is_owner FROM teams t LEFT JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id WHERE t.teams_id = {$team_id} AND t.teams_deleted = FALSE AND (t.teams_owner = {$_SESSION['user_id']} OR tm.teams_members_user_id = {$_SESSION['user_id']}) AND tm.teams_members_deleted=0 LIMIT 1;");
        $sth->execute();
        $resultat = $sth->fetchAll(PDO::FETCH_ASSOC);
        if (count($resultat)!=0){
            foreach ($resultat as $row) {
                $_SESSION['team_id'] = $row['teams_id'];
                $_SESSION['team_role'] = $row['is_owner'];
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verify if user is currently absent.
     *
     * @param int $user_id
     * @return bool
     */
    function isUserCurrentlyAbsent(int $user_id): bool {
        $stmt = $GLOBALS['dbco']->prepare("SELECT users_absence FROM users WHERE users_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);
        $jsonString = $stmt->fetchColumn();
        if (!$jsonString) {
            return false;
        }
        $absenceData = json_decode($jsonString, true);
        if (!is_array($absenceData) || empty($absenceData['enabled'])) {
            return false;
        }
        try {
            $now = new DateTime();
            $start = new DateTime($absenceData['start']);
            $end = new DateTime($absenceData['end']);
            return $now >= $start && $now <= $end;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Génère un email HTML complet OpenSupport
     *
     * @param array $options {
     *     @type array|null  $team              Données de l'équipe (teams_id, teams_name, teams_logo, teams_color, teams_form_url, teams_privacy_policy_url)
     *     @type string      $recipient_email   Email du destinataire
     *     @type string      $body_content      HTML principal à injecter
     *     @type string|null $privacy_token     Token du ticket pour le lien de gestion RGPD (optionnel)
     *     @type string      $subject           Sujet affiché dans le préheader
     * }
     * @return string
     */
    $email_translations = [
        'fr' => [
            'privacy_policy' => 'Politique de confidentialité',
            'manage_privacy' => 'Gérer mes données personnelles',
            'notice_sent_to' => 'Cet e-mail automatique a été envoyé à :recipient_email dans le cadre du traitement de votre demande de support.',
            'notice_gdpr' => 'Conformément à la réglementation RGPD, vous disposez d\'un droit d\'accès, de rectification et d\'effacement de vos données personnelles que vous pouvez exercer via le lien ci-dessus.<br>Merci de ne pas répondre directement à cet e-mail.',
            'powered_by' => 'Propulsé par'
        ],
        'en' => [
            'privacy_policy' => 'Privacy Policy',
            'manage_privacy' => 'Manage my personal data',
            'notice_sent_to' => 'This automated email was sent to :recipient_email as part of processing your support request.',
            'notice_gdpr' => 'In accordance with GDPR regulations, you have the right to access, rectify, and delete your personal data, which you can exercise via the link above.<br>Please do not reply directly to this email.',
            'powered_by' => 'Powered by'
        ],
        'es' => [
            'privacy_policy' => 'Política de privacidad',
            'manage_privacy' => 'Gestionar mis datos personales',
            'notice_sent_to' => 'Este correo electrónico automático fue enviado a :recipient_email como parte del trámite de su solicitud de soporte.',
            'notice_gdpr' => 'De conformidad con la normativa RGPD, tiene derecho a acceder, rectificar y suprimir sus datos personales, el cual puede ejercer a través del enlace anterior.<br>Por favor, no responda directamente a este correo electrónico.',
            'powered_by' => 'Desarrollado por'
        ]
    ];
    function renderEmailLayout(array $options): string {
        global $opensupport_link, $email_translations, $lang;
        $e = $email_translations[$lang] ?? $email_translations['en'];
        $team = $options['team'] ?? null;
        $recipientEmail = $options['recipient_email'] ?? '';
        $bodyContent = $options['body_content'] ?? '';
        $privacyToken = $options['privacy_token'] ?? null;
        $subject = $options['subject'] ?? 'OpenSupport Notification';
        $noticeSentTo = str_replace(
            ':recipient_email', 
            '<strong style="color: #64748b; font-weight: 600;">' . htmlspecialchars((string)$recipientEmail, ENT_QUOTES, 'UTF-8') . '</strong>', 
            $e['notice_sent_to']
        );
        $teamName = !empty($team['teams_name']) ? htmlspecialchars($team['teams_name']) : 'OpenSupport';
        $accentColor = !empty($team['teams_color']) ? htmlspecialchars($team['teams_color']) : '#635bff';
        $hasTeamLogo = !empty($team['teams_logo']) && $team['teams_logo'] == "1";
        $logoUrl = $hasTeamLogo ? rtrim($opensupport_link, '/') . '/up/teams/' . (int)$team['teams_id'] . '.webp' : rtrim($opensupport_link, '/') . '/src/opensupport_assets/opensupport_icon.svg';
        $privacyPolicyUrl = !empty($team['teams_privacy_policy_url']) ? htmlspecialchars($team['teams_privacy_policy_url']) : null;
        $privacyManageUrl = ($privacyToken && !empty($team['teams_form_url'])) ? rtrim($opensupport_link, '/') . '/form/' . htmlspecialchars($team['teams_form_url']) . '/privacy/' . htmlspecialchars($privacyToken) : null;
        
        ob_start();
        ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($subject) ?></title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 24px 0;">
        <tr>
            <td align="center">
                <!-- Wrapper Conteneur Principal -->
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    
                    <!-- HEADER -->
                    <tr>
                        <td align="center" style="padding: 24px 20px; border-bottom: 1px solid #e2e8f0;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="middle" style="padding-right: 12px;">
                                        <img src="<?= $logoUrl ?>" alt="<?= $teamName ?>" height="38" style="display: block; max-height: 38px; height: auto; border: 0;">
                                    </td>
                                    <td valign="middle">
                                        <span style="font-size: 18px; font-weight: 700; color: #0f172a; letter-spacing: -0.02em;">
                                            <?= $teamName ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 32px 28px; font-size: 15px; line-height: 24px; color: #334155;">
                            <?= $bodyContent ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" style="padding: 24px 20px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px;">
                                <?php if ($privacyPolicyUrl || $privacyManageUrl): ?>
                                    <tr>
                                        <td align="center" style="padding-bottom: 14px; font-size: 12px; line-height: 18px;">
                                            <?php if ($privacyPolicyUrl): ?>
                                                <a href="<?= $privacyPolicyUrl ?>" target="_blank" rel="noopener noreferrer" style="color: #635bff; text-decoration: underline; font-weight: 500;"><?= $e['privacy_policy'] ?> </a>
                                            <?php endif; ?>

                                            <?php if ($privacyPolicyUrl && $privacyManageUrl): ?>
                                                <span style="color: #cbd5e1; margin: 0 8px;">&bull;</span>
                                            <?php endif; ?>

                                            <?php if ($privacyManageUrl): ?>
                                                <a href="<?= $privacyManageUrl ?>" target="_blank" rel="noopener noreferrer" style="color: #635bff; text-decoration: underline; font-weight: 500;"><?= $e['manage_privacy'] ?> </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                <?php if (!empty($recipientEmail)): ?>
                                    <tr>
                                        <td align="center" style="font-size: 11px; line-height: 16px; color: #94a3b8; padding-bottom: 10px;"><?= $noticeSentTo ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td align="center" style="font-size: 10px; color: #cbd5e1;">&copy; <?= date('Y') ?> <?= $teamName ?> &bull; <?= $e['powered_by'] ?> OpenSupport</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
        <?php
        return ob_get_clean();
    }

/**
 * Send optimised transactionnal email in HTML + text (multipart/alternative).
 *
 * @param string      $to          Email address of addressee
 * @param string      $subject     Message subject
 * @param string      $htmlMessage HTML message
 * @param string|null $senderName  Display name of sender (by défaut : 'OpenSupport')
 * @return bool                    true if accepted by local server
 */
function sendOpenSupportMail(string $to, string $subject, string $htmlMessage, ?string $senderName = null): bool {
    global $opensupport_domain;

    $fromEmail = "noreply@" . $opensupport_domain;
    $displayName = (!empty($senderName) && trim($senderName) !== '') ? trim($senderName) : 'OpenSupport';

    // Text version
    $textBody = strip_tags(str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\r\n", $htmlMessage));
    $textBody = html_entity_decode($textBody, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $textBody = trim(preg_replace("/[\r\n]+/", "\r\n", $textBody));

    $boundary = "----=_NextPart_" . md5(uniqid((string)time(), true));
    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $textBody . "\r\n\r\n";
    $body .= "--{$boundary}\r\n";
    $body .= "Content-Type: text/html; charset=UTF-8\r\n";
    $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
    $body .= $htmlMessage . "\r\n\r\n";
    $body .= "--{$boundary}--";

    $messageId = "<" . gmdate('YmdHis') . "." . bin2hex(random_bytes(8)) . "@" . $opensupport_domain . ">";
    $headers = [];
    $headers[] = "Date: " . date('r');
    $headers[] = "Message-ID: {$messageId}";
    $headers[] = "From: =?UTF-8?B?" . base64_encode($displayName) . "?= <{$fromEmail}>";
    $headers[] = "Reply-To: {$fromEmail}";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-Type: multipart/alternative; boundary=\"{$boundary}\"";
    $headers[] = "X-Mailer: OpenSupport Mailer v1.0";
    $headers[] = "Auto-Submitted: auto-generated";
    $headers[] = "Precedence: bulk";
    $rawHeaders = implode("\r\n", $headers);
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    return @mail($to, $encodedSubject, $body, $rawHeaders, "-f" . $fromEmail);
}


///////////////////////////
///        SSO          ///
///////////////////////////

// Redirect URL
define('REDIRECT_URI', "{$GLOBALS['opensupport_link']}/dashboard/sso_callback.php"); 

// Google ID
define('GOOGLE_CLIENT_ID', 'TON_CLIENT_ID_GOOGLE');
define('GOOGLE_CLIENT_SECRET', 'TON_CLIENT_SECRET_GOOGLE');

// Microsoft ID
define('MICROSOFT_CLIENT_ID', 'TON_CLIENT_ID_MICROSOFT');
define('MICROSOFT_CLIENT_SECRET', 'TON_CLIENT_SECRET_MICROSOFT');

//cURL functions
function post_curl($url, $data, $is_form_urlencoded = false) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    if ($is_form_urlencoded) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    }
    
    // Désactive la vérification SSL en local si tu n'as pas de certificat configuré
    // À retirer en production !
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

function get_curl($url, $access_token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Accept: application/json'
    ]);
    
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
    }
    
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}
?>