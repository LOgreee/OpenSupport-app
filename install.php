<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('OPEN_SUPPORT_INIT', true);

$envFile = __DIR__ . '/config.env.php';
$alreadyConfigured = file_exists($envFile);
$step = (int)($_GET['step'] ?? 1);
$error = '';
if ($alreadyConfigured && $step < 5) {
    $step = 5;
} else {
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (preg_match('#/install/?$#', $requestUri)) {
        header("Location: " . rtrim($requestUri, '/') . "/1", true, 302);
        exit();
    }
}

/**
 * Checks whether a specific email mailbox exists on its mail server via SMTP.
 */
function verifyMailboxExists(string $email): bool {
    $domain = substr(strrchr($email, "@"), 1);
    if (empty($domain)) return false;

    // Retrieve MX records
    $mxHosts = [];
    $mxWeights = [];
    if (!getmxrr($domain, $mxHosts, $mxWeights) || empty($mxHosts)) {
        $mxHosts = [$domain];
    }
    array_multisort($mxWeights, $mxHosts);

    // Attempt connection to the highest priority available MX
    $connectTimeout = 4;
    $socket = false;
    foreach ($mxHosts as $host) {
        $socket = @fsockopen($host, 25, $errno, $errstr, $connectTimeout);
        if ($socket) break;
    }
    if (!$socket) return false;

    stream_set_timeout($socket, $connectTimeout);
    $response = fgets($socket);
    if (!str_starts_with($response, '220')) {
        fclose($socket);
        return false;
    }

    // SMTP Handshake
    $localhost = $_SERVER['SERVER_NAME'] ?? 'localhost';
    fputs($socket, "HELO {$localhost}\r\n");
    fgets($socket);

    fputs($socket, "MAIL FROM: <test@{$domain}>\r\n");
    fgets($socket);

    // Verify recipient address
    fputs($socket, "RCPT TO: <{$email}>\r\n");
    $rcptResponse = fgets($socket);

    fputs($socket, "QUIT\r\n");
    fclose($socket);

    return (str_starts_with($rcptResponse, '250') || str_starts_with($rcptResponse, '251'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 1: License
    if ($step === 1) {
        if (!isset($_POST['accept_license'])) {
            $error = "You must accept the license terms to continue.";
        } else {
            header("Location: 2");
            exit;
        }
    }

    // Step 2: DB connection
    elseif ($step === 2) {
        $db_host = trim($_POST['db_host'] ?? '127.0.0.1');
        $db_port = (int)($_POST['db_port'] ?? 3306);
        $db_name = trim($_POST['db_name'] ?? '');
        $db_user = trim($_POST['db_user'] ?? '');
        $db_pass = $_POST['db_pass'] ?? '';

        if (empty($db_name) || empty($db_user)) {
            $error = "Please provide both the database name and username.";
        } else {
            try {
                $testPdo = new PDO("mysql:host={$db_host};port={$db_port};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);

                // Complete SQL schema matching the application architecture
                $sqlSchema = "
                SET FOREIGN_KEY_CHECKS = 0;
                DROP TABLE IF EXISTS `messages`, `tickets`, `teams_members`, `teams`, `users`;
                SET FOREIGN_KEY_CHECKS = 1;

                CREATE TABLE `users` (
                    `users_id` INT AUTO_INCREMENT PRIMARY KEY,
                    `users_email` VARCHAR(255) NOT NULL UNIQUE,
                    `users_first_name` VARCHAR(100) NULL DEFAULT NULL,
                    `users_last_name` VARCHAR(100) NULL DEFAULT NULL,
                    `users_password` VARCHAR(255) NULL DEFAULT NULL,
                    `users_password_modify_token` VARCHAR(255) DEFAULT NULL,
                    `users_last_password_date` DATETIME DEFAULT NULL,
                    `users_absence` JSON DEFAULT NULL,
                    `users_admin` TINYINT(1) DEFAULT 0,
                    `users_invitation_token` VARCHAR(255) DEFAULT NULL,
                    `users_last_connexion` DATETIME DEFAULT NULL,
                    `users_creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `users_deleted` TINYINT(1) DEFAULT 0,
                    `users_deleted_at` DATETIME DEFAULT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE `teams` (
                    `teams_id` INT AUTO_INCREMENT PRIMARY KEY,
                    `teams_name` VARCHAR(150) NOT NULL,
                    `teams_privacy_policy_url` TEXT DEFAULT NULL,
                    `teams_form_url` VARCHAR(255) DEFAULT NULL UNIQUE,
                    `teams_form_config` JSON DEFAULT NULL,
                    `teams_messages_template` JSON DEFAULT NULL,
                    `teams_logo` TINYINT(1) DEFAULT 0,
                    `teams_color` VARCHAR(20) DEFAULT '#4f46e5',
                    `teams_owner` INT NOT NULL,
                    `teams_api_token` TEXT DEFAULT NULL,
                    `teams_creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `teams_deleted` TINYINT(1) DEFAULT 0,
                    `teams_deleted_at` DATETIME DEFAULT NULL,
                    FOREIGN KEY (`teams_owner`) REFERENCES `users`(`users_id`) ON DELETE RESTRICT
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE `teams_members` (
                    `teams_members_id` INT AUTO_INCREMENT PRIMARY KEY,
                    `teams_members_user_id` INT NOT NULL,
                    `teams_members_team_id` INT NOT NULL,
                    `teams_members_position` VARCHAR(100) DEFAULT 'Support',
                    `teams_members_groups` TEXT DEFAULT NULL,
                    `teams_members_join_date` DATETIME DEFAULT NULL,
                    `teams_members_invite_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `teams_members_deleted` TINYINT(1) DEFAULT 0,
                    FOREIGN KEY (`teams_members_user_id`) REFERENCES `users`(`users_id`) ON DELETE CASCADE,
                    FOREIGN KEY (`teams_members_team_id`) REFERENCES `teams`(`teams_id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE `tickets` (
                    `tickets_id` INT AUTO_INCREMENT PRIMARY KEY,
                    `tickets_teams` INT NOT NULL,
                    `tickets_token` VARCHAR(255) NOT NULL UNIQUE,
                    `tickets_email` VARCHAR(255) NOT NULL,
                    `tickets_first_name` VARCHAR(100) NOT NULL,
                    `tickets_last_name` VARCHAR(100) NOT NULL,
                    `tickets_subject` VARCHAR(255) NOT NULL,
                    `tickets_source` VARCHAR(100) NOT NULL DEFAULT 'OpenSupport form',
                    `tickets_description` TEXT NOT NULL,
                    `tickets_additionnal_fields` JSON DEFAULT NULL,
                    `tickets_assigned_to` INT DEFAULT NULL,
                    `tickets_status` INT NOT NULL DEFAULT 0,
                    `tickets_priority` INT NOT NULL DEFAULT 0,
                    `tickets_admin_notes` TEXT DEFAULT NULL,
                    `tickets_rating` INT DEFAULT NULL,
                    `tickets_feedback` TEXT DEFAULT NULL,
                    `tickets_client_last_visit_date` DATETIME DEFAULT NULL,
                    `tickets_creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `tickets_closing_date` DATETIME DEFAULT NULL,
                    `tickets_feedback_date` DATETIME DEFAULT NULL,
                    `tickets_deleted_at` DATETIME DEFAULT NULL,
                    FOREIGN KEY (`tickets_teams`) REFERENCES `teams`(`teams_id`) ON DELETE CASCADE,
                    FOREIGN KEY (`tickets_assigned_to`) REFERENCES `users`(`users_id`) ON DELETE SET NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

                CREATE TABLE `messages` (
                    `messages_id` INT AUTO_INCREMENT PRIMARY KEY,
                    `messages_ticket_id` INT NOT NULL,
                    `messages_sender_type` INT NOT NULL,
                    `messages_sender_id` INT DEFAULT NULL,
                    `messages_content` TEXT DEFAULT NULL,
                    `messages_attachements_request` TINYINT(1) DEFAULT 0,
                    `messages_attachements` JSON DEFAULT NULL,
                    `messages_creation_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                    `messages_deleted_at` DATETIME DEFAULT NULL,
                    FOREIGN KEY (`messages_ticket_id`) REFERENCES `tickets`(`tickets_id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                ";
                $testPdo->exec($sqlSchema);
                $_SESSION['setup_db'] = ['host' =>$db_host, 'port' => $db_port, 'name' =>$db_name, 'user' => $db_user, 'pass' =>$db_pass];
                header("Location: 3");
                exit;

            } catch (PDOException $e) {
                $error = "Database connection or SQL execution error: " . $e->getMessage();
            }
        }
    }

    // Step 3: Global parameters
    elseif ($step === 3) {
        $domain = trim($_POST['app_domain'] ?? '');
        $url = rtrim(trim($_POST['app_url'] ?? ''), '/');
        $tz = trim($_POST['app_tz'] ?? 'Europe/Paris');
        $max_file_size = (int)($_POST['max_file_size'] ?? 10);
        $max_files = (int)($_POST['max_files'] ?? 5);
        $host_name = trim($_POST['host_name'] ?? '');
        $host_details = trim($_POST['host_details'] ?? '');

        if (empty($domain) || empty($url)) {
            $error = "Domain name and full URL are required.";
        } else {
            $_SESSION['setup_app'] = [
                'domain' => $domain,
                'url' => $url,
                'timezone' => $tz,
                'max_file_size' => $max_file_size,
                'max_files' => $max_files,
                'host_info' => [
                    'provider' => $host_name,
                    'details'  => $host_details
                ]
            ];
            header("Location: 4");
            exit;
        }
    }

    // Step 4: Administrator account
    elseif ($step === 4) {
        $first_name = trim($_POST['admin_firstname'] ?? '');
        $last_name = trim($_POST['admin_lastname'] ?? '');
        $email = filter_var(trim($_POST['admin_email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $pass =$_POST['admin_password'] ?? '';
        $pass_confirm =$_POST['admin_password_confirm'] ?? '';

        if (!$email || empty($first_name) || empty($last_name)) {
            $error = "Please enter a valid email address and your full name.";
        } elseif (strlen($pass) < 8 || $pass !== $pass_confirm) {
            $error = "Passwords must match and be at least 8 characters long.";
        } else {
            $dbCreds =$_SESSION['setup_db'] ?? null;
            $appConfig =$_SESSION['setup_app'] ?? null;

            if (!$dbCreds || !$appConfig) {
                $error = "Missing configuration data. Please restart the installation process.";
            } else {
                try {
                    $pdo = new PDO("mysql:host={$dbCreds['host']};port={$dbCreds['port']};dbname={$dbCreds['name']};charset=utf8mb4", $dbCreds['user'],$dbCreds['pass'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);
                    $hash = password_hash($pass, PASSWORD_DEFAULT);
                    $stmtUser =$pdo->prepare("INSERT INTO `users` (`users_email`, `users_first_name`, `users_last_name`, `users_password`, `users_admin`, `users_creation_date`) VALUES (:email, :fn, :ln, :pass, 1, NOW())");
                    $stmtUser->execute([
                        'email' => $email,
                        'fn'    => $first_name,
                        'ln'    => $last_name,
                        'pass'  => $hash
                    ]);
                    $dirs = [
                        __DIR__ . '/up/teams',
                        __DIR__ . '/storage/attachments'
                    ];
                    foreach ($dirs as $d) {
                        if (!is_dir($d)) {
                            @mkdir($d, 0777, true);
                        }
                    }
                    $envContent = "<?php\n";
                    $envContent .= "defined('OPEN_SUPPORT_INIT') or exit('Access denied');\n\n";
                    $envContent .= "return " . var_export([
                        'db' => $dbCreds,
                        'app' => $appConfig
                    ], true) . ";\n";

                    file_put_contents($envFile,$envContent);

                    // Cron job configuration
                    function tryInstallCronJob(string $scriptPath): bool {
                        if (!function_exists('exec') || in_array('exec', array_map('trim', explode(',', ini_get('disable_functions'))))) {
                            return false;
                        }
                        $phpBinary = PHP_BINARY ?: '/usr/bin/php';
                        $cronCommand = "0 0 * * * {$phpBinary} " . escapeshellarg($scriptPath) . " > /dev/null 2>&1";
                        $currentCron = [];
                        @exec('crontab -l 2>/dev/null', $currentCron,$exitCode);
                        $currentCronStr = implode("\n", $currentCron);
                        if (strpos($currentCronStr, $scriptPath) !== false) {                             return true;                         }$newCronStr = trim($currentCronStr . "\n" . $cronCommand) . "\n";
                        $tmpFile = tempnam(sys_get_temp_dir(), 'cron_');
                        file_put_contents($tmpFile,$newCronStr);
                        @exec('crontab ' . escapeshellarg($tmpFile) . ' 2>&1', $output,$installCode);
                        @unlink($tmpFile);
                        return ($installCode === 0);                     }$cronScriptPath = __DIR__ . '/cron/data_cleaner.php';
                    $cronInstalled = tryInstallCronJob($cronScriptPath);

                    // Verification of the sender mailbox noreply@domain.com
                    $noreplyEmail = 'noreply@' . ($appConfig['domain'] ?? '');$_SESSION['setup_noreply_check'] = [
                        'email' => $noreplyEmail,
                        'exists' => verifyMailboxExists($noreplyEmail)
                    ];
                    $_SESSION['setup_cron_installed'] =$cronInstalled;

                    unset($_SESSION['setup_db'], $_SESSION['setup_app']);
                    header("Location: 5");
                    exit;

                } catch (Exception $e) {$error = "Error during setup completion: " . $e->getMessage();
                }
            }
        }
    }
}
$cronInstalled =$_SESSION['setup_cron_installed'] ?? false;
$noreplyCheck =$_SESSION['setup_noreply_check'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport Setup</title>
    <link rel="icon" type="image/x-icon" href="../src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="../src/css/main.css">
    <style>
        :root { --primary: #4f46e5; --bg: #f8fafc; --card: #ffffff; --border: #e2e8f0; --text: #0f172a; }
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 40px 20px; display: flex; justify-content: center; }
        .wizard-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; width: 100%; max-width: 640px; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        h1 { margin: 0 0 8px 0; font-size: 22px; font-weight: 700; color: var(--primary); }
        h2 { margin: 0 0 4px 0; font-size: 1.2em; font-weight: 700; color: var(--primary); }
        .steps-bar { display: flex; gap: 8px; margin: 20px 0 28px 0; }
        .step-pill { flex: 1; height: 5px; background: var(--border); border-radius: 4px; }
        .step-pill.active { background: var(--primary); }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        textarea { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; margin-bottom: 16px; font-family: inherit; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .alert { background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px; font-size: 13px; margin-bottom: 20px; border-radius: 4px; }
        .alert-warning { background: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; padding: 14px; font-size: 13px; margin-bottom: 15px; border-radius: 4px; }
        .lock-box { background: #fffbeb; border: 1px solid #f59e0b; padding: 24px; border-radius: 8px; text-align: center; }
        .code-block { font-family: monospace; background: #f1f5f9; padding: 8px 12px; border-radius: 4px; font-size: 13px; display: inline-block; margin: 10px 0; }
    </style>
</head>
<body>

<div class="wizard-card">
    <img src="../src/opensupport_assets/opensupport_logo.svg" alt="OpenSupport Logo" style="height:2em;">

    <div class="steps-bar">
        <div class="step-pill <?= $step >= 1 ? 'active' : '' ?>"></div>
        <div class="step-pill <?= $step >= 2 ? 'active' : '' ?>"></div>
        <div class="step-pill <?= $step >= 3 ? 'active' : '' ?>"></div>
        <div class="step-pill <?= $step >= 4 ? 'active' : '' ?>"></div>
        <div class="step-pill <?= $step >= 5 ? 'active' : '' ?>"></div>
    </div>

    <?php if ($error): ?>
        <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <?php if ($step === 1): ?>
        <h2>1. License & Prerequisites</h2>
        <p>This software requires the following environment to run:</p>
        <textarea readonly style="resize: none; font-size: 11px; background: #f8fafc; margin-top: 1em;">
- PHP server (version 8.1 or higher)
- MySQL / MariaDB database</textarea>
        <hr>
        <p>This software is distributed under the terms of the <a href="https://polyformproject.org/licenses/noncommercial/1.0.0" target="_blank"><strong>PolyForm Noncommercial 1.0.0</strong></a> license.</p>
        <textarea rows="16" readonly style="resize: none; font-size: 11px; background: #f8fafc; margin-top: 1em;">
PolyForm Noncommercial License 1.0.0

<https://polyformproject.org/licenses/noncommercial/1.0.0>

1. Acceptance

In order to get any license under these terms, you must agree
to them as both strict obligations and conditions to all
your licenses.

2. Copyright License

The licensor grants you a copyright license for the
software to do everything you might do with the software
that would otherwise infringe the licensor's copyright
in it for any permitted purpose.  However, you may
only distribute the software according to [Distribution
License](#distribution-license) and make changes or new works
based on the software according to [Changes and New Works
License](#changes-and-new-works-license).

3. Distribution License

The licensor grants you an additional copyright license
to distribute copies of the software.  Your license
to distribute covers distributing the software with
changes and new works permitted by [Changes and New Works
License](#changes-and-new-works-license).

4. Notices

You must ensure that anyone who gets a copy of any part of
the software from you also gets a copy of these terms or the
URL for them above, as well as copies of any plain-text lines
beginning with `Required Notice:` that the licensor provided
with the software.  For example:

> Required Notice: Copyright Thibault Morisse (https://github.com/LOgreee)

5. Changes and New Works License

The licensor grants you an additional copyright license to
make changes and new works based on the software for any
permitted purpose.

6. Patent License

The licensor grants you a patent license for the software that
covers patent claims the licensor can license, or becomes able
to license, that you would infringe by using the software.

7. Noncommercial Purposes

Any noncommercial purpose is a permitted purpose.

8. Personal Uses

Personal use for research, experiment, and testing for
the benefit of public knowledge, personal study, private
entertainment, hobby projects, amateur pursuits, or religious
observance, without any anticipated commercial application,
is use for a permitted purpose.

9. Noncommercial Organizations

Use by any charitable organization, educational institution,
public research organization, public safety or health
organization, environmental protection organization,
or government institution is use for a permitted purpose
regardless of the source of funding or obligations resulting
from the funding.

10. Fair Use

You may have "fair use" rights for the software under the
law. These terms do not limit them.

11. No Other Rights

These terms do not allow you to sublicense or transfer any of
your licenses to anyone else, or prevent the licensor from
granting licenses to anyone else.  These terms do not imply
any other licenses.

12. Patent Defense

If you make any written claim that the software infringes or
contributes to infringement of any patent, your patent license
for the software granted under these terms ends immediately. If
your company makes such a claim, your patent license ends
immediately for work on behalf of your company.

13. Violations

The first time you are notified in writing that you have
violated any of these terms, or done anything with the software
not covered by your licenses, your licenses can nonetheless
continue if you come into full compliance with these terms,
and take practical steps to correct past violations, within
32 days of receiving notice.  Otherwise, all your licenses
end immediately.

14. No Liability

***As far as the law allows, the software comes as is, without
any warranty or condition, and the licensor will not be liable
to you for any damages arising out of these terms or the use
or nature of the software, under any kind of legal claim.***

15. Definitions

The **licensor** is the individual or entity offering these
terms, and the **software** is the software the licensor makes
available under these terms.

**You** refers to the individual or entity agreeing to these
terms.

**Your company** is any legal entity, sole proprietorship,
or other kind of organization that you work for, plus all
organizations that have control over, are under the control of,
or are under common control with that organization.  **Control**
means ownership of substantially all the assets of an entity,
or the power to direct its management and policies by vote,
contract, or otherwise.  Control can be direct or indirect.

**Your licenses** are all the licenses granted to you for the
software under these terms.

**Use** means anything you do with the software requiring one
of your licenses.
        </textarea>

        <form method="POST">
            <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 20px;">
                <input type="checkbox" name="accept_license" id="lic" style="width: auto; margin: 0;" required>
                <label for="lic" style="margin: 0; font-weight: normal;">I accept the terms of the PolyForm Noncommercial 1.0.0 license</label>
            </div>
            <button type="submit" class="btn">Continue ➔</button>
        </form>

    <?php elseif ($step === 2): ?>
        <h2>2. Database Connection</h2>
        <p>Enter your MySQL credentials. The complete schema and tables will be initialized automatically.</p>
        <form method="POST">
            <div class="grid-2">
                <div>
                    <label>MySQL Host</label>
                    <input type="text" name="db_host" value="127.0.0.1" required>
                </div>
                <div>
                    <label>Port</label>
                    <input type="number" name="db_port" value="3306" required>
                </div>
            </div>
            <div>
                <label>Database Name</label>
                <input type="text" name="db_name" placeholder="opensupport" required>
            </div>
            <div class="grid-2">
                <div>
                    <label>MySQL Username</label>
                    <input type="text" name="db_user" required>
                </div>
                <div>
                    <label>MySQL Password</label>
                    <input type="password" name="db_pass">
                </div>
            </div>
            <button type="submit" class="btn">Test Connection & Initialize Tables ➔</button>
        </form>

    <?php elseif ($step === 3): ?>
        <h2>3. Instance Configuration</h2>
        <form method="POST">
            <div class="grid-2">
                <div>
                    <label>Domain Name</label>
                    <input type="text" name="app_domain" value="<?= htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'domain.com') ?>" required>
                </div>
                <div>
                    <label>Timezone</label>
                    <select id="app_tz" name="app_tz" required>
                        <?php
                        $currentTimezone = 'Europe/Paris';$timezones = DateTimeZone::listIdentifiers();
                        foreach ($timezones as $tz) {$selected = ($tz ===$currentTimezone) ? ' selected' : '';
                            echo "<option value=\"{$tz}\"{$selected}>{$tz}</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div>
                <label>Full Application URL (without trailing slash)</label>
                <input type="url" name="app_url" value="<?= ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost') ?>" required>
            </div>
            <div class="grid-2">
                <div>
                    <label>Max Size Per Attachment (MB)</label>
                    <input type="number" name="max_file_size" value="10" min="1" max="100" required>
                </div>
                <div>
                    <label>Max Files Per Request</label>
                    <input type="number" name="max_files" value="5" min="1" max="20" required>
                </div>
            </div>
            <div class="grid-2">
                <div>
                    <label>Hosting Provider (Legal Notice)</label>
                    <input type="text" name="host_name" placeholder="e.g., PlanetHoster, AWS, Hetzner">
                </div>
                <div>
                    <label>Hosting Address / Contact Details</label>
                    <input type="text" name="host_details" placeholder="e.g., 4416 Louis-B.-Mayer, Laval, QC, Canada">
                </div>
            </div>
            <button type="submit" class="btn">Next ➔</button>
        </form>

    <?php elseif ($step === 4): ?>
        <h2>4. Primary Administrator Account</h2>
        <p style="font-size: 13px; color: #64748b;">This account will have system-level administrative permissions on the instance.</p>
        <form method="POST">
            <div class="grid-2">
                <div>
                    <label>First Name</label>
                    <input type="text" name="admin_firstname" required>
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" name="admin_lastname" required>
                </div>
            </div>
            <div>
                <label>Email Address</label>
                <input type="email" name="admin_email" required>
            </div>
            <div class="grid-2">
                <div>
                    <label>Password (Min. 8 characters)</label>
                    <input type="password" name="admin_password" required minlength="8">
                </div>
                <div>
                    <label>Confirm Password</label>
                    <input type="password" name="admin_password_confirm" required minlength="8">
                </div>
            </div>
            <button type="submit" class="btn">Finish Installation</button>
        </form>

    <?php elseif ($step === 5): ?>
        <h2>Installation Complete!</h2>

        <?php if ($noreplyCheck && !$noreplyCheck['exists']): ?>
            <div class="alert-warning">
                <strong style="display: block; font-size: 14px; margin-bottom: 4px;">
                    ⚠️ Recommendation: Missing System Outbound Mailbox (<?= htmlspecialchars($noreplyCheck['email']) ?>)
                </strong>
                <p style="margin: 0; font-size: 13px; line-height: 1.5;">
                    The system mailbox <strong><?= htmlspecialchars($noreplyCheck['email']) ?></strong> could not be verified on your mail server. Transactional notifications (invitations, password resets, ticket updates) may be rejected or classified as spam by major email providers (Gmail, Outlook).
                </p>
                <p style="margin: 8px 0 0 0; font-size: 12px;">
                    👉 <strong>Recommendation:</strong> Create the email account or forwarder for <strong><?= htmlspecialchars($noreplyCheck['email']) ?></strong> in your hosting control panel and configure SPF/DKIM records.
                </p>
            </div>
        <?php endif; ?>

        <div class="lock-box" style="margin: 15px 0;">
            <?php if ($cronInstalled): ?>
                <p style="color: #15803d; font-size: 13px; margin: 0;">
                    ✅ The daily GDPR data compliance Cron job has been installed successfully.
                </p>
            <?php else: ?>
                <p style="color: #b45309; font-size: 13px; margin: 0;">
                    ⚠️ Unable to register the Cron job automatically (insufficient permissions).
                </p>
                <p style="font-size: 13px; margin: 8px 0 0 0;">
                    Please add the following entry manually to your crontab (executed every night at midnight):
                </p>
                <div class="code-block">0 0 * * * /usr/bin/php <?= htmlspecialchars(__DIR__ . '/cron/data_cleaner.php') ?> > /dev/null 2>&1</div>
            <?php endif; ?>
        </div>

        <div class="lock-box">
            <h3 style="color: #b45309; margin-top: 0;">Security: File Removal Required</h3>
            <p style="font-size: 14px;">The instance configuration and database structure have been set up successfully.</p>
            <p style="font-size: 14px;">To unlock access to OpenSupport, you must <strong>manually delete the installer script</strong> on your server:</p>
            <div class="code-block">rm <?= htmlspecialchars(__FILE__) ?></div>
            <p style="font-size: 13px; color: #78350f; margin-bottom: 0;">As long as this file remains in the directory, the application remains locked.</p>
        </div>

        <div style="margin-top: 20px; text-align: center;">
            <a href="../" class="btn" style="display: block; text-decoration: none;">Verify and Access Application</a>
        </div>
    <?php endif; ?>

</div>

</body>
</html>