<?php
// Funktion zur Erstellung der ausbildungen
function krp_ausbildung_create_section_callback() {
    $ausbildungen = get_option('krp_saved_ausbildungen', array());
    $krp_company_standorte = get_option('krp_kontakt_allgemein_company_standorte_field', []);
    ?>

    <style>
        .ausbildung_entry {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            position: relative;
        }
        .ausbildung_title {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .ausbildung_title h3 {
            margin: 0;
            flex-grow: 1;
        }
        .toggle_arrow {
            width: 0;
            height: 0;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            border-top: 6px solid #333;
            margin-right: 10px;
            transition: transform 0.3s ease;
        }
        .toggle_arrow.open {
            transform: rotate(180deg);
        }
        .ausbildung_details {
            display: none;
            margin-top: 10px;
        }
        .ausbildung_details.open {
            display: block;
        }
        .delete_ausbildung_button {
            cursor: pointer;
            margin-left: 5px;
        }
        .ausbildung_buttons {
            margin-bottom: 20px;
        }
        .krp-image-preview-container {
            margin-top: 20px;
        }
        .krp-image-preview-container img {
            max-width: 100%;
            height: auto;
            max-height: 200px;
            margin-top: 10px;
        }
    </style>

    <div class="wrap">
        <h3>Übersicht Ausbildungen</h3>
        <p>Über die jeweiligen Buttons kannst du neue Ausbildungen erstellen und speichern.</p>

        <form id="krp_ausbildungen_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_krp_ausbildungen">
            <?php wp_nonce_field('krp_save_ausbildungen_nonce'); ?>

            <div class="ausbildung_buttons">
                <button type="button" id="add_ausbildung_button">Ausbildung erstellen</button>
                <button type="submit" name="submit">Ausbildungen speichern</button>
            </div>

            <div id="ausbildungen_container">
                <?php foreach ($ausbildungen as $key => $ausbildung) : ?>
                    <div class="ausbildung_entry">
                        <div class="ausbildung_title" data-ausbildung="<?php echo $key; ?>">
                            <div class="toggle_arrow"></div>
                            <h3>#<?php echo $key + 1; ?> - <?php echo esc_html($ausbildung['ausbildung_title']); ?></h3>
                            <button class="delete_ausbildung_button" data-ausbildung="<?php echo $key; ?>">Löschen</button>
                        </div>
                        <div class="ausbildung_details" id="ausbildung_details_<?php echo $key; ?>">
                            <table class="form-table">
                                <!-- Ausbildung Name -->
                                <tr>
                                    <th><label for="ausbildung_title_<?php echo $key; ?>">Ausbildung Name</label></th>
                                    <td><input type="text" id="ausbildung_title_<?php echo $key; ?>" name="ausbildung_title[]" class="regular-text" value="<?php echo esc_attr($ausbildung['ausbildung_title']); ?>" required></td>
                                </tr>
                                <!-- Ausbildung Bereich -->
                                <tr>
                                    <th><label for="ausbildung_bereich_<?php echo $key + 1; ?>">Ausbildung Bereich</label></th>
                                    <td>
                                        <ul id="ausbildung_bereich_list_<?php echo $key + 1; ?>">
                                            <?php foreach ($ausbildung['ausbildung_bereich'] as $bereichKey => $bereich) : ?>
                                                <li>
                                                    <input type="text" name="ausbildung_bereich[<?php echo $key; ?>][]" class="regular-text" value="<?php echo esc_attr($bereich); ?>" required>
                                                    <?php if ($bereichKey > 0) : ?>
                                                        <button class="delete_bereich_button" data-ausbildung="<?php echo $key; ?>">X</button>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <button type="button" class="add_ausbildung_bereich_button" data-ausbildung="<?php echo $key; ?>">Weiteren Bereich hinzufügen</button>
                                    </td>
                                </tr>
                                <!-- Ausbildung Bild -->
                                <tr>
                                    <th><label for="ausbildung_image_<?php echo $key; ?>">Ausbildung Bild</label></th>
                                    <td>
                                        <input type="hidden" id="ausbildung_image_<?php echo $key; ?>" name="ausbildung_image[]" class="ausbildung_image_url" value="<?php echo esc_url($ausbildung['ausbildung_image']); ?>">
                                        <button type="button" class="upload_image_button" data-target="#ausbildung_image_<?php echo $key; ?>">Bild auswählen</button>
                                        <div class="krp-image-preview-container">
                                            <?php if (!empty($ausbildung['ausbildung_image'])): ?>
                                                <img src="<?php echo esc_url($ausbildung['ausbildung_image']); ?>" alt="Bildvorschau">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Firmen Infos -->
                                <tr>
                                    <th><label for="ausbildung_company_info_<?php echo $key; ?>">Firmen Infos</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="ausbildung_company_info_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'ausbildung_company_info_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'ausbildung_company_info_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'ausbildung_company_info_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('ausbildung_company_info_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'ausbildung_company_info_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('ausbildung_company_info_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'ausbildung_company_info_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'ausbildung_company_info_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'ausbildung_company_info_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'ausbildung_company_info_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'ausbildung_company_info_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'ausbildung_company_info_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="ausbildung_company_info_<?php echo $key; ?>" name="ausbildung_company_info[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_company_info']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Standort -->
                                <tr>
                                    <th><label for="ausbildung_standort_<?php echo $key; ?>">Standort</label></th>
                                    <td>
                                        <select id="ausbildung_standort_<?php echo $key; ?>" name="ausbildung_standort[]" class="regular-text">
                                            <option>Bitte auswählen</option>
                                            <?php foreach ($krp_company_standorte as $standort) : ?>
                                                <option value="<?php echo esc_attr($standort); ?>" <?php selected($ausbildung['ausbildung_standort'], $standort); ?>><?php echo esc_html($standort); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <!-- ausbildung Tätigkeiten -->
                                <tr>
                                    <th><label for="ausbildung_tasks_<?php echo $key; ?>">Ausbildung Tätigkeiten</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="ausbildung_tasks_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'ausbildung_tasks_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'ausbildung_tasks_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'ausbildung_tasks_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('ausbildung_tasks_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'ausbildung_tasks_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('ausbildung_tasks_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'ausbildung_tasks_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'ausbildung_tasks_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'ausbildung_tasks_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'ausbildung_tasks_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'ausbildung_tasks_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'ausbildung_tasks_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="ausbildung_tasks_<?php echo $key; ?>" name="Ausbildung_tasks[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_tasks']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- ausbildung Bewerbung -->
                                <tr>
                                    <th><label for="ausbildung_application_<?php echo $key; ?>">Ausbildung Bewerbung</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="ausbildung_application_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'ausbildung_application_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'ausbildung_application_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'ausbildung_application_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('ausbildung_application_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'ausbildung_application_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('ausbildung_application_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'ausbildung_application_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'ausbildung_application_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'ausbildung_application_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'ausbildung_application_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'ausbildung_application_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'ausbildung_application_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="ausbildung_application_<?php echo $key; ?>" name="ausbildung_application[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_application']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- ausbildung Bewerbung PDF -->
                                <tr>
                                    <th><label for="ausbildung_application_pdf_<?php echo $key; ?>">Ausbildung Bewerbung PDF</label></th>
                                    <td>
                                        <input type="file" id="ausbildung_application_pdf_<?php echo $key; ?>" name="ausbildung_application_pdf[]" class="regular-text">
                                        <?php if (!empty($ausbildung['ausbildung_application_pdf'])): ?>
                                            <a href="<?php echo esc_url($ausbildung['ausbildung_application_pdf']); ?>" target="_blank">PDF ansehen</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Kontakt Auswahl-->
                                <tr>
                                    <th><label for="ausbildung_select_contact_ausbildung_details_<?php echo $key; ?>">Kontakt Auswahl für ausbildung</label></th>
                                    <td>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_<?php echo $key; ?>" name="selected_contact_ausbildung_details_name[]">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            $saved_contacts = get_option('krp_saved_contacts', array());;
                                            foreach ($saved_contacts as $contact) {
                                                $contact_name_ausbildung_details = esc_html($contact['contact_name']);
                                                $contact_abteilung_ausbildung_details = implode(' und ', array_map('esc_html', $contact['contact_abteilung']));
                                                $contact_name_abteilung_ausbildung_details = $contact_name_ausbildung_details . ' , ' . $contact_abteilung_ausbildung_details;
                                                echo '<option value="' . esc_attr($contact_name_abteilung_ausbildung_details) . '"' . selected($ausbildung['selected_contact_job_details_name'], $contact_name_abteilung_ausbildung_details, false) . '>' . esc_html($contact_name_abteilung_ausbildung_details) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_tel_<?php echo $key; ?>" name="selected_contact_ausbildung_details_tel[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            foreach ($saved_contacts as $contact) {
                                                $contact_tel_ausbildung_details = esc_html($contact['contact_tel']);
                                                echo '<option value="' . esc_attr($contact_tel_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_tel'], $contact_tel_ausbildung_details, false) . '>' . esc_html($contact_tel_ausbildung_details) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_email_<?php echo $key; ?>" name="selected_contact_ausbildung_details_email[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            foreach ($saved_contacts as $contact) {
                                                $contact_email_ausbildung_details = esc_html($contact['contact_email']);
                                                echo '<option value="' . esc_attr($contact_email_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_email'], $contact_email_ausbildung_details, false) . '>' . esc_html($contact_email_ausbildung_details) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_info_<?php echo $key; ?>" name="selected_contact_ausbildung_details_info[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            foreach ($saved_contacts as $contact) {
                                                $contact_info_ausbildung_details = esc_html($contact['contact_info']);
                                                echo '<option value="' . esc_attr($contact_info_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_info'], $contact_info_ausbildung_details, false) . '>' . esc_html($contact_info_ausbildung_details) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_image_url_<?php echo $key; ?>" name="selected_contact_ausbildung_details_image_url[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            foreach ($saved_contacts as $contact) {
                                                $contact_image_url_ausbildung_details = esc_url_raw($contact['contact_image_url']);
                                                echo '<option value="' . esc_attr($contact_image_url_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_image_url'], $contact_image_url_ausbildung_details, false) . '>' . esc_html($contact_image_url_ausbildung_details) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    </div>

    <script>
        (function($) {
            $(document).ready(function() {
                // Bildauswahl-Button
                $(document).on('click', '.upload_image_button', function(e) {
                    e.preventDefault();

                    var button = $(this);
                    var targetInput = $(button.data('target'));

                    // Auswahl-Dialog für Medien öffnen
                    var frame = wp.media({
                        title: 'Bild auswählen',
                        button: { text: 'Bild auswählen' },
                        multiple: false
                    });

                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        targetInput.val(attachment.url);
                        button.siblings('.krp-image-preview-container').find('img').remove();
                        button.siblings('.krp-image-preview-container').append('<img src="' + attachment.url + '" alt="Bildvorschau">');
                    });

                    frame.open();
                });

                // ausbildung hinzufügen
                $('#add_ausbildung_button').click(function() {
                    const ausbildungIndex = $('#ausbildungen_container .ausbildung_entry').length;
                    const ausbildungHtml = `
                <div class="ausbildung_entry">
                    <div class="ausbildung_title" data-ausbildung="${ausbildungIndex}">
                        <div class="toggle_arrow"></div>
                        <h3>#${ausbildungIndex + 1} - Neue Ausbildung</h3>
                        <button class="delete_ausbildung_button" data-ausbildung="${ausbildungIndex}">Löschen</button>
                    </div>
                    <div class="ausbildung_details" id="ausbildung_details_${ausbildungIndex}">
                        <table class="form-table">
                            <!-- Ausbildung Name -->
                            <tr>
                                <th><label for="ausbildung_title_${ausbildungIndex}">Ausbildung Name</label></th>
                                <td><input type="text" id="ausbildung_title_${ausbildungIndex}" name="ausbildung_title[]" class="regular-text" required></td>
                            </tr>
                            <!-- Ausbildung Bereich -->
                            <tr>
                                <th><label for="ausbildung_bereich_${ausbildungIndex + 1}">Ausbildung Bereich</label></th>
                                <td>
                                    <ul id="ausbildung_bereich_list_${ausbildungIndex + 1}">
                                        <li>
                                            <input type="text" name="ausbildung_bereich[${ausbildungIndex}][]" class="regular-text" required>
                                            <button class="delete_bereich_button" data-ausbildung="${ausbildungIndex}">X</button>
                                        </li>
                                    </ul>
                                    <button type="button" class="add_ausbildung_bereich_button" data-ausbildung="${ausbildungIndex}">Weiteren Bereich hinzufügen</button>
                                </td>
                            </tr>
                            <!-- Ausbildung Bild -->
                            <tr>
                                <th><label for="ausbildung_image_${ausbildungIndex}">Ausbildung Bild</label></th>
                                <td>
                                    <input type="hidden" id="ausbildung_image_${ausbildungIndex}" name="ausbildung_image[]" class="ausbildung_image_url">
                                    <button type="button" class="upload_image_button" data-target="#ausbildung_image_${ausbildungIndex}">Bild auswählen</button>
                                    <div class="krp-image-preview-container"></div>
                                </td>
                            </tr>
                            <!-- Firmen Infos -->
                            <tr>
                                <th><label for="ausbildung_company_info_${ausbildungIndex}">Firmen Infos</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="ausbildung_company_info_${ausbildungIndex}">
                                            <button type="button" onclick="toggleTag('b', 'ausbildung_company_info_${ausbildungIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'ausbildung_company_info_${ausbildungIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'ausbildung_company_info_${ausbildungIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('ausbildung_company_info_${ausbildungIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'ausbildung_company_info_${ausbildungIndex}')">List</button>
                                            <button type="button" onclick="insertLink('ausbildung_company_info_${ausbildungIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'ausbildung_company_info_${ausbildungIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'ausbildung_company_info_${ausbildungIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'ausbildung_company_info_${ausbildungIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'ausbildung_company_info_${ausbildungIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'ausbildung_company_info_${ausbildungIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'ausbildung_company_info_${ausbildungIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="ausbildung_company_info_${ausbildungIndex}" name="ausbildung_company_info[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_company_info']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Standort -->
                            <tr>
                                <th><label for="ausbildung_standort_${ausbildungIndex}">Standort</label></th>
                                <td>
                                    <select id="ausbildung_standort_${ausbildungIndex}" name="ausbildung_standort[]" class="regular-text">
                                        <option>Bitte auswählen</option>
                                        <?php foreach ($krp_company_standorte as $standort) : ?>
                                            <option value="<?php echo esc_attr($standort); ?>"><?php echo esc_html($standort); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- Ausbildung Tätigkeiten -->
                            <tr>
                                <th><label for="ausbildung_tasks_${ausbildungIndex}">Ausbildung Tätigkeiten</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="ausbildung_tasks_${ausbildungIndex}">
                                            <button type="button" onclick="toggleTag('b', 'ausbildung_tasks_${ausbildungIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'ausbildung_tasks_${ausbildungIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'ausbildung_tasks_${ausbildungIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('ausbildung_tasks_${ausbildungIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'ausbildung_tasks_${ausbildungIndex}')">List</button>
                                            <button type="button" onclick="insertLink('ausbildung_tasks_${ausbildungIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'ausbildung_tasks_${ausbildungIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'ausbildung_tasks_${ausbildungIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'ausbildung_tasks_${ausbildungIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'ausbildung_tasks_${ausbildungIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'ausbildung_tasks_${ausbildungIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'ausbildung_tasks_${ausbildungIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="ausbildung_tasks_${ausbildungIndex}" name="ausbildung_tasks[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_tasks']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Ausbildung Bewerbung -->
                            <tr>
                                <th><label for="ausbildung_application_${ausbildungIndex}">Ausbildung Bewerbung</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="ausbildung_application_${ausbildungIndex}">
                                            <button type="button" onclick="toggleTag('b', 'ausbildung_application_${ausbildungIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'ausbildung_application_${ausbildungIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'ausbildung_application_${ausbildungIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('ausbildung_application_${ausbildungIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'ausbildung_application_${ausbildungIndex}')">List</button>
                                            <button type="button" onclick="insertLink('ausbildung_application_${ausbildungIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'ausbildung_application_${ausbildungIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'ausbildung_application_${ausbildungIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'ausbildung_application_${ausbildungIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'ausbildung_application_${ausbildungIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'ausbildung_application_${ausbildungIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'ausbildung_application_${ausbildungIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="ausbildung_application_${ausbildungIndex}" name="ausbildung_application[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($ausbildung['ausbildung_application']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Ausbildung Bewerbung PDF -->
                            <tr>
                                <th><label for="ausbildung_application_pdf_${ausbildungIndex}">Ausbildung Bewerbung PDF</label></th>
                                <td>
                                    <input type="file" id="ausbildung_application_pdf_${ausbildungIndex}" name="ausbildung_application_pdf[]" class="regular-text">
                                </td>
                            </tr>
                            <!-- Kontakt Auswahl-->
                                <tr>
                                    <th><label for="ausbildung_select_contact_ausbildung_details_${ausbildungIndex}">Kontakt Auswahl für ausbildung</label></th>
                                    <td>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_${ausbildungIndex}" name="selected_contact_ausbildung_details_name[]">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    $saved_contacts = get_option('krp_saved_contacts', array());;
                    foreach ($saved_contacts as $contact) {
                        $contact_name_ausbildung_details = esc_html($contact['contact_name']);
                        $contact_abteilung_ausbildung_details = implode(' und ', array_map('esc_html', $contact['contact_abteilung']));
                        $contact_name_abteilung_ausbildung_details = $contact_name_ausbildung_details . ' , ' . $contact_abteilung_ausbildung_details;
                        echo '<option value="' . esc_attr($contact_name_abteilung_ausbildung_details) . '"' . selected($ausbildung['selected_contact_job_details_name'], $contact_name_abteilung_ausbildung_details, false) . '>' . esc_html($contact_name_abteilung_ausbildung_details) . '</option>';
                    }
                    ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_tel_${ausbildungIndex}" name="selected_contact_ausbildung_details_tel[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    foreach ($saved_contacts as $contact) {
                        $contact_tel_ausbildung_details = esc_html($contact['contact_tel']);
                        echo '<option value="' . esc_attr($contact_tel_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_tel'], $contact_tel_ausbildung_details, false) . '>' . esc_html($contact_tel_ausbildung_details) . '</option>';
                    }
                    ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_email_${ausbildungIndex}" name="selected_contact_ausbildung_details_email[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    foreach ($saved_contacts as $contact) {
                        $contact_email_ausbildung_details = esc_html($contact['contact_email']);
                        echo '<option value="' . esc_attr($contact_email_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_email'], $contact_email_ausbildung_details, false) . '>' . esc_html($contact_email_ausbildung_details) . '</option>';
                    }
                    ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_info_${ausbildungIndex}" name="selected_contact_ausbildung_details_info[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    foreach ($saved_contacts as $contact) {
                        $contact_info_ausbildung_details = esc_html($contact['contact_info']);
                        echo '<option value="' . esc_attr($contact_info_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_info'], $contact_info_ausbildung_details, false) . '>' . esc_html($contact_info_ausbildung_details) . '</option>';
                    }
                    ?>
                                        </select>
                                        <select class="contact-select" id="ausbildung_select_contact_ausbildung_details_image_url_${ausbildungIndex}" name="selected_contact_ausbildung_details_image_url[]" style="display: none;">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    foreach ($saved_contacts as $contact) {
                        $contact_image_url_ausbildung_details = esc_url_raw($contact['contact_image_url']);
                        echo '<option value="' . esc_attr($contact_image_url_ausbildung_details) . '"' . selected($ausbildung['selected_contact_ausbildung_details_image_url'], $contact_image_url_ausbildung_details, false) . '>' . esc_html($contact_image_url_ausbildung_details) . '</option>';
                    }
                    ?>
                                        </select>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>`;
                    $('#ausbildungen_container').append(ausbildungHtml);
                });

                // ausbildung löschen
                $(document).on('click', '.delete_ausbildung_button', function() {
                    const ausbildungIndex = $(this).data('ausbildung');
                    $(this).closest('.ausbildung_entry').remove();
                });

                // ausbildung Bereich hinzufügen
                $(document).on('click', '.add_ausbildung_bereich_button', function() {
                    const ausbildungIndex = $(this).data('ausbildung');
                    const BereichHtml = `
                <li>
                    <input type="text" name="ausbildung_bereich[${ausbildungIndex}][]" class="regular-text" required>
                    <button class="delete_bereich_button" data-ausbildung="${ausbildungIndex}">X</button>
                </li>`;
                    $(`#ausbildung_bereich_list_${ausbildungIndex + 1}`).append(BereichHtml);
                });

                // ausbildung Bereich löschen
                $(document).on('click', '.delete_bereich_button', function() {
                    $(this).closest('li').remove();
                });

                // ausbildung Titel aufklappen/zu klappen
                $(document).on('click', '.ausbildung_title', function() {
                    const ausbildungIndex = $(this).data('ausbildung');
                    $(`#ausbildung_details_${ausbildungIndex}`).toggleClass('open');
                    $(this).find('.toggle_arrow').toggleClass('open');
                });
            });
        })(jQuery);
    </script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            // Speichere die Kontaktinformationen in einem JavaScript-Objekt
            var contacts = <?php echo json_encode($saved_contacts); ?>;

            // Funktion zum Aktualisieren der anderen Select-Felder
            function updateContactDetails(contact) {
                var key = contact.key; // Der Key sollte hier aus dem Kontakt-Daten erhalten werden
                document.querySelector(`#ausbildung_select_contact_ausbildung_details_tel_${key}`).value = contact.contact_tel || '';
                document.querySelector(`#ausbildung_select_contact_ausbildung_details_email_${key}`).value = contact.contact_email || '';
                document.querySelector(`#ausbildung_select_contact_ausbildung_details_info_${key}`).value = contact.contact_info || '';
                document.querySelector(`#ausbildung_select_contact_ausbildung_details_image_url_${key}`).value = contact.contact_image_url || '';
            }

            // Event Listener für die Änderung des Kontakt-Selects
            document.querySelectorAll(".contact-select[name='selected_contact_ausbildung_details_name[]']").forEach(function(selectElement) {
                selectElement.addEventListener("change", function() {
                    var selectedContactNameAbteilung = this.value;
                    var key = this.id.split('_').pop(); // Extrahiere den Schlüssel aus der ID

                    // Finde den Kontakt basierend auf dem Namen und der Abteilung
                    var [selectedContactName, selectedContactAbteilung] = selectedContactNameAbteilung.split(' , ');

                    var contact = contacts.find(c => {
                        var abteilung = c.contact_abteilung.join(' und ');
                        return c.contact_name === selectedContactName && abteilung === selectedContactAbteilung;
                    });

                    if (contact) {
                        contact.key = key; // Setze den Schlüssel im Kontakt-Objekt
                        // Update die anderen Select-Felder basierend auf dem ausgewählten Kontakt
                        updateContactDetails(contact);
                    } else {
                        // Falls kein Kontakt gefunden wird, setze die Werte auf leer
                        document.querySelector(`#ausbildung_select_contact_ausbildung_details_tel_${key}`).value = '';
                        document.querySelector(`#ausbildung_select_contact_ausbildung_details_email_${key}`).value = '';
                        document.querySelector(`#ausbildung_select_contact_ausbildung_details_info_${key}`).value = '';
                        document.querySelector(`#ausbildung_select_contact_ausbildung_details_image_url_${key}`).value = '';
                    }
                });
            });
        });
    </script>
    <script>
        function getEditor(id) {
            return document.getElementById(id);
        }

        function toggleTag(tagName, editorId, secondTagName = null) {
            const editor = getEditor(editorId);
            const text = editor.value;
            const selectionStart = editor.selectionStart;
            const selectionEnd = editor.selectionEnd;
            const selectedText = text.substring(selectionStart, selectionEnd);
            const regex = new RegExp(`(<${tagName}>)([^<]*)(<\/${tagName}>)`, 'i');

            if (selectedText.match(regex)) {
                editor.value = text.slice(0, selectionStart) + text.slice(selectionStart, selectionEnd).replace(regex, '$2') + text.slice(selectionEnd);
            } else {
                const newText = `<${tagName}>${selectedText}</${tagName}>`;
                if (secondTagName) {
                    editor.value = text.slice(0, selectionStart) + `<${secondTagName}>${newText}</${secondTagName}>` + text.slice(selectionEnd);
                } else {
                    editor.value = text.slice(0, selectionStart) + newText + text.slice(selectionEnd);
                }
            }
            editor.selectionStart = editor.selectionEnd = selectionStart + newText.length;
        }

        function insertLink(editorId) {
            const editor = getEditor(editorId);
            const url = prompt('Enter the URL:');
            const text = editor.value;
            const selectionStart = editor.selectionStart;
            const selectionEnd = editor.selectionEnd;
            const selectedText = text.substring(selectionStart, selectionEnd);

            if (url) {
                const linkText = selectedText || url;
                const newText = `<a href="${url}">${linkText}</a>`;
                editor.value = text.slice(0, selectionStart) + newText + text.slice(selectionEnd);
                editor.selectionStart = editor.selectionEnd = selectionStart + newText.length;
            }
        }

        function insertLineBreak(editorId) {
            const editor = getEditor(editorId);
            const text = editor.value;
            const selectionStart = editor.selectionStart;
            const selectionEnd = editor.selectionEnd;
            const newText = `<br>`;
            editor.value = text.slice(0, selectionStart) + newText + text.slice(selectionEnd);
            editor.selectionStart = editor.selectionEnd = selectionStart + newText.length;
        }
    </script>
    <?php
}

// Funktion zum Speichern der ausbildungen
function krp_save_ausbildungen() {
    if (isset($_POST['action']) && $_POST['action'] === 'save_krp_ausbildungen') {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'krp_save_ausbildungen_nonce')) {
            wp_die('Ungültige Anfrage.');
        }

        // Felder sanitieren
        $ausbildung_titles = isset($_POST['ausbildung_title']) ? array_map('sanitize_text_field', $_POST['ausbildung_title']) : array();
        $ausbildung_bereiche = isset($_POST['ausbildung_bereich']) ? array_map(function($bereichs) {
            return array_map('sanitize_text_field', $bereichs);
        }, $_POST['ausbildung_bereich']) : array();
        $ausbildung_standorte = isset($_POST['ausbildung_standort']) ? array_map('sanitize_text_field', $_POST['ausbildung_standort']) : array();

        $selected_contacts_ausbildung_details_name = isset($_POST['selected_contact_ausbildung_details_name']) ? array_map('sanitize_text_field', $_POST['selected_contact_ausbildung_details_name']) : array();
        $selected_contacts_ausbildung_details_abteilung = isset($_POST['selected_contact_ausbildung_details_abteilung']) ? array_map('sanitize_text_field', $_POST['selected_contact_ausbildung_details_abteilung']) : array();
        $selected_contacts_ausbildung_details_tel = isset($_POST['selected_contact_ausbildung_details_tel']) ? array_map('sanitize_text_field', $_POST['selected_contact_ausbildung_details_tel']) : array();
        $selected_contacts_ausbildung_details_email = isset($_POST['selected_contact_ausbildung_details_email']) ? array_map('sanitize_text_field', $_POST['selected_contact_ausbildung_details_email']) : array();
        $selected_contacts_ausbildung_details_info = isset($_POST['selected_contact_ausbildung_details_info']) ? array_map('sanitize_text_field', $_POST['selected_contact_ausbildung_details_info']) : array();
        $selected_contacts_ausbildung_details_image_url = isset($_POST['selected_contact_ausbildung_details_image_url']) ? array_map('esc_url_raw', $_POST['selected_contact_ausbildung_details_image_url']) : array();

        // Felder mit wp_kses_post verarbeiten (für die Validierung auf HTML)
        $ausbildung_company_infos = isset($_POST['ausbildung_company_info']) ? array_map('wp_kses_post', $_POST['ausbildung_company_info']) : array();
        $ausbildung_tasks = isset($_POST['ausbildung_tasks']) ? array_map('wp_kses_post', $_POST['ausbildung_tasks']) : array();
        $ausbildung_applications = isset($_POST['ausbildung_application']) ? array_map('wp_kses_post', $_POST['ausbildung_application']) : array();

        // PDF-Dateien verarbeiten
        $ausbildung_application_pdfs = isset($_FILES['ausbildung_application_pdf']) ? $_FILES['ausbildung_application_pdf'] : array();
        $ausbildung_images = isset($_POST['ausbildung_image']) ? array_map('esc_url_raw', $_POST['ausbildung_image']) : array();

        $ausbildungen = array();
        foreach ($ausbildung_titles as $key => $title) {
            $pdf_url = '';

            if (isset($ausbildung_application_pdfs['name'][$key]) && !empty($ausbildung_application_pdfs['name'][$key])) {
                // Stelle sicher, dass die Datei-Array-Struktur korrekt verarbeitet wird
                $file = array(
                    'name' => $ausbildung_application_pdfs['name'][$key],
                    'type' => $ausbildung_application_pdfs['type'][$key],
                    'tmp_name' => $ausbildung_application_pdfs['tmp_name'][$key],
                    'error' => $ausbildung_application_pdfs['error'][$key],
                    'size' => $ausbildung_application_pdfs['size'][$key],
                );

                // Datei hochladen
                $uploaded_pdf = wp_handle_upload($file, array('test_form' => false));

                if (!isset($uploaded_pdf['error'])) {
                    $pdf_url = $uploaded_pdf['url'];
                } else {
                    // Falls ein Fehler auftritt, gib ihn aus oder logge ihn
                    error_log('Upload-Fehler: ' . $uploaded_pdf['error']);
                }
            }

            // ausbildung-Daten sammeln
            $ausbildungen[] = array(
                'ausbildung_title' => $title,
                'ausbildung_bereich' => isset($ausbildung_bereiche[$key]) ? $ausbildung_bereiche[$key] : array(),
                'ausbildung_company_info' => isset($ausbildung_company_infos[$key]) ? $ausbildung_company_infos[$key] : '',
                'ausbildung_standort' => isset($ausbildung_standorte[$key]) ? $ausbildung_standorte[$key] : '',
                'ausbildung_tasks' => isset($ausbildung_tasks[$key]) ? $ausbildung_tasks[$key] : '',
                'ausbildung_application' => isset($ausbildung_applications[$key]) ? $ausbildung_applications[$key] : '',
                'ausbildung_application_pdf' => $pdf_url,
                'ausbildung_image' => isset($ausbildung_images[$key]) ? $ausbildung_images[$key] : '', // Bild-URL hinzufügen
                'selected_contact_ausbildung_details_name' => isset($selected_contacts_ausbildung_details_name[$key]) ? $selected_contacts_ausbildung_details_name[$key] : '',
                'selected_contact_ausbildung_details_abteilung' => isset($selected_contacts_ausbildung_details_abteilung[$key]) ? $selected_contacts_ausbildung_details_abteilung[$key] : '',
                'selected_contact_ausbildung_details_tel' => isset($selected_contacts_ausbildung_details_tel[$key]) ? $selected_contacts_ausbildung_details_tel[$key] : '',
                'selected_contact_ausbildung_details_email' => isset($selected_contacts_ausbildung_details_email[$key]) ? $selected_contacts_ausbildung_details_email[$key] : '',
                'selected_contact_ausbildung_details_info' => isset($selected_contacts_ausbildung_details_info[$key]) ? $selected_contacts_ausbildung_details_info[$key] : '',
                'selected_contact_ausbildung_details_image_url' => isset($selected_contacts_ausbildung_details_image_url[$key]) ? $selected_contacts_ausbildung_details_image_url[$key] : '',
            );
        }

        // ausbildungen in der Datenbank speichern
        update_option('krp_saved_ausbildungen', $ausbildungen);

        // Zurück zur vorherigen Seite mit Erfolgsmeldung
        wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
        exit;
    }
}

add_action('admin_post_save_krp_ausbildungen', 'krp_save_ausbildungen');