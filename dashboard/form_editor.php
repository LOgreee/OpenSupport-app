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
        'title' => 'Support',
        'subtitle' => 'Une équipe à votre écoute.',
        'email' => 'Adresse email',
        'firstname' => 'Prénom',
        'lastname' => 'Nom',
        'subject' => 'Objet',
        'description' => 'Description de votre problème',
        'submit' => 'Lancer le chat',
        'back' => 'Retour',
        'form_editor_title' => 'Éditeur de formulaire',
        'no_selection_msg' => 'Cliquez sur un champ modifiable à droite pour afficher ses propriétés, ou bien cliquez sur "+" pour ajouter un nouveau champ.',
        'label_input_type' => 'Type d\'entrée',
        'type_text' => 'Texte',
        'type_email' => 'Email',
        'type_tel' => 'Numéro de téléphone',
        'type_select' => 'Volet déroulant',
        'type_checkbox' => 'Case à cocher',
        'label_title' => 'Titre',
        'placeholder_title' => 'Ex : Catégorie',
        'label_char_limit' => 'Limite de caractères (optionnel)',
        'placeholder_char_limit' => 'Ex : 50 (laisser vide pour illimité)',
        'label_required' => 'Champ obligatoire',
        'label_possible_answers' => 'Réponses possibles',
        'btn_delete_field' => 'Supprimer l\'élément du formulaire',
        'btn_save' => 'Sauvegarder',
        'feedback_saved' => 'Formulaire sauvegardé avec succès !',
        'feedback_save_error' => 'Erreur lors de la sauvegarde : ',
        'feedback_format_error' => 'Erreur de format des données.',
        'js_untitled' => 'Sans titre',
        'js_no_option' => 'Aucune option définie',
        'js_max' => 'Max',
        'js_answer_prefix' => 'Réponse',
        'js_none' => 'Aucun',
        'js_members' => 'Membres',
        'js_groups' => 'Groupes',
        'js_option_default' => 'Option 1',
        'js_new_field' => 'Nouveau champ'
    ],
    'en' => [
        'title' => 'Support',
        'subtitle' => 'Our team is here to help.',
        'email' => 'Email Address',
        'firstname' => 'First Name',
        'lastname' => 'Last Name',
        'subject' => 'Subject',
        'description' => 'Describe your issue',
        'submit' => 'Start Chat',
        'back' => 'Back',
        'form_editor_title' => 'Form Editor',
        'no_selection_msg' => 'Click on an editable field on the right to display its properties, or click "+" to add a new field.',
        'label_input_type' => 'Input type',
        'type_text' => 'Text',
        'type_email' => 'Email',
        'type_tel' => 'Phone number',
        'type_select' => 'Dropdown',
        'type_checkbox' => 'Checkbox',
        'label_title' => 'Title',
        'placeholder_title' => 'E.g., Category',
        'label_char_limit' => 'Character limit (optional)',
        'placeholder_char_limit' => 'E.g., 50 (leave blank for unlimited)',
        'label_required' => 'Mandatory field',
        'label_possible_answers' => 'Possible answers',
        'btn_delete_field' => 'Delete element from form',
        'btn_save' => 'Save',
        'feedback_saved' => 'Form saved successfully!',
        'feedback_save_error' => 'Error while saving: ',
        'feedback_format_error' => 'Data format error.',
        'js_untitled' => 'Untitled',
        'js_no_option' => 'No option defined',
        'js_max' => 'Max',
        'js_answer_prefix' => 'Answer',
        'js_none' => 'None',
        'js_members' => 'Members',
        'js_groups' => 'Groups',
        'js_option_default' => 'Option 1',
        'js_new_field' => 'New field'
    ],
    'es' => [
        'title' => 'Soporte',
        'subtitle' => 'Nuestro equipo está aquí para ayudar.',
        'email' => 'Correo electrónico',
        'firstname' => 'Nombre',
        'lastname' => 'Apellido',
        'subject' => 'Asunto',
        'description' => 'Descripción del problema',
        'submit' => 'Iniciar chat',
        'back' => 'Volver',
        'form_editor_title' => 'Editor de formulario',
        'no_selection_msg' => 'Haga clic en un campo editable a la derecha para ver sus propiedades, o pulse "+" para añadir un nuevo campo.',
        'label_input_type' => 'Tipo de entrada',
        'type_text' => 'Texto',
        'type_email' => 'Correo electrónico',
        'type_tel' => 'Número de teléfono',
        'type_select' => 'Menú desplegable',
        'type_checkbox' => 'Casilla de verificación',
        'label_title' => 'Título',
        'placeholder_title' => 'Ej.: Categoría',
        'label_char_limit' => 'Límite de caracteres (opcional)',
        'placeholder_char_limit' => 'Ej.: 50 (dejar vacío para ilimitado)',
        'label_required' => 'Campo obligatorio',
        'label_possible_answers' => 'Respuestas posibles',
        'btn_delete_field' => 'Eliminar elemento del formulario',
        'btn_save' => 'Guardar',
        'feedback_saved' => '¡Formulario guardado con éxito!',
        'feedback_save_error' => 'Error al guardar: ',
        'feedback_format_error' => 'Error en el formato de datos.',
        'js_untitled' => 'Sin título',
        'js_no_option' => 'Ninguna opción definida',
        'js_max' => 'Máx',
        'js_answer_prefix' => 'Respuesta',
        'js_none' => 'Ninguno',
        'js_members' => 'Miembros',
        'js_groups' => 'Grupos',
        'js_option_default' => 'Opción 1',
        'js_new_field' => 'Nuevo campo'
    ]
];
$t = $translations[$lang];

