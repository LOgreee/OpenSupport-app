<?php require("../config.php");
connectionCheck();

// Language manager
$translations = [
    'fr' => [
        'title' => 'Compte',
        'back' => 'Retour',
        'error_generic' => 'Une erreur est survenue, veuillez réessayer.',
        'error_password_incorrect' => 'Mot de passe incorrect.',
        'error_fill_all_fields' => 'Veuillez remplir tous les champs.',
        'error_password_confirm_required' => 'Veuillez saisir votre mot de passe pour confirmer.',
        'error_team_not_found' => 'Équipe introuvable.',
        'error_action_refused_not_owner' => 'Action refusée : vous n\'êtes pas le propriétaire de cette équipe.',
        'error_team_delete_failed' => 'Une erreur est survenue lors de la suppression de l\'équipe.',
        'error_team_export_failed' => 'Une erreur est survenue lors de l\'export des données : ',
        'error_team_anonymize_failed' => 'Une erreur est survenue lors de l\'anonymisation : ',
        'error_team_delete_attachments_failed' => 'Une erreur est survenue lors de la suppression des pièces jointes : ',
        'error_account_delete_type_delete' => 'Veuillez saisir exactement le mot \'delete\' pour confirmer.',
        'error_account_delete_admin_teams' => 'Impossible de supprimer votre compte car vous êtes l\'administrateur de :count équipe(s). Veuillez transférer ce rôle ou supprimer vos équipes avant de réessayer.',
        'error_account_delete_failed' => 'Une erreur est survenue lors de la suppression de votre compte.',
        'error_account_export_failed' => 'Une erreur est survenue lors de la génération de l\'export.',
        'success_data_exported' => 'Données exportées avec succès !',
        'success_team_tickets_anonymized' => 'Les données des tickets fermés ont été anonymisées avec succès.',
        'success_team_attachments_deleted' => 'Toutes les pièces jointes des tickets fermés ont été supprimées avec succès.',
        'label_confirm_password' => 'Saisissez votre mot de passe pour confirmer *',
        'label_file_format' => 'Format du fichier*',
        'label_type_delete' => 'Saisissez "delete" *',
        'btn_export' => 'Exporter',
        'btn_delete' => 'Supprimer',
        'btn_transfer' => 'Transférer',
        'quit_team_title' => 'Quitter l\'équipe ?',
        'quit_team_intro' => 'Vous vous apprêtez à quitter l\'équipe :team.',
        'quit_team_admin_notice' => 'Afin de quitter cette équipe, vous devez transférer le rôle d\'administrateur de l\'équipe à un autre membre.',
        'quit_team_transfer_label' => 'Transfert du rôle à *',
        'quit_team_btn_transfer_and_quit' => 'Transférer et quitter',
        'quit_team_btn_quit' => 'Quitter',
        'delete_team_title' => 'Supprimer l\'équipe ?',
        'delete_team_intro' => 'Vous vous apprêtez à supprimer définitivement l\'équipe :team.',
        'delete_team_warning' => 'Cette action est irréversible, l\'ensemble des données liées à cette équipe seront définitivement supprimées dans les 30 jours suivants.',
        'transfer_admin_title' => 'Transfert du rôle d\'administrateur',
        'transfer_admin_intro' => 'Vous vous apprêtez à transférer le rôle d\'administrateur de l\'équipe :team.',
        'transfer_admin_label' => 'Transfert du rôle à *',
        'export_team_title' => 'Export des données de l\'équipe',
        'export_team_notice' => 'Cet export de données ne contient pas les messages et les pièces jointes transmis via le chat.',
        'anonymize_team_title' => 'Anonymiser les tickets fermés',
        'anonymize_team_intro' => 'Vous vous apprêtez à <strong>anonymiser définitivement</strong> l\'ensemble des tickets fermés de cette équipe.',
        'anonymize_team_details' => 'Les données personnelles (nom, email, description, messages) seront remplacées conformément au RGPD tout en conservant les données catégorielles pour vos statistiques.',
        'anonymize_team_warning' => 'Cette action est irréversible.',
        'anonymize_team_btn' => 'Anonymiser les données',
        'delete_attachments_title' => 'Supprimer les pièces jointes des tickets fermés',
        'delete_attachments_intro' => 'Vous vous apprêtez à <strong>supprimer définitivement</strong> l\'ensemble des fichiers et pièces jointes rattachés aux tickets fermés de cette équipe.',
        'delete_attachments_warning' => 'Cette action est irréversible, les fichiers seront supprimés du serveur.',
        'delete_attachments_btn' => 'Supprimer les pièces jointes',
        'delete_account_title' => 'Supprimer le compte',
        'delete_account_intro' => 'Vous vous apprêtez à <strong>supprimer définitivement</strong> votre compte.',
        'export_account_title' => 'Export des données de compte'
    ],
    'en' => [
        'title' => 'Account',
        'back' => 'Back',
        'error_generic' => 'An error occurred, please try again.',
        'error_password_incorrect' => 'Incorrect password.',
        'error_fill_all_fields' => 'Please fill in all fields.',
        'error_password_confirm_required' => 'Please enter your password to confirm.',
        'error_team_not_found' => 'Team not found.',
        'error_action_refused_not_owner' => 'Action refused: you are not the owner of this team.',
        'error_team_delete_failed' => 'An error occurred while deleting the team.',
        'error_team_export_failed' => 'An error occurred while exporting data: ',
        'error_team_anonymize_failed' => 'An error occurred while anonymizing data: ',
        'error_team_delete_attachments_failed' => 'An error occurred while deleting attachments: ',
        'error_account_delete_type_delete' => 'Please type exactly \'delete\' to confirm.',
        'error_account_delete_admin_teams' => 'Cannot delete your account because you are the administrator of :count team(s). Please transfer this role or delete your teams before trying again.',
        'error_account_delete_failed' => 'An error occurred while deleting your account.',
        'error_account_export_failed' => 'An error occurred while generating the export.',
        'success_data_exported' => 'Data exported successfully!',
        'success_team_tickets_anonymized' => 'Closed ticket data has been successfully anonymized.',
        'success_team_attachments_deleted' => 'All attachments of closed tickets have been successfully deleted.',
        'label_confirm_password' => 'Enter your password to confirm *',
        'label_file_format' => 'File format*',
        'label_type_delete' => 'Type "delete" *',
        'btn_export' => 'Export',
        'btn_delete' => 'Delete',
        'btn_transfer' => 'Transfer',
        'quit_team_title' => 'Leave team?',
        'quit_team_intro' => 'You are about to leave the :team team.',
        'quit_team_admin_notice' => 'To leave this team, you must transfer the team administrator role to another member.',
        'quit_team_transfer_label' => 'Transfer role to *',
        'quit_team_btn_transfer_and_quit' => 'Transfer and leave',
        'quit_team_btn_quit' => 'Leave',
        'delete_team_title' => 'Delete team?',
        'delete_team_intro' => 'You are about to permanently delete the :team team.',
        'delete_team_warning' => 'This action is irreversible; all data associated with this team will be permanently deleted within 30 days.',
        'transfer_admin_title' => 'Transfer administrator role',
        'transfer_admin_intro' => 'You are about to transfer the administrator role of the :team team.',
        'transfer_admin_label' => 'Transfer role to *',
        'export_team_title' => 'Export team data',
        'export_team_notice' => 'This data export does not include messages and attachments sent via the chat.',
        'anonymize_team_title' => 'Anonymize closed tickets',
        'anonymize_team_intro' => 'You are about to <strong>permanently anonymize</strong> all closed tickets for this team.',
        'anonymize_team_details' => 'Personal data (name, email, description, messages) will be replaced in compliance with GDPR while keeping categorical data for your statistics.',
        'anonymize_team_warning' => 'This action is irreversible.',
        'anonymize_team_btn' => 'Anonymize data',
        'delete_attachments_title' => 'Delete attachments of closed tickets',
        'delete_attachments_intro' => 'You are about to <strong>permanently delete</strong> all files and attachments linked to closed tickets in this team.',
        'delete_attachments_warning' => 'This action is irreversible; files will be deleted from the server.',
        'delete_attachments_btn' => 'Delete attachments',
        'delete_account_title' => 'Delete account',
        'delete_account_intro' => 'You are about to <strong>permanently delete</strong> your account.',
        'export_account_title' => 'Export account data'
    ],
    'es' => [
        'title' => 'Cuenta',
        'back' => 'Volver',
        'error_generic' => 'Ha ocurrido un error, por favor inténtelo de nuevo.',
        'error_password_incorrect' => 'Contraseña incorrecta.',
        'error_fill_all_fields' => 'Por favor, rellene todos los campos.',
        'error_password_confirm_required' => 'Por favor, introduzca su contraseña para confirmar.',
        'error_team_not_found' => 'Equipo no encontrado.',
        'error_action_refused_not_owner' => 'Acción denegada: no es el propietario de este equipo.',
        'error_team_delete_failed' => 'Ocurrió un error al eliminar el equipo.',
        'error_team_export_failed' => 'Ocurrió un error durante la exportación de datos: ',
        'error_team_anonymize_failed' => 'Ocurrió un error durante la anonimización: ',
        'error_team_delete_attachments_failed' => 'Ocurrió un error al eliminar los archivos adjuntos: ',
        'error_account_delete_type_delete' => 'Por favor, escriba exactamente la palabra \'delete\' para confirmar.',
        'error_account_delete_admin_teams' => 'No es posible eliminar su cuenta porque es administrador de :count equipo(s). Por favor, transfiera este rol o elimine sus equipos antes de volver a intentarlo.',
        'error_account_delete_failed' => 'Ocurrió un error al eliminar su cuenta.',
        'error_account_export_failed' => 'Ocurrió un error al generar la exportación.',
        'success_data_exported' => '¡Datos exportados con éxito!',
        'success_team_tickets_anonymized' => 'Los datos de los tickets cerrados se han anonimizado con éxito.',
        'success_team_attachments_deleted' => 'Todos los archivos adjuntos de los tickets cerrados se han eliminado con éxito.',
        'label_confirm_password' => 'Introduzca su contraseña para confirmar *',
        'label_file_format' => 'Formato de archivo*',
        'label_type_delete' => 'Escriba "delete" *',
        'btn_export' => 'Exportar',
        'btn_delete' => 'Eliminar',
        'btn_transfer' => 'Transferir',
        'quit_team_title' => '¿Salir del equipo?',
        'quit_team_intro' => 'Está a punto de salir del equipo :team.',
        'quit_team_admin_notice' => 'Para salir de este equipo, debe transferir el rol de administrador del equipo a otro miembro.',
        'quit_team_transfer_label' => 'Transferir rol a *',
        'quit_team_btn_transfer_and_quit' => 'Transferir y salir',
        'quit_team_btn_quit' => 'Salir',
        'delete_team_title' => '¿Eliminar el equipo?',
        'delete_team_intro' => 'Está a punto de eliminar definitivamente el equipo :team.',
        'delete_team_warning' => 'Esta acción es irreversible; todos los datos vinculados a este equipo se eliminarán definitivamente en los siguientes 30 días.',
        'transfer_admin_title' => 'Transferencia del rol de administrador',
        'transfer_admin_intro' => 'Está a punto de transferir el rol de administrador del equipo :team.',
        'transfer_admin_label' => 'Transferir rol a *',
        'export_team_title' => 'Exportar datos del equipo',
        'export_team_notice' => 'Esta exportación de datos no incluye los mensajes ni los archivos adjuntos transmitidos mediante el chat.',
        'anonymize_team_title' => 'Anonimizar los tickets cerrados',
        'anonymize_team_intro' => 'Está a punto de <strong>anonimizar definitivamente</strong> todos los tickets cerrados de este equipo.',
        'anonymize_team_details' => 'Los datos personales (nombre, correo electrónico, descripción, mensajes) se sustituirán conforme al RGPD, manteniendo los datos categóricos para sus estadísticas.',
        'anonymize_team_warning' => 'Esta acción es irreversible.',
        'anonymize_team_btn' => 'Anonimizar datos',
        'delete_attachments_title' => 'Eliminar archivos adjuntos de tickets cerrados',
        'delete_attachments_intro' => 'Está a punto de <strong>eliminar definitivamente</strong> todos los archivos y adjuntos vinculados a los tickets cerrados de este equipo.',
        'delete_attachments_warning' => 'Esta acción es irreversible; los archivos se eliminarán del servidor.',
        'delete_attachments_btn' => 'Eliminar archivos adjuntos',
        'delete_account_title' => 'Eliminar cuenta',
        'delete_account_intro' => 'Está a punto de <strong>eliminar definitivamente</strong> su cuenta.',
        'export_account_title' => 'Exportar datos de la cuenta'
    ]
];
$t = $translations[$lang];

