<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

require("../../config.php");
header('Content-Type: application/json');

try{
    $ticket_id = (int)($_POST['ticket_id'] ?? 0);
    $message_id = (int)($_POST['message_id'] ?? 0);
    $token = $_POST['token'] ?? '';

    // Vérification de sécurité pour le client
    $stmt = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_id = :id AND tickets_token = :token LIMIT 1");
    $stmt->execute(['id' => $ticket_id, 'token' => $token]);
    if (!$stmt->fetch()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Unauthorized access.']);
        exit;
    }

    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        echo json_encode(['success' => false, 'error' => 'No files.']);
        exit;
    }

    $baseStorage = $opensupport_storage_dir ?? (__DIR__ . '/../../storage/attachments/');
    $ticketDir = rtrim($baseStorage, '/') . '/' . $ticket_id . '/';
    if (!is_dir($ticketDir)) {
        mkdir($ticketDir, 0755, true);
    }

    $uploadedNames = [];
    $allowedMimes = ['image/jpeg', 'image/png', 'video/mp4', 'application/pdf'];
    $maxSize = ($opensupport_max_file_size ?? 10) * 1024 * 1024;
    $fileCount = count($_FILES['files']['name']);

    if ($fileCount > ($opensupport_max_files ?? 5)) {
        echo json_encode(['success' => false, 'error' => 'Maximum files number reached.']);
        exit;
    }

    for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) continue;
        if ($_FILES['files']['size'][$i] > $maxSize) continue;

        $tmpPath = $_FILES['files']['tmp_name'][$i];
        $mimeType = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);
        } elseif (function_exists('mime_content_type')) {
            $mimeType = mime_content_type($tmpPath);
        } else {
            $mimeType = $_FILES['files']['type'][$i];
        }

        if (!in_array($mimeType, $allowedMimes)) continue;

        $uniqueBase = uniqid('file_', true);

        // Images / .webp conversion
        if (in_array($mimeType, ['image/jpeg', 'image/png'])) {
            $converted = false;

            if (extension_loaded('gd') && function_exists('imagewebp')) {
                $finalName = $uniqueBase . '.webp';
                $destPath = $ticketDir . $finalName;
                
                $img = ($mimeType === 'image/jpeg') ? @imagecreatefromjpeg($tmpPath) : @imagecreatefrompng($tmpPath);
                if ($img) {
                    imagepalettetotruecolor($img);
                    imagealphablending($img, true);
                    imagesavealpha($img, true);
                    if (imagewebp($img, $destPath, 85)) {
                        $converted = true;
                        $uploadedNames[] = $finalName;
                    }
                    imagedestroy($img);
                }
            }
            // Fallback
            if (!$converted) {
                $ext = ($mimeType === 'image/jpeg') ? '.jpg' : '.png';
                $finalName = $uniqueBase . $ext;
                if (move_uploaded_file($tmpPath, $ticketDir . $finalName)) {
                    $uploadedNames[] = $finalName;
                }
            }
        }
        // Vidéo optimization
        elseif ($mimeType === 'video/mp4') {
            $finalName = $uniqueBase . '.mp4';
            $destPath = $ticketDir . $finalName;
            exec("ffmpeg -y -i " . escapeshellarg($tmpPath) . " -vcodec libx264 -crf 28 -preset fast -acodec aac " . escapeshellarg($destPath) . " 2>&1", $out, $returnCode);
            if ($returnCode !== 0 || !file_exists($destPath)) {
                move_uploaded_file($tmpPath, $destPath);
            }
            $uploadedNames[] = $finalName;
        } 
        // PDF
        elseif ($mimeType === 'application/pdf') {
            $finalName = $uniqueBase . '.pdf';
            $destPath = $ticketDir . $finalName;
            move_uploaded_file($tmpPath, $destPath);
            $uploadedNames[] = $finalName;
        }
    }

    if (!empty($uploadedNames)) {
        $stmtUp = $dbco->prepare("UPDATE messages SET messages_attachements_request = 0, messages_attachements = :json WHERE messages_id = :mid AND messages_ticket_id = :tid");
        $stmtUp->execute(['json' => json_encode($uploadedNames), 'mid'  => $message_id, 'tid'  => $ticket_id]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No valid file could be processed.']);
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'error'   => 'Server error : ' . $e->getMessage(),
        'file'    => $e->getFile(),
        'line'    => $e->getLine()
    ]);
}
?>