$form_feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raw_json = $_POST['form_config_json'] ?? '[]';
    $decoded = json_decode($raw_json, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        try {
            $stmtSave = $dbco->prepare("UPDATE teams SET teams_form_config = :config WHERE teams_id = :team_id");
            $stmtSave->execute(['config' => json_encode($decoded, JSON_UNESCAPED_UNICODE), 'team_id' => $_GET['team_id']]);
            $form_feedback = $t['feedback_saved'];
        } catch (PDOException $e) {
            $form_feedback = $t['feedback_save_error'] . $e->getMessage();
        }
    } else {
        $form_feedback = $t['feedback_format_error'];
    }
}

$stmtTeam = $dbco->prepare("SELECT teams_id, teams_name, teams_color, teams_logo, teams_form_config FROM teams WHERE teams_id = :team_id LIMIT 1");
$stmtTeam->execute(['team_id' => $_GET['team_id']]);
$team = $stmtTeam->fetch(PDO::FETCH_ASSOC);
if (!$team) {
    header("Location: ../dashboard/");
    exit();
}
$current_config = json_decode($team['teams_form_config'] ?? '[]', true) ?: [];

$stmtStaff = $dbco->prepare("SELECT u.users_id, u.users_first_name, u.users_last_name, u.users_email, tm.teams_members_groups FROM users u INNER JOIN teams_members tm ON tm.teams_members_user_id = u.users_id WHERE tm.teams_members_team_id = :team_id AND (tm.teams_members_deleted = 0 OR tm.teams_members_deleted IS NULL) AND tm.teams_members_join_date IS NOT NULL ORDER BY u.users_first_name ASC");
$stmtStaff->execute(['team_id' => $_GET['team_id']]);
$staffMembers = $stmtStaff->fetchAll(PDO::FETCH_ASSOC);
$teamGroups = [];
foreach ($staffMembers as $member) {
    if (!empty($member['teams_members_groups'])) {
        $grps = array_map('trim', explode(',', $member['teams_members_groups']));
        foreach ($grps as $g) {
            if ($g !== '' && !in_array($g, $teamGroups)) {
                $teamGroups[] = $g;
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
        <a href="settings"><?= $t['back'] ?></a>
    </div>
    
    <main>
        <section class="box form-settings">
            <header>
                <h1><?= $t['form_editor_title'] ?></h1>
            </header>

            <?php if (!empty($form_feedback)): ?>
                <p class="alert" style="margin-bottom: 15px;"><?= htmlspecialchars($form_feedback) ?></p>
            <?php endif; ?>

            <div id="no-selection-msg"><?= $t['no_selection_msg'] ?></div>
            <form id="field-properties" style="display: none;">
                <div>
                    <label><?= $t['label_input_type'] ?></label>
                    <select id="prop-type">
                        <option value="text"><?= $t['type_text'] ?></option>
                        <option value="email"><?= $t['type_email'] ?></option>
                        <option value="tel"><?= $t['type_tel'] ?></option>
                        <option value="select"><?= $t['type_select'] ?></option>
                        <option value="checkbox"><?= $t['type_checkbox'] ?></option>
                    </select>
                </div>
                <div>
                    <label><?= $t['label_title'] ?></label>
                    <input type="text" id="prop-title" placeholder="<?= $t['placeholder_title'] ?>">
                </div>
                <div id="maxlength-container">
                    <label><?= $t['label_char_limit'] ?></label>
                    <input type="number" id="prop-maxlength" min="1" max="1000" placeholder="<?= $t['placeholder_char_limit'] ?>">
                </div>
                <div class="check">
                    <input type="checkbox" id="prop-required">
                    <label for="prop-required"><?= $t['label_required'] ?></label>
                </div>
                <div id="options-manager" style="display: none; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                    <label><?= $t['label_possible_answers'] ?></label>
                    <div id="options-list" style="margin-top: 8px;"></div>
                    <button type="button" class="btn-add-field" id="btn-add-option">+</button>
                </div>
                <hr>
                <button type="button" class="btn-link-danger" id="prop-delete"><?= $t['btn_delete_field'] ?></button>
            </form>

            <form method="POST" id="save-form">
                <input type="hidden" name="action" value="save_form_config">
                <input type="hidden" name="form_config_json" id="form_config_json">
                <button type="submit" class="btn"><?= $t['btn_save'] ?></button>
            </form>
        </section>

        <section class="box large">
            <header>
                <?php if ($team['teams_logo'] == "1"): ?>
                    <img src="<?= $opensupport_link?>/up/teams/<?= $team['teams_id'] ?>.webp" alt="Logo <?= htmlspecialchars($team['teams_name']) ?>">
                <?php endif; ?>
                <h1><?= $t['title'] ?></h1>
                <p><?= $t['subtitle'] ?></p>
            </header>
            <form>
                <div class="grid-cols-2">
                    <div>
                        <label><?= $t['firstname'] ?> *</label>
                        <input type="text" placeholder="Lorem Ipsum" disabled>
                    </div>
                    <div>
                        <label><?= $t['lastname'] ?> *</label>
                        <input type="text" placeholder="Lorem Ipsum" disabled>
                    </div>
                </div>
                <div>
                    <label><?= $t['email'] ?> *</label>
                    <input type="email" placeholder="Lorem Ipsum" disabled>
                </div>
                <div id="dynamic-fields-container"></div>
                <button type="button" class="btn-add-field" id="btn-add-field">+</button>
                <div>
                    <label><?= $t['subject'] ?> *</label>
                    <input type="text" placeholder="Lorem Ipsum" disabled>
                </div>
                <div>
                    <label><?= $t['description'] ?> *</label>
                    <textarea rows="3" placeholder="Lorem Ipsum" disabled></textarea>
                </div>
                <button type="button" class="btn" disabled><?= $t['submit'] ?></button>
            </form>
        </section>

        <script>
            const i18n = {
                untitled: <?= json_encode($t['js_untitled']) ?>,
                noOption: <?= json_encode($t['js_no_option']) ?>,
                max: <?= json_encode($t['js_max']) ?>,
                answerPrefix: <?= json_encode($t['js_answer_prefix']) ?>,
                none: <?= json_encode($t['js_none']) ?>,
                members: <?= json_encode($t['js_members']) ?>,
                groups: <?= json_encode($t['js_groups']) ?>,
                optionDefault: <?= json_encode($t['js_option_default']) ?>,
                newField: <?= json_encode($t['js_new_field']) ?>
            };

            const initialFormFields = <?= json_encode($current_config, JSON_UNESCAPED_UNICODE) ?>;
            const staffMembers = <?= json_encode($staffMembers, JSON_UNESCAPED_UNICODE) ?>;
            const teamGroups = <?= json_encode($teamGroups, JSON_UNESCAPED_UNICODE) ?>;
            let formFields = Array.isArray(initialFormFields) ? initialFormFields : [];
            let selectedFieldIndex = null;
            let hasUnsavedChanges = false;

            function markDraft() {
                hasUnsavedChanges = true;
            }

            window.addEventListener('beforeunload', (e) => {
                if (hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('dynamic-fields-container');
                const sidebarNoSelect = document.getElementById('no-selection-msg');
                const sidebarProps = document.getElementById('field-properties');
                const propType = document.getElementById('prop-type');
                const propTitle = document.getElementById('prop-title');
                const propMaxLength = document.getElementById('prop-maxlength');
                const maxLengthContainer = document.getElementById('maxlength-container');
                const propRequired = document.getElementById('prop-required');
                const propDelete = document.getElementById('prop-delete');
                const optionsManager = document.getElementById('options-manager');
                const optionsList = document.getElementById('options-list');
                const btnAddOption = document.getElementById('btn-add-option');
                const btnAddField = document.getElementById('btn-add-field');
                const saveForm = document.getElementById('save-form');
                const formConfigJson = document.getElementById('form_config_json');

                function renderFields() {
                    container.innerHTML = '';
                    formFields.forEach((field, index) => {
                        const fieldDiv = document.createElement('div');
                        fieldDiv.className = `custom-field-item ${selectedFieldIndex === index ? 'selected' : ''}`;
                        fieldDiv.draggable = true;
                        fieldDiv.dataset.index = index;

                        const dragHandle = document.createElement('span');
                        dragHandle.className = 'field-drag-bar';
                        dragHandle.textContent = '⋮⋮';
                        fieldDiv.appendChild(dragHandle);

                        const contentWrap = document.createElement('div');
                        if (field.type === 'checkbox') {
                            const checkWrap = document.createElement('div');
                            checkWrap.className = 'check';
                            checkWrap.innerHTML = `
                                <input type="checkbox" disabled>
                                <label>${field.question || i18n.untitled} ${field.required ? '*' : ''}</label>
                            `;
                            contentWrap.appendChild(checkWrap);
                        } else {
                            const label = document.createElement('label');
                            label.textContent = (field.question || i18n.untitled) + (field.required ? ' *' : '');
                            contentWrap.appendChild(label);

                            if (field.type === 'select') {
                                const sel = document.createElement('select');
                                sel.disabled = true;
                                if (field.options && field.options.length > 0) {
                                    field.options.forEach(opt => {
                                        const o = document.createElement('option');
                                        o.textContent = opt.label;
                                        sel.appendChild(o);
                                    });
                                } else {
                                    sel.innerHTML = `<option>${i18n.noOption}</option>`;
                                }
                                contentWrap.appendChild(sel);
                            } else {
                                const inputWrapper = document.createElement('div');
                                inputWrapper.className = 'input-with-limit';
                                const input = document.createElement('input');
                                input.type = field.type === 'tel' ? 'tel' : (field.type === 'email' ? 'email' : 'text');
                                input.placeholder = field.question || '';
                                input.disabled = true;
                                inputWrapper.appendChild(input);

                                if (field.maxlength && parseInt(field.maxlength, 10) > 0) {
                                    const badge = document.createElement('span');
                                    badge.className = 'input-char-limit-badge';
                                    badge.textContent = `${i18n.max} ${field.maxlength}`;
                                    inputWrapper.appendChild(badge);
                                }
                                contentWrap.appendChild(inputWrapper);
                            }
                        }

                        fieldDiv.appendChild(contentWrap);

                        fieldDiv.addEventListener('click', () => {
                            selectField(index);
                        });

                        fieldDiv.addEventListener('dragstart', (e) => {
                            e.dataTransfer.setData('text/plain', index);
                            fieldDiv.style.opacity = '0.4';
                        });

                        fieldDiv.addEventListener('dragend', () => {
                            fieldDiv.style.opacity = '1';
                        });

                        fieldDiv.addEventListener('dragover', (e) => e.preventDefault());

                        fieldDiv.addEventListener('drop', (e) => {
                            e.preventDefault();
                            const originIndex = parseInt(e.dataTransfer.getData('text/plain'), 10);
                            const targetIndex = index;
                            if (originIndex !== targetIndex) {
                                const item = formFields.splice(originIndex, 1)[0];
                                formFields.splice(targetIndex, 0, item);
                                selectField(targetIndex);
                                markDraft();
                                renderFields();
                            }
                        });

                        container.appendChild(fieldDiv);
                    });
                }

                function selectField(index) {
                    selectedFieldIndex = index;
                    const field = formFields[index];
                    if (!field) {
                        sidebarNoSelect.style.display = 'block';
                        sidebarProps.style.display = 'none';
                        return;
                    }

                    sidebarNoSelect.style.display = 'none';
                    sidebarProps.style.display = 'block';

                    propType.value = field.type;
                    propTitle.value = field.question || '';
                    propRequired.checked = !!field.required;

                    if (['text', 'email', 'tel'].includes(field.type)) {
                        maxLengthContainer.style.display = 'block';
                        propMaxLength.value = field.maxlength || '';
                    } else {
                        maxLengthContainer.style.display = 'none';
                        propMaxLength.value = '';
                    }

                    if (field.type === 'select') {
                        optionsManager.style.display = 'block';
                        renderOptionsEditor(field);
                    } else {
                        optionsManager.style.display = 'none';
                    }

                    renderFields();
                }

                function renderOptionsEditor(field) {
                    optionsList.innerHTML = '';
                    if (!field.options) field.options = [];

                    field.options.forEach((opt, optIndex) => {
                        const row = document.createElement('div');
                        row.className = 'option-config-item';

                        const topRow = document.createElement('div');
                        topRow.className = 'option-config-item-info';

                        const inputVal = document.createElement('input');
                        inputVal.type = 'text';
                        inputVal.value = opt.label;
                        inputVal.placeholder = `${i18n.answerPrefix} ${optIndex + 1}`;
                        inputVal.addEventListener('input', (e) => {
                            opt.label = e.target.value;
                            opt.value = e.target.value.toLowerCase().replace(/[^a-z0-9]/g, '_');
                            markDraft();
                            renderFields();
                        });

                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.textContent = '✕';
                        removeBtn.onclick = () => {
                            field.options.splice(optIndex, 1);
                            markDraft();
                            renderOptionsEditor(field);
                            renderFields();
                        };

                        topRow.appendChild(inputVal);
                        topRow.appendChild(removeBtn);
                        row.appendChild(topRow);

                        const ruleBox = document.createElement('div');
                        ruleBox.className = 'option-rule-box';

                        const assignType = opt.assign_to ? opt.assign_to.type : '';
                        const assignVal = opt.assign_to ? opt.assign_to.value : '';

                        let selectHtml = `
                            <select class="dispatch-select">
                                <option value="">${i18n.none}</option>
                                <optgroup label="${i18n.members}">
                        `;
                        staffMembers.forEach(m => {
                            const selected = (assignType === 'user' && assignVal == m.users_id) ? 'selected' : '';
                            selectHtml += `<option value="user:${m.users_id}" ${selected}>👤 ${m.users_first_name} ${m.users_last_name} (${m.users_email})</option>`;
                        });
                        selectHtml += `</optgroup><optgroup label="${i18n.groups}">`;
                        teamGroups.forEach(g => {
                            const selected = (assignType === 'group' && assignVal == g) ? 'selected' : '';
                            selectHtml += `<option value="group:${g}" ${selected}>👥 ${g}</option>`;
                        });
                        selectHtml += `</optgroup></select>`;

                        ruleBox.innerHTML = selectHtml;
                        ruleBox.querySelector('.dispatch-select').addEventListener('change', (e) => {
                            const val = e.target.value;
                            if (!val) {
                                delete opt.assign_to;
                            } else {
                                const [type, target] = val.split(':');
                                opt.assign_to = { type: type, value: target };
                            }
                            markDraft();
                        });

                        row.appendChild(ruleBox);
                        optionsList.appendChild(row);
                    });
                }

                propType.addEventListener('change', (e) => {
                    if (selectedFieldIndex === null) return;
                    const field = formFields[selectedFieldIndex];
                    field.type = e.target.value;

                    if (field.type === 'select') {
                        if (!field.options || field.options.length === 0) {
                            field.options = [{ label: i18n.optionDefault, value: 'opt_1' }];
                        }
                        optionsManager.style.display = 'block';
                        renderOptionsEditor(field);
                    } else {
                        optionsManager.style.display = 'none';
                        delete field.options;
                    }

                    if (['text', 'email', 'tel'].includes(field.type)) {
                        maxLengthContainer.style.display = 'block';
                    } else {
                        maxLengthContainer.style.display = 'none';
                        delete field.maxlength;
                    }

                    markDraft();
                    renderFields();
                });

                propTitle.addEventListener('input', (e) => {
                    if (selectedFieldIndex === null) return;
                    formFields[selectedFieldIndex].question = e.target.value;
                    markDraft();
                    renderFields();
                });

                propMaxLength.addEventListener('input', (e) => {
                    if (selectedFieldIndex === null) return;
                    const val = parseInt(e.target.value, 10);
                    if (!isNaN(val) && val > 0) {
                        formFields[selectedFieldIndex].maxlength = val;
                    } else {
                        delete formFields[selectedFieldIndex].maxlength;
                    }
                    markDraft();
                    renderFields();
                });

                propRequired.addEventListener('change', (e) => {
                    if (selectedFieldIndex === null) return;
                    formFields[selectedFieldIndex].required = e.target.checked;
                    markDraft();
                    renderFields();
                });

                propDelete.addEventListener('click', () => {
                    if (selectedFieldIndex === null) return;
                    formFields.splice(selectedFieldIndex, 1);
                    selectedFieldIndex = null;
                    selectField(null);
                    markDraft();
                    renderFields();
                });

                btnAddOption.addEventListener('click', () => {
                    if (selectedFieldIndex === null) return;
                    const field = formFields[selectedFieldIndex];
                    const nextNum = (field.options.length + 1);
                    field.options.push({ label: `${i18n.answerPrefix} ${nextNum}`, value: `reponse_${nextNum}` });
                    markDraft();
                    renderOptionsEditor(field);
                    renderFields();
                });

                btnAddField.addEventListener('click', () => {
                    const newField = { type: 'text', question: i18n.newField, required: false };
                    formFields.push(newField);
                    selectField(formFields.length - 1);
                    markDraft();
                });

                saveForm.addEventListener('submit', () => {
                    hasUnsavedChanges = false;
                    formConfigJson.value = JSON.stringify(formFields);
                });

                renderFields();
            });
        </script>
    </main>
    <footer>Powered by OpenSupport</footer>
    <script src="<?= $opensupport_link?>/src/js/main.js"></script>
</body>
</html>