$form_feedback = "";

// FORMS PROCESS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_GET["team_id"])){
        if($_GET["action"]=="quit_team") {
            //Quit team
            if(isset($_POST['new_admin']) && isset($_POST['password'])){
                // Admin transfer
                $new_admin = $_POST['new_admin'];
                $password_attempt = $_POST['password'] ?? '';
                if (!empty($new_admin) && !empty($password_attempt)) {
                    $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id");
                    $stmt_auth->execute(['user_id' => $_SESSION['user_id']]);
                    $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
                    if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                        $stmt_member = $dbco->prepare("SELECT teams_members_user_id FROM teams_members WHERE teams_members_user_id = :user_id AND teams_members_team_id = :teams_id AND teams_members_deleted = '0';");
                        $stmt_member->execute(['user_id' => $new_admin,'teams_id' => $_SESSION['team_id']]);
                        $new_admin_verification = $stmt_member->fetch(PDO::FETCH_ASSOC);
                        if ($new_admin_verification) {
                            $stmt_update = $dbco->prepare("UPDATE teams SET teams_owner = :new_owner_id WHERE teams_id = :team_id AND teams_owner = :current_user_id;");
                            $stmt_update->execute(['new_owner_id' => $new_admin, 'team_id' => $_SESSION['team_id'], 'current_user_id' => $_SESSION['user_id']]);
                        } else {
                            $form_feedback = $t['error_generic'];
                        }
                    } else {
                        $form_feedback = $t['error_password_incorrect'];
                    } 
                } else {
                    $form_feedback = $t['error_fill_all_fields'];
                }
            }
            if($form_feedback==""){
                $stmt_delete = $dbco->prepare("UPDATE teams_members SET teams_members_deleted = '1' WHERE teams_members_user_id = :user_id AND teams_members_team_id = :team_id");
                $stmt_delete->execute(['user_id' => $_SESSION['user_id'], 'team_id' => $_SESSION['team_id']]);
                $stmt_tickets = $dbco->prepare("UPDATE tickets SET tickets_assigned_to = NULL WHERE tickets_assigned_to = :user_id AND tickets_teams = :team_id AND tickets_status != 2");
                $stmt_tickets->execute(['user_id' => $_SESSION['user_id'], 'team_id' => $_SESSION['team_id']]);
                header("Location: ../../../dashboard");
                exit();
            }

        } elseif($_GET["action"]=="transfer_team_admin") {
            //Transfer team admin role
            $new_admin = $_POST['new_admin'];
            $password_attempt = $_POST['password'] ?? '';
            if (!empty($new_admin) && !empty($password_attempt)) {
                $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id");
                $stmt_auth->execute(['user_id' => $_SESSION['user_id']]);
                $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
                if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                    $stmt_member = $dbco->prepare("SELECT teams_members_user_id FROM teams_members WHERE teams_members_user_id = :user_id AND teams_members_team_id = :teams_id AND teams_members_deleted = '0';");
                    $stmt_member->execute(['user_id' => $new_admin,'teams_id' => $_SESSION['team_id']]);
                    $new_admin_verification = $stmt_member->fetch(PDO::FETCH_ASSOC);
                    if ($new_admin_verification) {
                        $stmt_update = $dbco->prepare("UPDATE teams SET teams_owner = :new_owner_id WHERE teams_id = :team_id AND teams_owner = :current_user_id;");
                        $stmt_update->execute(['new_owner_id' => $new_admin, 'team_id' => $_SESSION['team_id'], 'current_user_id' => $_SESSION['user_id']]);
                        header("Location: ../members");
                        exit();
                    } else {
                        $form_feedback = $t['error_generic'];
                    }
                } else {
                    $form_feedback = $t['error_password_incorrect'];
                } 
            } else {
                $form_feedback = $t['error_fill_all_fields'];
            }

        } elseif($_GET["action"]=="delete_team") {
            // Delete team
            $password_attempt = $_POST['password'] ?? '';
            $current_team_id = (int)($_GET['team_id'] ?? $_SESSION['team_id'] ?? 0);
            $user_id = (int)$_SESSION['user_id'];
            if (empty($password_attempt)) {
                $form_feedback = $t['error_password_confirm_required'];
            } else {
                $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id LIMIT 1");
                $stmt_auth->execute(['user_id' => $user_id]);
                $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
                if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                    $stmt_owner = $dbco->prepare("SELECT teams_id FROM teams WHERE teams_id = :team_id AND teams_owner = :owner_id AND teams_deleted = 0 LIMIT 1");
                    $stmt_owner->execute(['team_id' => $current_team_id, 'owner_id' => $user_id]);
                    if ($stmt_owner->fetch()) {
                        try {
                            $dbco->beginTransaction();
                            $stmt_team_del = $dbco->prepare("UPDATE teams SET teams_deleted = 1, teams_deleted_at = NOW(), teams_form_url = NULL, teams_api_token = NULL WHERE teams_id = :team_id");
                            $stmt_team_del->execute(['team_id' => $current_team_id]);
                            $stmt_tickets_del = $dbco->prepare("UPDATE tickets SET tickets_status = 2, tickets_closing_date = COALESCE(tickets_closing_date, NOW()), tickets_deleted_at = NOW() WHERE tickets_teams = :team_id");
                            $stmt_tickets_del->execute(['team_id' => $current_team_id]);
                            $stmt_messages_del = $dbco->prepare("UPDATE messages m INNER JOIN tickets t ON m.messages_ticket_id = t.tickets_id SET m.messages_deleted_at = NOW() WHERE t.tickets_teams = :team_id");
                            $stmt_messages_del->execute(['team_id' => $current_team_id]);
                            $stmt_members_del = $dbco->prepare("UPDATE teams_members SET teams_members_deleted = 1 WHERE teams_members_team_id = :team_id");
                            $stmt_members_del->execute(['team_id' => $current_team_id]);
                            $dbco->commit();
                            if (isset($_SESSION['team_id']) && $_SESSION['team_id'] == $current_team_id) {
                                unset($_SESSION['team_id']);
                            }
                            header("Location: ../../../dashboard/");
                            exit();
                        } catch (Exception $e) {
                            $dbco->rollBack();
                            $form_feedback = $t['error_team_delete_failed'];
                        }
                    } else {
                        $form_feedback = $t['error_action_refused_not_owner'];
                    }
                } else {
                    $form_feedback = $t['error_password_incorrect'];
                }
            }
        
        } elseif($_GET["action"] == "export_team") {
            //Export team data
            $file_type = $_POST['file_type'] ?? 'json';
            $team_id = (int)$_GET['team_id'];
            try {
                $stmt_team = $dbco->prepare("SELECT teams_id, teams_name, teams_privacy_policy_url, teams_form_url, teams_form_config, teams_messages_template, teams_color, teams_creation_date FROM teams WHERE teams_id = :team_id AND (teams_deleted = 0 OR teams_deleted IS NULL) LIMIT 1");
                $stmt_team->execute(['team_id' => $team_id]);
                $team_data = $stmt_team->fetch(PDO::FETCH_ASSOC);
                if (!$team_data) {
                    throw new Exception($t['error_team_not_found']);
                }
                $team_settings_export = $team_data;
                $team_settings_export['teams_form_config'] = json_decode($team_data['teams_form_config'] ?? '[]', true) ?: [];
                $team_settings_export['teams_messages_template'] = json_decode($team_data['teams_messages_template'] ?? '[]', true) ?: [];
                $stmt_members = $dbco->prepare("SELECT tm.teams_members_id, u.users_first_name, u.users_last_name, u.users_email, tm.teams_members_position, tm.teams_members_groups, tm.teams_members_join_date FROM teams_members tm INNER JOIN users u ON tm.teams_members_user_id = u.users_id WHERE tm.teams_members_team_id = :team_id AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL) AND tm.teams_members_join_date IS NOT NULL ORDER BY tm.teams_members_join_date ASC");
                $stmt_members->execute(['team_id' => $team_id]);
                $members_data = $stmt_members->fetchAll(PDO::FETCH_ASSOC);
                $stmt_tickets = $dbco->prepare("SELECT tickets_id, tickets_first_name, tickets_last_name, tickets_email, tickets_subject, tickets_description, tickets_additionnal_fields, tickets_status, tickets_priority, tickets_admin_notes, tickets_rating, tickets_feedback, tickets_creation_date, tickets_closing_date FROM tickets WHERE tickets_teams = :team_id AND (tickets_deleted_at IS NULL) ORDER BY tickets_creation_date DESC");
                $stmt_tickets->execute(['team_id' => $team_id]);
                $tickets_raw = $stmt_tickets->fetchAll(PDO::FETCH_ASSOC);
                $tickets_data = [];
                foreach ($tickets_raw as $ticket) {
                    $item = $ticket;
                    $item['tickets_additionnal_fields'] = json_decode($ticket['tickets_additionnal_fields'] ?? '[]', true) ?: [];
                    $tickets_data[] = $item;
                }
                $date_export = date('Y-m-d_H-i');
                $filename_base = 'team_' . $team_id . '_export_' . $date_export;
                if ($file_type === 'json') {
                    // --- EXPORT JSON ---
                    $export_array = ['team_settings' => $team_settings_export, 'members' => $members_data, 'tickets' => $tickets_data];
                    header('Content-Type: application/json; charset=utf-8');
                    header('Content-Disposition: attachment; filename="' . $filename_base . '.json"');
                    echo json_encode($export_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit();
                } elseif ($file_type === 'csv') {
                    // --- EXPORT CSV ---
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename="' . $filename_base . '.csv"');
                    $output = fopen('php://output', 'w');
                    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
                    fputcsv($output, ['--- TEAM SETTINGS ---']);
                    fputcsv($output, array_keys($team_data));
                    fputcsv($output, array_values($team_data));
                    fputcsv($output, []);
                    fputcsv($output, []);
                    fputcsv($output, ['--- MEMBERS ---']);
                    if (!empty($members_data)) {
                        fputcsv($output, array_keys($members_data[0]));
                        foreach ($members_data as $member) {
                            fputcsv($output, array_values($member));
                        }
                    } else {
                        fputcsv($output, ['No members']);
                    }
                    fputcsv($output, []);
                    fputcsv($output, []);
                    fputcsv($output, ['--- TICKETS ---']);
                    if (!empty($tickets_raw)) {
                        fputcsv($output, array_keys($tickets_raw[0]));
                        foreach ($tickets_raw as $tRow) {
                            fputcsv($output, array_values($tRow));
                        }
                    } else {
                        fputcsv($output, ['No tickets']);
                    }
                    fclose($output);
                    exit();
                }
                $form_feedback = $t['success_data_exported'];
            } catch (Exception $e) {
                $form_feedback = $t['error_team_export_failed'] . $e->getMessage();
            }
            
        } elseif($_GET["action"] == "anonymize_team_tickets") {
            // Anonymise team closed tickets
            $password_attempt = $_POST['password'] ?? '';
            $team_id = (int)$_GET['team_id'];
            $user_id = (int)$_SESSION['user_id'];
            if (empty($password_attempt)) {
                $form_feedback = $t['error_password_confirm_required'];
            } else {
                $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id LIMIT 1");
                $stmt_auth->execute(['user_id' => $user_id]);
                $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
                if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                    try {
                        $dbco->beginTransaction();
                        $stmtTickets = $dbco->prepare("SELECT tickets_id, tickets_additionnal_fields FROM tickets WHERE tickets_teams = :team_id AND tickets_status = 2 AND tickets_email NOT LIKE 'anonymized_%'");
                        $stmtTickets->execute(['team_id' => $team_id]);
                        $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
                        if (!empty($tickets)) {
                            $ticketIds = array_column($tickets, 'tickets_id');
                            $stmtUpdate = $dbco->prepare("UPDATE tickets SET tickets_first_name = 'Anonymous', tickets_last_name = 'User', tickets_email = CONCAT('anonymized_', tickets_id, '@opensupport.local'), tickets_description = '[Data deleted in accordance with the GDPR]', tickets_admin_notes = NULL, tickets_additionnal_fields = :clean_fields, tickets_deleted_at = NOW() WHERE tickets_id = :ticket_id");
                            foreach ($tickets as $tRow) {
                                $cleanFields = [];
                                $rawFields = json_decode($tRow['tickets_additionnal_fields'] ?? '[]', true);
                                if (is_array($rawFields)) {
                                    foreach ($rawFields as $field) {
                                        $question = $field['question'] ?? '';
                                        $answer = $field['answer'] ?? '';
                                        if (filter_var($answer, FILTER_VALIDATE_EMAIL) || preg_match('/^[0-9+ ]{8,15}$/', $answer)) {
                                            $answer = '[Deleted]';
                                        }
                                        $cleanFields[] = [
                                            'question' => $question,
                                            'answer'   => $answer
                                        ];
                                    }
                                }
                                $stmtUpdate->execute([
                                    'clean_fields' => json_encode($cleanFields, JSON_UNESCAPED_UNICODE),
                                    'ticket_id'    => $tRow['tickets_id']
                                ]);
                            }
                            $inClause = implode(',', array_map('intval', $ticketIds));
                            $dbco->exec("UPDATE messages SET messages_content = '[Deleted message]', messages_attachements = '[]', messages_deleted_at = NOW() WHERE messages_ticket_id IN ($inClause)");
                        }
                        $dbco->commit();
                        $form_feedback = $t['success_team_tickets_anonymized'];
                    } catch (Exception $e) {
                        $dbco->rollBack();
                        $form_feedback = $t['error_team_anonymize_failed'] . $e->getMessage();
                    }
                } else {
                    $form_feedback = $t['error_password_incorrect'];
                }
            }

        } elseif($_GET["action"] == "delete_team_attachments") {
            // Delete closed tickets attachements
            $password_attempt = $_POST['password'] ?? '';
            $team_id = (int)$_GET['team_id'];
            $user_id = (int)$_SESSION['user_id'];
            if (empty($password_attempt)) {
                $form_feedback = $t['error_password_confirm_required'];
            } else {
                $stmt_auth = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id LIMIT 1");
                $stmt_auth->execute(['user_id' => $user_id]);
                $current_user = $stmt_auth->fetch(PDO::FETCH_ASSOC);
                if ($current_user && password_verify($password_attempt, $current_user['users_password'])) {
                    try {
                        $stmtTickets = $dbco->prepare("SELECT tickets_id FROM tickets WHERE tickets_teams = :team_id AND tickets_status = 2");
                        $stmtTickets->execute(['team_id' => $team_id]);
                        $ticketIds = $stmtTickets->fetchAll(PDO::FETCH_COLUMN);
                        $baseStorage = !empty($opensupport_storage_dir) ? $opensupport_storage_dir : (__DIR__ . '/../storage/attachments/');
                        foreach ($ticketIds as $ticketId) {
                            $dir = rtrim($baseStorage, '/') . '/' . (int)$ticketId;
                            if (is_dir($dir)) {
                                array_map('unlink', glob("$dir/*.*"));
                                @rmdir($dir);
                            }
                        }
                        if (!empty($ticketIds)) {
                            $inClause = implode(',', array_map('intval', $ticketIds));
                            $dbco->exec("UPDATE messages SET messages_attachements = '[]' WHERE messages_ticket_id IN ($inClause)");
                        }
                        $form_feedback = $t['success_team_attachments_deleted'];
                    } catch (Exception $e) {
                        $form_feedback = $t['error_team_delete_attachments_failed'] . $e->getMessage();
                    }
                } else {
                    $form_feedback = $t['error_password_incorrect'];
                }
            }
        }
        
    } else {
        if($_GET["action"]=="delete_account") {
            //Delete account
            $verification = trim($_POST['verification'] ?? '');
            $password = $_POST['password'] ?? '';
            $user_id = $_SESSION['user_id'];

            if (strtolower($verification) !== 'delete') {
                $form_feedback = $t['error_account_delete_type_delete'];
            } else {
                $stmt_check_admin = $dbco->prepare("SELECT COUNT(teams_id) FROM teams WHERE teams_owner = :user_id AND teams_deleted = 0");
                $stmt_check_admin->execute(['user_id' => $user_id]);
                $admin_teams_count = $stmt_check_admin->fetchColumn();
                if ($admin_teams_count > 0) {
                    $form_feedback = str_replace(':count', $admin_teams_count, $t['error_account_delete_admin_teams']);
                } else {
                    $stmt_user = $dbco->prepare("SELECT users_password FROM users WHERE users_id = :user_id LIMIT 1");
                    $stmt_user->execute(['user_id' => $user_id]);
                    $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
                    if ($user && password_verify($password, $user['users_password'])) {
                        try {
                            $dbco->beginTransaction();
                            $stmt_teams = $dbco->prepare("UPDATE teams_members SET teams_members_deleted = '1' WHERE teams_members_user_id = :user_id");
                            $stmt_teams->execute(['user_id' => $user_id]);
                            $stmt_tickets = $dbco->prepare("UPDATE tickets SET tickets_assigned_to = NULL WHERE tickets_assigned_to = :user_id AND tickets_status != 2");
                            $stmt_tickets->execute(['user_id' => $user_id]);
                            $stmt_account = $dbco->prepare("UPDATE users SET users_deleted = '1' WHERE users_id = :user_id");
                            $stmt_account->execute(['user_id' => $user_id]);
                            $dbco->commit();
                            $_SESSION = array();
                            if (ini_get("session.use_cookies")) {
                                $params = session_get_cookie_params();
                                setcookie(session_name(), '', time() - 42000,
                                    $params["path"], $params["domain"],
                                    $params["secure"], $params["httponly"]
                                );
                            }
                            session_destroy();
                            header("Location: ../../login");
                            exit();

                        } catch (Exception $e) {
                            $dbco->rollBack();
                            $form_feedback = $t['error_account_delete_failed'];
                        }
                    } else {
                        $form_feedback = $t['error_password_incorrect'];
                    }
                }
            }

        } elseif($_GET["action"]=="export_account") {
            //Export account data
            $file_type = $_POST['file_type'];
            try {
                $stmt_user = $dbco->prepare("SELECT * FROM users WHERE users_id = :user_id LIMIT 1");
                $stmt_user->execute(['user_id' => $_SESSION['user_id']]);
                $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
                if ($user_data) {
                    unset($user_data['users_password'], $user_data['users_password_modify_token'], $user_data['users_invitation_token'], $user_data['users_deleted']);
                }
                
                $stmt_teams = $dbco->prepare("SELECT t.teams_id, t.teams_name, tm.teams_members_position AS teams_position, tm.teams_members_groups AS teams_groups, tm.teams_members_join_date AS teams_join_date FROM teams t INNER JOIN teams_members tm ON t.teams_id = tm.teams_members_team_id WHERE tm.teams_members_user_id = :user_id AND tm.teams_members_deleted = 0 AND t.teams_deleted = 0");
                $stmt_teams->execute(['user_id' => $_SESSION['user_id']]);
                $teams_data = $stmt_teams->fetchAll(PDO::FETCH_ASSOC);
                $date_export = date('Y-m-d_H-i');
                if ($file_type === 'json') {
                    // --- EXPORT JSON ---
                    $export_array = ['user' => $user_data, 'teams' => $teams_data];
                    header('Content-Type: application/json; charset=utf-8');
                    header('Content-Disposition: attachment; filename="account_export_' . $date_export . '.json"');
                    echo json_encode($export_array, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    exit();

                } elseif ($file_type === 'csv') {
                    // --- EXPORT CSV ---
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename="account_export_' . $date_export . '.csv"');
                    $output = fopen('php://output', 'w');
                    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                    fputcsv($output, ['--- USER DATA ---']);
                    if ($user_data) {
                        fputcsv($output, array_keys($user_data));
                        fputcsv($output, array_values($user_data));
                    }
                    fputcsv($output, []);
                    fputcsv($output, []);
                    fputcsv($output, ['--- TEAMS ---']);
                    if (count($teams_data) > 0) {
                        fputcsv($output, array_keys($teams_data[0]));
                        foreach ($teams_data as $team) {
                            fputcsv($output, array_values($team));
                        }
                    } else {
                        fputcsv($output, ['No team']);
                    }
                    fclose($output);
                    exit();
                }
                $form_feedback = $t['success_data_exported'];

            } catch (Exception $e) {
                $form_feedback = $t['error_account_export_failed'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenSupport</title>
    <link rel="icon" type="image/x-icon" href="<?= $opensupport_link?>/src/opensupport_assets/opensupport_icon.svg">
    <link rel="stylesheet" href="<?= $opensupport_link?>/src/css/main.css">
    <link rel="manifest" href="<?= $opensupport_link ?>/manifest.json">
</head>
<body class="form">
    <div class="action_bar reverse">
        <a href="#" onclick="history.back()"><?= $t['back'] ?></a>
    </div>
    
    <main>
        <?php if(isset($_GET["team_id"]) && $_GET["team_id"]!=""):
            if(verifyTeamAccess($_GET['team_id'])==false){
                header("Location: ../../../dashboard/");
                exit();
            }?>
            <?php if($_GET["action"]=="quit_team"):?>
                <section class="box">
                    <header>
                        <h1><?= $t['quit_team_title'] ?></h1>
                    </header>

                    <form target="" method="POST">
                        <?php if($form_feedback!=""):?>
                        <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif;?>
                        <div>
                            <?php $sth = $dbco->prepare("SELECT teams_name, teams_owner FROM teams WHERE teams_id = :team_id AND teams_deleted = 0 LIMIT 1");
                            $sth->execute(['team_id' => $_SESSION['team_id']]);
                            $team = $sth->fetch(PDO::FETCH_ASSOC);
                            $is_admin = false;
                            $teamName = '';
                            if ($team) {
                                if ($team['teams_owner'] == $_SESSION['user_id']) {
                                    $is_admin = true;
                                }
                                $teamName = htmlspecialchars($team['teams_name']);
                            }?>
                            <p><?= str_replace(':team', '<strong>' . $teamName . '</strong>', $t['quit_team_intro']) ?></p>
                        </div>
                        <?php if($is_admin):?>
                            <div>
                                <p><?= $t['quit_team_admin_notice'] ?></p>
                            </div>
                            <div>
                                <label><?= $t['quit_team_transfer_label'] ?></label>
                                <select name="new_admin" required>
                                    <?php $sth_members = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email FROM users u INNER JOIN teams_members tm ON u.users_id = tm.teams_members_user_id WHERE tm.teams_members_team_id = :team_id AND tm.teams_members_deleted = 0 AND tm.teams_members_join_date IS NOT NULL AND u.users_id != :user_id ORDER BY u.users_first_name ASC, u.users_email ASC");
                                    $sth_members->execute(['team_id' => $_SESSION['team_id'], 'user_id' => $_SESSION['user_id']]);
                                    $team_members = $sth_members->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($team_members as $member):?>
                                        <option value="<?= htmlspecialchars($member['users_id']) ?>"><?= htmlspecialchars($member['users_first_name'] . ' ' . $member['users_last_name']) ?> (<?= htmlspecialchars($member['users_email']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label><?= $t['label_confirm_password'] ?></label>
                                <input type="password" name="password" required>
                            </div>
                            <button type="submit"><?= $t['quit_team_btn_transfer_and_quit'] ?></button>
                        <?php else:?>
                            <button type="submit"><?= $t['quit_team_btn_quit'] ?></button>
                        <?php endif;?>
                    </form>
                </section>
        
            <?php elseif($_GET["action"]=="delete_team"):
                $sth = $dbco->prepare("SELECT teams_name, teams_owner FROM teams WHERE teams_id = :team_id AND teams_deleted = 0 LIMIT 1");
                $sth->execute(['team_id' => $_SESSION['team_id']]);
                $team = $sth->fetch(PDO::FETCH_ASSOC);
                if ($team) {
                    if ($team['teams_owner'] == $_SESSION['user_id']) {
                        $is_admin = true;
                    } else {
                        header("Location: ../../../dashboard");
                        exit();
                    }
                }?>
                <section class="box">
                    <header>
                        <h1><?= $t['delete_team_title'] ?></h1>
                    </header>

                    <form target="" method="POST">
                        <?php if($form_feedback!=""):?>
                        <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif;?>
                        <div>
                            <p><?= str_replace(':team', '<strong>' . htmlspecialchars($team['teams_name'] ?? '') . '</strong>', $t['delete_team_intro']) ?></p>
                        </div>
                        <div>
                            <strong><?= $t['delete_team_warning'] ?></strong>
                        </div>
                        <div>
                            <label><?= $t['label_confirm_password'] ?></label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit"><?= $t['btn_delete'] ?></button>
                    </form>
                </section>
        
            <?php elseif($_GET["action"]=="transfer_team_admin"):
            $sth = $dbco->prepare("SELECT teams_owner FROM teams WHERE teams_id = :team_id AND teams_deleted = 0 LIMIT 1");
            $sth->execute(['team_id' => $_SESSION['team_id']]);
            $team = $sth->fetch(PDO::FETCH_ASSOC);
            if ($team && $team['teams_owner'] != $_SESSION['user_id']) {
                header("Location: ../../../dashboard/");
                exit();
            }?>
                <section class="box">
                    <header>
                        <h1><?= $t['transfer_admin_title'] ?></h1>
                    </header>

                    <form target="" method="POST">
                        <div class="transfer">
                            <p class="user_icon"><?= substr($_SESSION['user_name'] ?? '', 0, 1)?></p>
                            <p>--></p>
                            <p class="user_icon">?</p>
                        </div>
                        <div>
                            <?php $sth = $dbco->prepare("SELECT teams_name FROM teams WHERE teams_id = :team_id AND teams_deleted = 0 LIMIT 1");
                            $sth->execute(['team_id' => $_SESSION['team_id']]);
                            $team = $sth->fetch(PDO::FETCH_ASSOC);
                            $teamName = $team ? htmlspecialchars($team['teams_name']) : ''; ?>
                            <p><?= str_replace(':team', '<strong>' . $teamName . '</strong>', $t['transfer_admin_intro']) ?></p>
                        </div>
                        <?php if($form_feedback!=""):?>
                        <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif;?>
                        <div>
                            <label><?= $t['transfer_admin_label'] ?></label>
                            <select name="new_admin" required>
                                <?php $sth_members = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email FROM users u INNER JOIN teams_members tm ON u.users_id = tm.teams_members_user_id WHERE tm.teams_members_team_id = :team_id AND tm.teams_members_deleted = 0 AND tm.teams_members_join_date IS NOT NULL AND u.users_id != :user_id ORDER BY u.users_first_name ASC, u.users_email ASC");
                                $sth_members->execute(['team_id' => $_SESSION['team_id'], 'user_id' => $_SESSION['user_id']]);
                                $team_members = $sth_members->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($team_members as $member):?>
                                    <option value="<?= htmlspecialchars($member['users_id']) ?>"><?= htmlspecialchars($member['users_first_name'] . ' ' . $member['users_last_name']) ?> (<?= htmlspecialchars($member['users_email']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label><?= $t['label_confirm_password'] ?></label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit"><?= $t['btn_transfer'] ?></button>
                    </form>
                </section>
        
            <?php elseif($_GET["action"]=="export_team"):
            $sth = $dbco->prepare("SELECT teams_owner FROM teams WHERE teams_id = :team_id AND teams_deleted = 0 LIMIT 1");
            $sth->execute(['team_id' => $_SESSION['team_id']]);
            $team = $sth->fetch(PDO::FETCH_ASSOC);
            if ($team && $team['teams_owner'] != $_SESSION['user_id']) {
                header("Location: ../../../dashboard/");
                exit();
            }?>
                <section class="box">
                    <header>
                        <h1><?= $t['export_team_title'] ?></h1>
                    </header>

                    <form target="" method="POST">
                        <div class="transfer">
                            <p class="user_icon"><?= substr($_SESSION['user_name'] ?? '', 0, 1)?></p>
                        </div>
                        <?php if($form_feedback!=""):?>
                        <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif;?>
                        <div>
                            <p><?= $t['export_team_notice'] ?></p>
                        </div>
                        <div>
                            <label><?= $t['label_file_format'] ?></label>
                            <select name="file_type" required>
                                <option value="csv">CSV (.csv)</option>
                                <option value="json">JSON (.json)</option>
                            </select>
                        </div>
                        <button type="submit"><?= $t['btn_export'] ?></button>
                    </form>
                </section>
        
            <?php elseif($_GET["action"]=="anonymize_team_tickets"): ?>
                <section class="box">
                    <header>
                        <h1><?= $t['anonymize_team_title'] ?></h1>
                    </header>

                    <form method="POST">
                        <?php if($form_feedback!=""): ?>
                            <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif; ?>
                        <div>
                            <p><?= $t['anonymize_team_intro'] ?></p>
                            <p><?= $t['anonymize_team_details'] ?></p>
                        </div>
                        <div>
                            <strong><?= $t['anonymize_team_warning'] ?></strong>
                        </div>
                        <div>
                            <label><?= $t['label_confirm_password'] ?></label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit"><?= $t['anonymize_team_btn'] ?></button>
                    </form>
                </section>

            <?php elseif($_GET["action"]=="delete_team_attachments"): ?>
                <section class="box">
                    <header>
                        <h1><?= $t['delete_attachments_title'] ?></h1>
                    </header>

                    <form method="POST">
                        <?php if($form_feedback!=""): ?>
                            <p class="alert"><?php echo $form_feedback; ?></p>
                        <?php endif; ?>
                        <div>
                            <p><?= $t['delete_attachments_intro'] ?></p>
                        </div>
                        <div>
                            <strong><?= $t['delete_attachments_warning'] ?></strong>
                        </div>
                        <div>
                            <label><?= $t['label_confirm_password'] ?></label>
                            <input type="password" name="password" required>
                        </div>
                        <button type="submit"><?= $t['delete_attachments_btn'] ?></button>
                    </form>
                </section>
        
            <?php else:
                header('HTTP/1.0 404 Not Found');
                exit;
            endif;?>
        
        <?php elseif($_GET["action"]=="delete_account"):?>
            <section class="box">
                <header>
                    <h1><?= $t['delete_account_title'] ?></h1>
                </header>

                <form target="" method="POST">
                    <div class="transfer">
                        <p class="user_icon"><?= substr($_SESSION['user_name'] ?? '', 0, 1)?></p>
                    </div>
                    <div>
                        <p><?= $t['delete_account_intro'] ?></p>
                    </div>
                    <?php if($form_feedback!=""):?>
                    <p class="alert"><?php echo $form_feedback; ?></p>
                    <?php endif;?>
                    <div>
                        <label><?= $t['label_type_delete'] ?></label>
                        <input type="text" name="verification" required>
                    </div>
                    <div>
                        <label><?= $t['label_confirm_password'] ?></label>
                        <input type="password" name="password" required>
                    </div>
                    <button type="submit"><?= $t['btn_delete'] ?></button>
                </form>
            </section>
        
        <?php elseif($_GET["action"]=="export_account"):?>
            <section class="box">
                <header>
                    <h1><?= $t['export_account_title'] ?></h1>
                </header>

                <form target="" method="POST">
                    <div class="transfer">
                        <p class="user_icon"><?= substr($_SESSION['user_name'] ?? '', 0, 1)?></p>
                    </div>
                    <?php if($form_feedback!=""):?>
                    <p class="alert"><?php echo $form_feedback; ?></p>
                    <?php endif;?>
                    <div>
                        <label><?= $t['label_file_format'] ?></label>
                        <select name="file_type" required>
                            <option value="csv">CSV (.csv)</option>
                            <option value="json">JSON (.json)</option>
                        </select>
                    </div>
                    <button type="submit"><?= $t['btn_export'] ?></button>
                </form>
            </section>
        
        <?php else:
            header('HTTP/1.0 404 Not Found');
            exit;
        endif;?>
    </main>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>