<?php require("config.php");
header_remove("X-Frame-Options");
header("Content-Security-Policy: frame-ancestors *");

// Language manager
$translations = [
    'fr' => [
        'title' => 'Politique de confidentialité',
        'team_policy' => 'Politique de confidentialité de',
        'no_team_policy' => 'Aucune politique de confidentialité n\'a été fournis.',
    ],
    'en' => [
        'title' => 'Privacy Policy',
        'team_policy' => 'Privacy Policy of',
        'no_team_policy' => 'No privacy policy has been provided.',
    ],
    'es' => [
        'title' => 'Política de privacidad',
        'team_policy' => 'política de privacidad de',
        'no_team_policy' => 'No se ha proporcionado ninguna política de privacidad.',
    ]
];

$t = $translations[$lang];

if(isset($_GET['env']) && $_GET['env']!=""){
    $env_slug = isset($_GET['env']) ? strip_tags($_GET['env']) : '';
    $stmt = $dbco->prepare("SELECT `teams_id`, `teams_name`, `teams_form_config`, `teams_logo`, `teams_color`, `teams_privacy_policy_url` FROM `teams` WHERE `teams_form_url` = :env AND `teams_deleted` = 0 LIMIT 1");
    $stmt->execute(['env' => $env_slug]);
    $team = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['title'] ?> - <?= htmlspecialchars($team['teams_name']) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
</head>
<body class="form">
    
    <div class="action_bar">
        <?php require("src/php/language_selector.php"); ?>
    </div>

    <main>
        <div class="box large">
            <header>
                <h1><?= $t['title'] ?></h1>
            </header>
            
            <?php if(isset($_GET['env']) && $_GET['env']!=""):?>
                <div class="privacy-section">
                    <h2><?= $team['teams_name'] ?></h2>
                    <?php if(isset($team['teams_privacy_policy_url']) && $team['teams_privacy_policy_url']!=""):?>
                        <a href="" target="_blank"><?= $t['team_policy'] ?> <?= $team['teams_name'] ?></a>
                    <?php else: ?>
                        <p><?= $t['no_team_policy'] ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="privacy-section">
                <h2>OpenSupport Privacy Policy & Data Architecture</h2>
                <h3>1. Open-Source Software Notice & Role Clarification</h3>
                <p>OpenSupport is a self-hosted, open-source customer support software platform. OpenSupport does not operate as a centralized cloud provider, does not centrally aggregate data across installations, and acts strictly as software deployed by independent operators. Under the General Data Protection Regulation (GDPR), the team or organization hosting and running this instance acts as the <strong>Data Controller</strong>. The software itself is provided "as is", built with privacy-by-design principles to assist operators in meeting regulatory requirements.</p>
                <h3>2. Collected Data & Processing Operations</h3>
                <p>When submitting support tickets or communicating via chat, the application processes the following data categories directly within the local database:</p>
                <ul>
                    <li><strong>Identity & Contact Details:</strong> Client first and last names, email address, and IP-related diagnostic metadata.</li>
                    <li><strong>Ticket Information:</strong> Ticket subjects, text descriptions, internal agent notes, assignment logs, and custom fields defined by the team.</li>
                    <li><strong>Conversations & Communications:</strong> Chat messages, timestamps, and customer feedback or survey ratings upon ticket closure.</li>
                    <li><strong>Submitted Attachments:</strong> Files uploaded through file requests (images converted to WebP format for data minimization and storage optimization, MP4 video recordings, and PDF documents).</li>
                </ul>
                <h3>3. Storage Security & File Access Restriction</h3>
                <p>Direct public HTTP access to the storage of all submitted attachments is strictly prohibited at the web server level. File retrieval, previewing, and downloading are exclusively channeled through a dedicated identity verification script. This gateway executes strict authorization checks on every request, allowing access solely to verified team members assigned to the ticket or to the client holding an authenticated ticket session token.</p>
                <h3>4. Permitted Actions & Responsibilities of Teams</h3>
                <p>Organizations operating on this instance are permitted to use processed data exclusively for customer support resolution, internal ticket handling, communication, and service evaluation. Operators are prohibited from:
                </p>
                <ul>
                    <li>Requesting, storing, or processing sensitive payment details (such as credit card numbers or banking credentials) through standard ticket fields or attachments.</li>
                    <li>Sharing customer details or attachment access tokens with unverified third parties.</li>
                    <li>Retaining personal inquiries or chat logs beyond the necessity of resolving the initial service request.</li>
                </ul>
                <h3>5. Retention, Anonymization & The Right to Erasure</h3>
                <p>In alignment with Article 17 of the GDPR (Right to Erasure), the platform provides automated tools for instance administrators to:
                </p>
                <ul>
                    <li><strong>Anonymize Closed Inquiries:</strong> Automatically wipe personally identifiable information (client names, email addresses, message contents) from tickets closed for more than 30 days.</li>
                    <li><strong>Automated Attachment Erasure:</strong> Permanently purge physical uploaded files and server directories associated with closed tickets after 30 days.</li>
                    <li><strong>Team Lifecycle Management:</strong> When an entire team space is deleted, an immediate soft-deletion cuts external API keys and form endpoints, closing pending tickets and scheduling an irreversible database and asset purge within 30 days.</li>
                </ul>
                <h3>6. Data Subject Rights</h3>
                <p>Individuals who have submitted a support ticket retain the right to request access to, rectification of, or complete deletion of their personal records. Because each OpenSupport environment is managed autonomously, data subject requests must be directed to the designated organization (Data Controller) handling your ticket via the contact channels provided in their support form.</p>
            </div>
        </div>
    </main>
    
    <footer>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>src/js/main.js"></script>
</body>
</html>