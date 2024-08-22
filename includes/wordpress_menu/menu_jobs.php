<?php
// Funktion zur Erstellung der Jobs
function krp_job_create_section_callback() {
    $jobs = get_option('krp_saved_jobs', array());
    $krp_company_standorte = get_option('krp_kontakt_allgemein_company_standorte_field', []);
    ?>

    <style>
        .job_entry {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            position: relative;
        }
        .job_title {
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .job_title h3 {
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
        .job_details {
            display: none;
            margin-top: 10px;
        }
        .job_details.open {
            display: block;
        }
        .delete_job_button {
            cursor: pointer;
            margin-left: 5px;
        }
        .job_buttons {
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
        <h3>Übersicht Jobs</h3>
        <p>Über die jeweiligen Buttons kannst du neue Jobs erstellen und speichern.</p>

        <form id="krp_jobs_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_krp_jobs">
            <?php wp_nonce_field('krp_save_jobs_nonce'); ?>

            <div class="job_buttons">
                <button type="button" id="add_job_button">Job erstellen</button>
                <button type="submit" name="submit">Jobs speichern</button>
            </div>

            <div id="jobs_container">
                <?php foreach ($jobs as $key => $job) : ?>
                    <div class="job_entry">
                        <div class="job_title" data-job="<?php echo $key; ?>">
                            <div class="toggle_arrow"></div>
                            <h3>#<?php echo $key + 1; ?> - <?php echo esc_html($job['job_title']); ?></h3>
                            <button class="delete_job_button" data-job="<?php echo $key; ?>">Löschen</button>
                        </div>
                        <div class="job_details" id="job_details_<?php echo $key; ?>">
                            <table class="form-table">
                                <!-- Job Name -->
                                <tr>
                                    <th><label for="job_title_<?php echo $key; ?>">Job Name</label></th>
                                    <td><input type="text" id="job_title_<?php echo $key; ?>" name="job_title[]" class="regular-text" value="<?php echo esc_attr($job['job_title']); ?>" required></td>
                                </tr>
                                <!-- Job Bereich -->
                                <tr>
                                    <th><label for="job_bereich_<?php echo $key + 1; ?>">Job Bereich</label></th>
                                    <td>
                                        <ul id="job_bereich_list_<?php echo $key + 1; ?>">
                                            <?php foreach ($job['job_bereich'] as $bereichKey => $bereich) : ?>
                                                <li>
                                                    <input type="text" name="job_bereich[<?php echo $key; ?>][]" class="regular-text" value="<?php echo esc_attr($bereich); ?>" required>
                                                    <?php if ($bereichKey > 0) : ?>
                                                        <button class="delete_bereich_button" data-job="<?php echo $key; ?>">X</button>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <button type="button" class="add_job_bereich_button" data-job="<?php echo $key; ?>">Weiteren Bereich hinzufügen</button>
                                    </td>
                                </tr>
                                <!-- Job Bild -->
                                <tr>
                                    <th><label for="job_image_<?php echo $key; ?>">Job Bild</label></th>
                                    <td>
                                        <input type="hidden" id="job_image_<?php echo $key; ?>" name="job_image[]" class="job_image_url" value="<?php echo esc_url($job['job_image']); ?>">
                                        <button type="button" class="upload_image_button" data-target="#job_image_<?php echo $key; ?>">Bild auswählen</button>
                                        <div class="krp-image-preview-container">
                                            <?php if (!empty($job['job_image'])): ?>
                                                <img src="<?php echo esc_url($job['job_image']); ?>" alt="Bildvorschau">
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Firmen Infos -->
                                <tr>
                                    <th><label for="job_company_info_<?php echo $key; ?>">Firmen Infos</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="job_company_info_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'job_company_info_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'job_company_info_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'job_company_info_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('job_company_info_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'job_company_info_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('job_company_info_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'job_company_info_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'job_company_info_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'job_company_info_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'job_company_info_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'job_company_info_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'job_company_info_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="job_company_info_<?php echo $key; ?>" name="job_company_info[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_company_info']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Standort -->
                                <tr>
                                    <th><label for="job_standort_<?php echo $key; ?>">Standort</label></th>
                                    <td>
                                        <select id="job_standort_<?php echo $key; ?>" name="job_standort[]" class="regular-text">
                                            <option>Bitte auswählen</option>
                                            <?php foreach ($krp_company_standorte as $standort) : ?>
                                                <option value="<?php echo esc_attr($standort); ?>" <?php selected($job['job_standort'], $standort); ?>><?php echo esc_html($standort); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <!-- Job Tätigkeiten -->
                                <tr>
                                    <th><label for="job_tasks_<?php echo $key; ?>">Job Tätigkeiten</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="job_tasks_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'job_tasks_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'job_tasks_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'job_tasks_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('job_tasks_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'job_tasks_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('job_tasks_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'job_tasks_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'job_tasks_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'job_tasks_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'job_tasks_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'job_tasks_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'job_tasks_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="job_tasks_<?php echo $key; ?>" name="job_tasks[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_tasks']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Job Bewerbung -->
                                <tr>
                                    <th><label for="job_application_<?php echo $key; ?>">Job Bewerbung</label></th>
                                    <td>
                                        <div class="krp-text-editor">
                                            <div class="toolbar" data-editor-id="job_application_<?php echo $key; ?>">
                                                <button type="button" onclick="toggleTag('b', 'job_application_<?php echo $key; ?>')">Bold</button>
                                                <button type="button" onclick="toggleTag('i', 'job_application_<?php echo $key; ?>')">Italic</button>
                                                <button type="button" onclick="toggleTag('u', 'job_application_<?php echo $key; ?>')">Underline</button>
                                                <button type="button" onclick="insertLineBreak('job_application_<?php echo $key; ?>')">Zeilenumbruch</button>
                                                <button type="button" onclick="toggleTag('ol', 'job_application_<?php echo $key; ?>')">List</button>
                                                <button type="button" onclick="insertLink('job_application_<?php echo $key; ?>')">Insert Link</button>
                                                <button type="button" onclick="toggleTag('h1', 'job_application_<?php echo $key; ?>')">H1</button>
                                                <button type="button" onclick="toggleTag('h2', 'job_application_<?php echo $key; ?>')">H2</button>
                                                <button type="button" onclick="toggleTag('h3', 'job_application_<?php echo $key; ?>')">H3</button>
                                                <button type="button" onclick="toggleTag('h4', 'job_application_<?php echo $key; ?>')">H4</button>
                                                <button type="button" onclick="toggleTag('h5', 'job_application_<?php echo $key; ?>')">H5</button>
                                                <button type="button" onclick="toggleTag('h6', 'job_application_<?php echo $key; ?>')">H6</button>
                                            </div>
                                            <div class="editor-container">
                                                <textarea id="job_application_<?php echo $key; ?>" name="job_application[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_application']); ?></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Job Bewerbung PDF -->
                                <tr>
                                    <th><label for="job_application_pdf_<?php echo $key; ?>">Job Bewerbung PDF</label></th>
                                    <td>
                                        <input type="file" id="job_application_pdf_<?php echo $key; ?>" name="job_application_pdf[]" class="regular-text">
                                        <?php if (!empty($job['job_application_pdf'])): ?>
                                            <a href="<?php echo esc_url($job['job_application_pdf']); ?>" target="_blank">PDF ansehen</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- Kontakt Auswahl-->
                                <tr>
                                    <th><label for="job_select_contact_job_details_<?php echo $key; ?>">Kontakt Auswahl für Job</label></th>
                                    <td>
                                        <select class="contact-select" id="job_select_contact_job_details_<?php echo $key; ?>" name="selected_contact_job_details_name[]">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                                            $saved_contacts = get_option('krp_saved_contacts', array());
                                            foreach ($saved_contacts as $contact) {
                                                $contact_name_job_details = esc_html($contact['contact_name']);
                                                echo '<option value="' . esc_attr($contact_name_job_details) . '"' . selected($job['selected_contact_job_details_name'], $contact_name_job_details, false) . '>' . esc_html($contact_name_job_details) . '</option>';
                                            }
                                            ?>
                                        </select>

                                        <!-- Andere Felder werden basierend auf der Auswahl aktualisiert -->
                                        <select class="contact-select" id="job_select_contact_job_details_abteilung_<?php echo $key; ?>" name="selected_contact_job_details_abteilung[]">
                                            <option value="" disabled selected>Abteilung auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_tel_<?php echo $key; ?>" name="selected_contact_job_details_tel[]">
                                            <option value="" disabled selected>Telefon auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_email_<?php echo $key; ?>" name="selected_contact_job_details_email[]">
                                            <option value="" disabled selected>Email auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_info_<?php echo $key; ?>" name="selected_contact_job_details_info[]">
                                            <option value="" disabled selected>Info auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_image_url_<?php echo $key; ?>" name="selected_contact_job_details_image_url[]">
                                            <option value="" disabled selected>Bild-URL auswählen</option>
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

                // Job hinzufügen
                $('#add_job_button').click(function() {
                    const jobIndex = $('#jobs_container .job_entry').length;
                    const jobHtml = `
                <div class="job_entry">
                    <div class="job_title" data-job="${jobIndex}">
                        <div class="toggle_arrow"></div>
                        <h3>#${jobIndex + 1} - Neuer Job</h3>
                        <button class="delete_job_button" data-job="${jobIndex}">Löschen</button>
                    </div>
                    <div class="job_details" id="job_details_${jobIndex}">
                        <table class="form-table">
                            <!-- Job Name -->
                            <tr>
                                <th><label for="job_title_${jobIndex}">Job Name</label></th>
                                <td><input type="text" id="job_title_${jobIndex}" name="job_title[]" class="regular-text" required></td>
                            </tr>
                            <!-- Job Bereich -->
                            <tr>
                                <th><label for="job_bereich_${jobIndex + 1}">Job Bereich</label></th>
                                <td>
                                    <ul id="job_bereich_list_${jobIndex + 1}">
                                        <li>
                                            <input type="text" name="job_bereich[${jobIndex}][]" class="regular-text" required>
                                            <button class="delete_bereich_button" data-job="${jobIndex}">X</button>
                                        </li>
                                    </ul>
                                    <button type="button" class="add_job_bereich_button" data-job="${jobIndex}">Weiteren Bereich hinzufügen</button>
                                </td>
                            </tr>
                            <!-- Job Bild -->
                            <tr>
                                <th><label for="job_image_${jobIndex}">Job Bild</label></th>
                                <td>
                                    <input type="hidden" id="job_image_${jobIndex}" name="job_image[]" class="job_image_url">
                                    <button type="button" class="upload_image_button" data-target="#job_image_${jobIndex}">Bild auswählen</button>
                                    <div class="krp-image-preview-container"></div>
                                </td>
                            </tr>
                            <!-- Firmen Infos -->
                            <tr>
                                <th><label for="job_company_info_${jobIndex}">Firmen Infos</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="job_company_info_${jobIndex}">
                                            <button type="button" onclick="toggleTag('b', 'job_company_info_${jobIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'job_company_info_${jobIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'job_company_info_${jobIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('job_company_info_${jobIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'job_company_info_${jobIndex}')">List</button>
                                            <button type="button" onclick="insertLink('job_company_info_${jobIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'job_company_info_${jobIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'job_company_info_${jobIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'job_company_info_${jobIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'job_company_info_${jobIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'job_company_info_${jobIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'job_company_info_${jobIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="job_company_info_${jobIndex}" name="job_company_info[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_company_info']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Standort -->
                            <tr>
                                <th><label for="job_standort_${jobIndex}">Standort</label></th>
                                <td>
                                    <select id="job_standort_${jobIndex}" name="job_standort[]" class="regular-text">
                                        <option>Bitte auswählen</option>
                                        <?php foreach ($krp_company_standorte as $standort) : ?>
                                            <option value="<?php echo esc_attr($standort); ?>"><?php echo esc_html($standort); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <!-- Job Tätigkeiten -->
                            <tr>
                                <th><label for="job_tasks_${jobIndex}">Job Tätigkeiten</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="job_tasks_${jobIndex}">
                                            <button type="button" onclick="toggleTag('b', 'job_tasks_${jobIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'job_tasks_${jobIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'job_tasks_${jobIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('job_tasks_${jobIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'job_tasks_${jobIndex}')">List</button>
                                            <button type="button" onclick="insertLink('job_tasks_${jobIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'job_tasks_${jobIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'job_tasks_${jobIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'job_tasks_${jobIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'job_tasks_${jobIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'job_tasks_${jobIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'job_tasks_${jobIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="job_tasks_${jobIndex}" name="job_tasks[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_tasks']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Job Bewerbung -->
                            <tr>
                                <th><label for="job_application_${jobIndex}">Job Bewerbung</label></th>
                                <td>
                                    <div class="krp-text-editor">
                                        <div class="toolbar" data-editor-id="job_application_${jobIndex}">
                                            <button type="button" onclick="toggleTag('b', 'job_application_${jobIndex}')">Bold</button>
                                            <button type="button" onclick="toggleTag('i', 'job_application_${jobIndex}')">Italic</button>
                                            <button type="button" onclick="toggleTag('u', 'job_application_${jobIndex}')">Underline</button>
                                            <button type="button" onclick="insertLineBreak('job_application_${jobIndex}')">Zeilenumbruch</button>
                                            <button type="button" onclick="toggleTag('ol', 'job_application_${jobIndex}')">List</button>
                                            <button type="button" onclick="insertLink('job_application_${jobIndex}')">Insert Link</button>
                                            <button type="button" onclick="toggleTag('h1', 'job_application_${jobIndex}')">H1</button>
                                            <button type="button" onclick="toggleTag('h2', 'job_application_${jobIndex}')">H2</button>
                                            <button type="button" onclick="toggleTag('h3', 'job_application_${jobIndex}')">H3</button>
                                            <button type="button" onclick="toggleTag('h4', 'job_application_${jobIndex}')">H4</button>
                                            <button type="button" onclick="toggleTag('h5', 'job_application_${jobIndex}')">H5</button>
                                            <button type="button" onclick="toggleTag('h6', 'job_application_${jobIndex}')">H6</button>
                                        </div>
                                        <div class="editor-container">
                                            <textarea id="job_application_${jobIndex}" name="job_application[]" style="width: 800px; height: 250px;"><?php echo esc_textarea($job['job_application']); ?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Job Bewerbung PDF -->
                            <tr>
                                <th><label for="job_application_pdf_${jobIndex}">Job Bewerbung PDF</label></th>
                                <td>
                                    <input type="file" id="job_application_pdf_${jobIndex}" name="job_application_pdf[]" class="regular-text">
                                </td>
                            </tr>
                                                          <!-- Kontakt Auswahl-->
                                <tr>
                                    <th><label for="job_select_contact_job_details_${jobIndex}">Kontakt Auswahl für Job</label></th>
                                    <td>
                                        <select class="contact-select" id="job_select_contact_job_details_${jobIndex}" name="selected_contact_job_details_name[]">
                                            <option value="" disabled selected>Kontakt auswählen</option>
                                            <?php
                    $saved_contacts = get_option('krp_saved_contacts', array());
                    foreach ($saved_contacts as $contact) {
                        $contact_name_job_details = esc_html($contact['contact_name']);
                        echo '<option value="' . esc_attr($contact_name_job_details) . '"' . selected($job['selected_contact_job_details_name'], $contact_name_job_details, false) . '>' . esc_html($contact_name_job_details) . '</option>';
                    }
                    ?>
                                        </select>

                                        <!-- Andere Felder werden basierend auf der Auswahl aktualisiert -->
                                        <select class="contact-select" id="job_select_contact_job_details_abteilung_${jobIndex}" name="selected_contact_job_details_abteilung[]">
                                            <option value="" disabled selected>Abteilung auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_tel_${jobIndex}" name="selected_contact_job_details_tel[]">
                                            <option value="" disabled selected>Telefon auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_email_${jobIndex}" name="selected_contact_job_details_email[]">
                                            <option value="" disabled selected>Email auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_info_${jobIndex}" name="selected_contact_job_details_info[]">
                                            <option value="" disabled selected>Info auswählen</option>
                                        </select>
                                        <select class="contact-select" id="job_select_contact_job_details_image_url_${jobIndex}" name="selected_contact_job_details_image_url[]">
                                            <option value="" disabled selected>Bild-URL auswählen</option>
                                        </select>
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>`;
                    $('#jobs_container').append(jobHtml);
                });

                // Job löschen
                $(document).on('click', '.delete_job_button', function() {
                    const jobIndex = $(this).data('job');
                    $(this).closest('.job_entry').remove();
                });

                // Job Bereich hinzufügen
                $(document).on('click', '.add_job_bereich_button', function() {
                    const jobIndex = $(this).data('job');
                    const BereichHtml = `
                <li>
                    <input type="text" name="job_bereich[${jobIndex}][]" class="regular-text" required>
                    <button class="delete_bereich_button" data-job="${jobIndex}">X</button>
                </li>`;
                    $(`#job_bereich_list_${jobIndex + 1}`).append(BereichHtml);
                });

                // Job Bereich löschen
                $(document).on('click', '.delete_bereich_button', function() {
                    $(this).closest('li').remove();
                });

                // Job Titel aufklappen/zu klappen
                $(document).on('click', '.job_title', function() {
                    const jobIndex = $(this).data('job');
                    $(`#job_details_${jobIndex}`).toggleClass('open');
                    $(this).find('.toggle_arrow').toggleClass('open');
                });
            });
        })(jQuery);
    </script>
    <script type="text/javascript">
        // Speichere die Kontaktinformationen in einem JavaScript-Objekt
        var contacts = <?php echo json_encode($saved_contacts); ?>;

        // Event Listener für die Änderung des Kontakt-Selects
        document.querySelectorAll(".contact-select[name='selected_contact_job_details_name[]']").forEach(function(selectElement) {
            selectElement.addEventListener("change", function() {
                var selectedContactName = this.value;
                var key = this.id.split('_').pop(); // Extrahiere den Schlüssel aus der ID

                // Finde den Index des ausgewählten Kontakts
                var contactIndex = contacts.findIndex(contact => contact.contact_name === selectedContactName);

                if (contactIndex !== -1) {
                    // Update die anderen Select-Felder basierend auf dem ausgewählten Kontakt
                    updateContactDetails(contactIndex, key);
                }
            });
        });

        function updateContactDetails(contactIndex, key) {
            var contact = contacts[contactIndex];

            // Erstelle die Optionen für jedes Select-Feld und setze die Werte
            updateSelectField("job_select_contact_job_details_abteilung_" + key, contact.contact_abteilung);
            updateSelectField("job_select_contact_job_details_tel_" + key, [contact.contact_tel]);
            updateSelectField("job_select_contact_job_details_email_" + key, [contact.contact_email]);
            updateSelectField("job_select_contact_job_details_info_" + key, [contact.contact_info]);
            updateSelectField("job_select_contact_job_details_image_url_" + key, [contact.contact_image_url]);
        }

        function updateSelectField(selectId, values) {
            var selectField = document.getElementById(selectId);
            selectField.innerHTML = ''; // Entferne vorhandene Optionen

            values.forEach(function(value) {
                var option = document.createElement('option');
                option.value = value;
                option.text = value;
                selectField.appendChild(option);
            });

            selectField.style.display = 'block'; // Zeige das Select-Feld an
        }
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

// Funktion zum Speichern der Jobs
function krp_save_jobs() {
    if (isset($_POST['action']) && $_POST['action'] === 'save_krp_jobs') {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'krp_save_jobs_nonce')) {
            wp_die('Ungültige Anfrage.');
        }

        // Felder sanitieren
        $job_titles = isset($_POST['job_title']) ? array_map('sanitize_text_field', $_POST['job_title']) : array();
        $job_bereiche = isset($_POST['job_bereich']) ? array_map(function($bereichs) {
            return array_map('sanitize_text_field', $bereichs);
        }, $_POST['job_bereich']) : array();
        $job_standorte = isset($_POST['job_standort']) ? array_map('sanitize_text_field', $_POST['job_standort']) : array();

        $selected_contacts_job_details_name = isset($_POST['selected_contact_job_details_name']) ? array_map('sanitize_text_field', $_POST['selected_contact_job_details_name']) : array();
        $selected_contacts_job_details_abteilung = isset($_POST['selected_contact_job_details_abteilung']) ? array_map('sanitize_text_field', $_POST['selected_contact_job_details_abteilung']) : array();
        $selected_contacts_job_details_tel = isset($_POST['selected_contact_job_details_tel']) ? array_map('sanitize_text_field', $_POST['selected_contact_job_details_tel']) : array();
        $selected_contacts_job_details_email = isset($_POST['selected_contact_job_details_email']) ? array_map('sanitize_text_field', $_POST['selected_contact_job_details_email']) : array();
        $selected_contacts_job_details_info = isset($_POST['selected_contact_job_details_info']) ? array_map('sanitize_text_field', $_POST['selected_contact_job_details_info']) : array();
        $selected_contacts_job_details_image_url = isset($_POST['selected_contact_job_details_image_url']) ? array_map('esc_url_raw', $_POST['selected_contact_job_details_image_url']) : array();

        // Felder mit wp_kses_post verarbeiten (für die Validierung auf HTML)
        $job_company_infos = isset($_POST['job_company_info']) ? array_map('wp_kses_post', $_POST['job_company_info']) : array();
        $job_tasks = isset($_POST['job_tasks']) ? array_map('wp_kses_post', $_POST['job_tasks']) : array();
        $job_applications = isset($_POST['job_application']) ? array_map('wp_kses_post', $_POST['job_application']) : array();

        // PDF-Dateien verarbeiten
        $job_application_pdfs = isset($_FILES['job_application_pdf']) ? $_FILES['job_application_pdf'] : array();
        $job_images = isset($_POST['job_image']) ? array_map('esc_url_raw', $_POST['job_image']) : array();

        $jobs = array();
        foreach ($job_titles as $key => $title) {
            $pdf_url = '';

            if (isset($job_application_pdfs['name'][$key]) && !empty($job_application_pdfs['name'][$key])) {
                // Stelle sicher, dass die Datei-Array-Struktur korrekt verarbeitet wird
                $file = array(
                    'name' => $job_application_pdfs['name'][$key],
                    'type' => $job_application_pdfs['type'][$key],
                    'tmp_name' => $job_application_pdfs['tmp_name'][$key],
                    'error' => $job_application_pdfs['error'][$key],
                    'size' => $job_application_pdfs['size'][$key],
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

            // Job-Daten sammeln
            $jobs[] = array(
                'job_title' => $title,
                'job_bereich' => isset($job_bereiche[$key]) ? $job_bereiche[$key] : array(),
                'job_company_info' => isset($job_company_infos[$key]) ? $job_company_infos[$key] : '',
                'job_standort' => isset($job_standorte[$key]) ? $job_standorte[$key] : '',
                'job_tasks' => isset($job_tasks[$key]) ? $job_tasks[$key] : '',
                'job_application' => isset($job_applications[$key]) ? $job_applications[$key] : '',
                'job_application_pdf' => $pdf_url,
                'job_image' => isset($job_images[$key]) ? $job_images[$key] : '', // Bild-URL hinzufügen
                'selected_contact_job_details_name' => isset($selected_contacts_job_details_name[$key]) ? $selected_contacts_job_details_name[$key] : '',
                'selected_contact_job_details_abteilung' => isset($selected_contacts_job_details_abteilung[$key]) ? $selected_contacts_job_details_abteilung[$key] : '',
                'selected_contact_job_details_tel' => isset($selected_contacts_job_details_tel[$key]) ? $selected_contacts_job_details_tel[$key] : '',
                'selected_contact_job_details_email' => isset($selected_contacts_job_details_email[$key]) ? $selected_contacts_job_details_email[$key] : '',
                'selected_contact_job_details_info' => isset($selected_contacts_job_details_info[$key]) ? $selected_contacts_job_details_info[$key] : '',
                'selected_contact_job_details_image_url' => isset($selected_contacts_job_details_image_url[$key]) ? $selected_contacts_job_details_image_url[$key] : '',
            );
        }

        // Jobs in der Datenbank speichern
        update_option('krp_saved_jobs', $jobs);

        // Zurück zur vorherigen Seite mit Erfolgsmeldung
        wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
        exit;
    }
}

add_action('admin_post_save_krp_jobs', 'krp_save_jobs');
