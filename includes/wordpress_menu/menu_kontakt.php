<?php
/*
 * Excluded file that handles the kontakt tab in the WordPress krp admin menu
 */

function krp_kontakt_section_callback() {
    ?>
        <p>Kontakt-Einstellungen für das Plugin. Hier kannst du allgemeine Kontakt Informationen Angeben. Unten kannst du über den Button Kontakt Personen erstellen. Diese können dann bei den contacts und Ausbildungsstellen zugeordnet werden.</p>
    <?php
}

function krp_kontakt_allgemein_section_callback() {
    ?>
        <h3>Allgemeine Kontakt Informationen</h3>
        <p>Hier kannst du Informationen hinzufügen, die auf der Kontakt Seite angezeigt werden.</p>
    <?php
}

function krp_kontakt_allgemein_tel_field_callback() {
    $krp_kontakt_tel = get_option('krp_kontakt_allgemein_tel_field');
    ?>
        <input type="text" id="krp_kontakt_allgemein_tel" name="krp_kontakt_allgemein_tel_field" style="width: 450px" value="<?php echo esc_attr($krp_kontakt_tel); ?>" />
        <script>
            const telefonnummerInput = document.getElementById('krp_kontakt_allgemein_tel');

            telefonnummerInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+\-()\s]/g, '');
            });
        </script>
    <?php
}

function krp_kontakt_allgemein_address_field_callback() {
    // Werte aus der Datenbank abrufen
    $krp_kontakt_street = get_option('krp_kontakt_allgemein_street_field');
    $krp_kontakt_number = get_option('krp_kontakt_allgemein_number_field');
    $krp_kontakt_zip = get_option('krp_kontakt_allgemein_zip_field');
    $krp_kontakt_city = get_option('krp_kontakt_allgemein_city_field');
    $krp_kontakt_additional = get_option('krp_kontakt_allgemein_additional_field');

    ?>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <input type="text" name="krp_kontakt_allgemein_street_field" placeholder="Straße" style="width: 200px" value="<?php echo esc_attr($krp_kontakt_street); ?>" />
        <input type="text" name="krp_kontakt_allgemein_number_field" placeholder="Hausnummer" style="width: 100px" value="<?php echo esc_attr($krp_kontakt_number); ?>" />
        <input type="text" name="krp_kontakt_allgemein_zip_field" placeholder="Postleitzahl" style="width: 100px" value="<?php echo esc_attr($krp_kontakt_zip); ?>" />
        <input type="text" name="krp_kontakt_allgemein_city_field" placeholder="Ort" style="width: 150px" value="<?php echo esc_attr($krp_kontakt_city); ?>" />
        <input type="text" name="krp_kontakt_allgemein_additional_field" placeholder="Zusatz" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_additional); ?>" />
    </div>
    <?php
}

function krp_kontakt_allgemein_email_field_callback() {
    $krp_kontakt_email = get_option('krp_kontakt_allgemein_email_field');
    ?>
        <input type="email" name="krp_kontakt_allgemein_email_field" style="width: 450px" value="<?php echo esc_attr($krp_kontakt_email); ?>" placeholder="abc@xyz.com"/>
    <?php
}

function krp_kontakt_allgemein_opening_hours_field_callback() {
    $krp_kontakt_oh_monday = get_option('krp_kontakt_allgemein_oh_monday');
    $krp_kontakt_oh_dienstag = get_option('krp_kontakt_allgemein_oh_dienstag');
    $krp_kontakt_oh_mittwoch = get_option('krp_kontakt_allgemein_oh_mittwoch');
    $krp_kontakt_oh_donnerstag = get_option('krp_kontakt_allgemein_oh_donnerstag');
    $krp_kontakt_oh_freitag = get_option('krp_kontakt_allgemein_oh_freitag');
    $krp_kontakt_oh_samstag = get_option('krp_kontakt_allgemein_oh_samstag');
    $krp_kontakt_oh_sonntag = get_option('krp_kontakt_allgemein_oh_sonntag');
    $krp_kontakt_oh_display = get_option('krp_kontakt_allgemein_oh_display', 'ja');

    ?>
        <style>
            .krp_kontakt_allgemein_form label {
                display: inline-block;
                width: 100px;
            }
        </style>
        <div class="krp_kontakt_allgemein_form">
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Montag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_monday" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_monday); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_dienstag">Dienstag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_dienstag" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_dienstag); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Mittwoch:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_mittwoch" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_mittwoch); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Donnerstag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_donnerstag" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_donnerstag); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Freitag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_freitag" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_freitag); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Samstag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_samstag" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_samstag); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div>
                <label for="krp_kontakt_allgemein_oh_monday">Sonntag:</label>
                <input type="text" name="krp_kontakt_allgemein_oh_sonntag" style="width: 250px" value="<?php echo esc_attr($krp_kontakt_oh_sonntag); ?>" placeholder="09:00 - 18:00 / Geschlossen"/>
            </div>
            <div style="margin-top: 20px;">
                <label>Öffnungszeiten anzeigen:</label>
                <input type="radio" name="krp_kontakt_allgemein_oh_display" value="block" <?php checked($krp_kontakt_oh_display, 'block'); ?> /> Ja
                <input type="radio" name="krp_kontakt_allgemein_oh_display" value="none" <?php checked($krp_kontakt_oh_display, 'none'); ?> /> Nein
            </div>
        </div>
    <script>
        document.querySelectorAll('.krp_kontakt_allgemein_form input[type="text"]').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^0-9gG]/g, ''); // Nur Zahlen und "g" oder "G" erlauben

                if (value.toLowerCase().startsWith("g")) {
                    value = "Geschlossen";
                } else {
                    let formattedValue = "";

                    // Format für die erste Zeit (hh:mm)
                    if (value.length >= 2) {
                        formattedValue += value.slice(0, 2) + ':';
                        if (value.length >= 4) {
                            formattedValue += value.slice(2, 4);
                        } else {
                            formattedValue += value.slice(2);
                        }
                    } else {
                        formattedValue += value;
                    }

                    // Leerzeichen-Dash-Leerzeichen und Format für die zweite Zeit (hh:mm)
                    if (value.length > 4) {
                        formattedValue += ' - ';
                        if (value.length >= 6) {
                            formattedValue += value.slice(4, 6) + ':';
                            if (value.length >= 8) {
                                formattedValue += value.slice(6, 8);
                            } else {
                                formattedValue += value.slice(6);
                            }
                        } else {
                            formattedValue += value.slice(4);
                        }
                    }

                    value = formattedValue;
                }

                // Maximal erlaubte Länge des Inputs
                if (value !== "Geschlossen" && value.length > 14) {
                    value = value.slice(0, 14);
                }

                e.target.value = value;
            });
        });
    </script>
    <?php
}

function krp_kontakt_allgemein_fax_field_callback() {
    $krp_kontakt_fax = get_option('krp_kontakt_allgemein_fax_field');
    ?>
        <input type="text" id="krp_kontakt_allgemein_fax" name="krp_kontakt_allgemein_fax_field" style="width: 450px" value="<?php echo esc_attr($krp_kontakt_fax); ?>" />
        <script>
            const faxnummerInput = document.getElementById('krp_kontakt_allgemein_fax');

            faxnummerInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^\d+\-()\s]/g, '');
            });
        </script>
    <?php
}

function krp_kontakt_allgemein_company_standorte_field_callback() {
    $krp_company_standorte = get_option('krp_kontakt_allgemein_company_standorte_field', array());

    // Die Standorte als JSON für JavaScript bereitstellen
    $standorte_json = json_encode($krp_company_standorte);
    ?>

    <div id="standorte-container">
        <?php if (!empty($krp_company_standorte)): ?>
            <?php foreach ($krp_company_standorte as $index => $standort): ?>
                <div class="standort-field">
                    <input type="text" name="krp_company_standorte[]" value="<?php echo esc_attr($standort); ?>" />
                    <button type="button" class="remove-standort">X</button>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <button type="button" id="add-standort">Standort hinzufügen</button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var standorteContainer = document.getElementById('standorte-container');
            var addStandortButton = document.getElementById('add-standort');

            addStandortButton.addEventListener('click', function() {
                var newStandortDiv = document.createElement('div');
                newStandortDiv.classList.add('standort-field');
                newStandortDiv.innerHTML = '<input type="text" name="krp_company_standorte[]" value="" /> <button type="button" class="remove-standort">X</button>';
                standorteContainer.appendChild(newStandortDiv);
            });

            standorteContainer.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-standort')) {
                    event.target.parentNode.remove();
                }
            });
        });
    </script>

    <style>
        .standort-field {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            width: 485px;
        }
        .standort-field input {
            margin-right: 10px;
            flex: 1;
        }
    </style>

    <?php
}

function krp_kontakt_select_section_callback() {
    // Abrufen der gespeicherten Kontakte
    $contacts = get_option('krp_saved_contacts', array());
    $selected_contact_contact_tab = get_option('krp_selected_contact_contact_tab', '');

    ?>
    <input type="hidden" name="action" value="save_krp_selected_contact_contact_tab">
    <select name="selected_contact_contact_tab" id="selected_contact_contact_tab" style="width: 450px;">
        <option value="" disabled selected>Bitte auswählen</option>
        <?php foreach ($contacts as $contact): ?>
            <option value="<?php echo esc_attr($contact['contact_name']); ?>" <?php selected($selected_contact_contact_tab, $contact['contact_name']); ?>>
                <?php echo esc_html($contact['contact_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}

function krp_kontakt_create_section_callback() {
    $contacts = get_option('krp_saved_contacts', array());
    ?>

    <style>
        .contact_entry { margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; position: relative; }
        .contact_title { cursor: pointer; display: flex; align-items: center; margin-bottom: 10px; }
        .contact_title h3 { margin: 0; flex-grow: 1; }
        .toggle_arrow { width: 0; height: 0; border-left: 6px solid transparent; border-right: 6px solid transparent; border-top: 6px solid #333; margin-right: 10px; transition: transform 0.3s ease; }
        .toggle_arrow.open { transform: rotate(180deg); }
        .contact_details { display: none; margin-top: 10px; }
        .contact_details.open { display: block; }
        .delete_contact_button { cursor: pointer; margin-left: 5px; }
        .contact_buttons { margin-bottom: 20px; }
        .krp-image-preview-container { margin-top: 20px; }
        .krp-image-preview-container img { max-width: 100%; height: auto; max-height: 200px; margin-top: 10px; }
    </style>

    <div class="wrap">
        <h3>Übersicht Kontakt Personen</h3>
        <p>Über die jeweiligen Buttons kannst du neue Kontakt Personen erstellen und speichern. Diese kannst du dann den einzelnen Jobs und Ausbildungsstellen zuweisen.</p>

        <form id="krp_contacts_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_krp_contacts">
            <?php wp_nonce_field('krp_save_contacts_nonce'); ?>

            <div class="contact_buttons">
                <button type="button" id="add_contact_button">Kontakt erstellen</button>
                <button type="submit" name="submit">Kontakte speichern</button>
            </div>

            <div id="contacts_container">
                <?php foreach ($contacts as $key => $contact) : ?>
                    <div class="contact_entry">
                        <div class="contact_title" data-contact="<?php echo $key; ?>">
                            <div class="toggle_arrow"></div>
                            <h3>#<?php echo $key + 1; ?> - <?php echo esc_html($contact['contact_name']); ?></h3>
                            <button class="delete_contact_button" data-contact="<?php echo $key; ?>">Löschen</button>
                        </div>
                        <div class="contact_details" id="contact_details_<?php echo $key; ?>">
                            <table class="form-table">
                                <tr>
                                    <th><label for="contact_name_<?php echo $key; ?>">Kontakt Name</label></th>
                                    <td><input type="text" id="contact_name_<?php echo $key; ?>" name="contact_name[]" class="regular-text" value="<?php echo esc_attr($contact['contact_name']); ?>" required></td>
                                </tr>
                                <tr>
                                    <th><label for="contact_tel_<?php echo $key; ?>">Telefon</label></th>
                                    <td><input type="text" id="contact_tel_<?php echo $key; ?>" name="contact_tel[]" class="regular-text" value="<?php echo esc_attr($contact['contact_tel']); ?>" required></td>
                                </tr>
                                <tr>
                                    <th><label for="contact_email_<?php echo $key; ?>">Email</label></th>
                                    <td><input type="email" id="contact_email_<?php echo $key; ?>" name="contact_email[]" class="regular-text" value="<?php echo esc_attr($contact['contact_email']); ?>" required placeholder="abc@xyz.com"></td>
                                </tr>
                                <tr>
                                    <th><label for="contact_abteilung_<?php echo $key; ?>">Abteilung</label></th>
                                    <td>
                                        <ul id="contact_abteilung_list_<?php echo $key; ?>">
                                            <?php if (isset($contact['contact_abteilung']) && is_array($contact['contact_abteilung'])): ?>
                                                <?php foreach ($contact['contact_abteilung'] as $bereichKey => $bereich) : ?>
                                                    <li>
                                                        <input type="text" name="contact_abteilung[<?php echo $key; ?>][]" class="regular-text" value="<?php echo esc_attr($bereich); ?>" required>
                                                        <button class="delete_bereich_button" data-contact="<?php echo $key; ?>">X</button>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                        <button type="button" class="add_contact_abteilung_button" data-contact="<?php echo $key; ?>">Weitere Abteilung hinzufügen</button>
                                    </td>
                                </tr>
                                <tr>
                                    <th><label for="contact_info_<?php echo $key; ?>">Kontakt Informationen</label></th>
                                    <td><textarea id="contact_info_<?php echo $key; ?>" name="contact_info[]" class="regular-text"><?php echo isset($contact['contact_info']) ? esc_html($contact['contact_info']) : ''; ?></textarea></td>
                                </tr>
                                <tr>
                                    <th><label for="contact_image_<?php echo $key; ?>">Kontakt Bild</label></th>
                                    <td>
                                        <div id="krp-hero-upload-buttons">
                                            <button type="button" id="krp-media-select_<?php echo $key; ?>" class="krp-media-select-button" data-contact="<?php echo $key; ?>">Bild auswählen</button>
                                        </div>
                                        <div id="krp-image-preview_<?php echo $key; ?>" class="krp-image-preview-container">
                                            <img src="<?php echo !empty($contact['contact_image_url']) ? esc_url($contact['contact_image_url']) : esc_url(get_template_directory_uri() . '/assets/img/Platzhalterbild.jpg'); ?>" alt="Bildvorschau">
                                        </div>
                                        <input type="hidden" id="contact_image_url_<?php echo $key; ?>" name="contact_image_url[]" value="<?php echo esc_attr($contact['contact_image_url']); ?>">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var form = document.getElementById('krp_contacts_form');
                var contactsContainer = document.getElementById('contacts_container');
                var addContactButton = document.getElementById('add_contact_button');
                var contactCount = <?php echo count($contacts); ?>;

                addContactButton.addEventListener('click', function() {
                    contactCount++;

                    var contactHtml =
                        <div class="contact_entry">
                            <div class="contact_title" data-contact="${contactCount}">
                                <div class="toggle_arrow"></div>
                                <h3>#${contactCount} - Neuer Kontakt</h3>
                                <button class="delete_contact_button" data-contact="${contactCount}">Löschen</button>
                            </div>
                            <div class="contact_details" id="contact_details_${contactCount}">
                                <table class="form-table">
                                    <tr>
                                        <th><label for="contact_name_${contactCount}">Kontakt Name</label></th>
                                        <td><input type="text" id="contact_name_${contactCount}" name="contact_name[]" class="regular-text" required></td>
                                    </tr>
                                    <tr>
                                        <th><label for="contact_tel_${contactCount}">Telefon</label></th>
                                        <td><input type="text" id="contact_tel_${contactCount}" name="contact_tel[]" class="regular-text" required></td>
                                    </tr>
                                    <tr>
                                        <th><label for="contact_email_${contactCount}">Email</label></th>
                                        <td><input type="email" id="contact_email_${contactCount}" name="contact_email[]" class="regular-text" required placeholder="abc@xyz.com"></td>
                                    </tr>
                                    <tr>
                                        <th><label for="contact_abteilung_${contactCount}">Abteilung</label></th>
                                        <td>
                                            <ul id="contact_abteilung_list_${contactCount}">
                                                <li>
                                                    <input type="text" name="contact_abteilung[${contactCount}][]" class="regular-text" required>
                                                        <button class="delete_bereich_button remove-standort" data-contact="${contactCount}">X</button>
                                                </li>
                                            </ul>
                                            <button type="button" class="add_contact_abteilung_button" data-contact="${contactCount}">Weitere Abteilung hinzufügen</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><label for="contact_info_${contactCount}">Kontakt Informationen</label></th>
                                        <td><textarea id="contact_info_${contactCount}" name="contact_info[]" class="regular-text"></textarea></td>
                                    </tr>
                                    <tr>
                                        <th><label for="contact_image_${contactCount}">Kontakt Bild</label></th>
                                        <td>
                                            <div id="krp-hero-upload-buttons">
                                                <button type="button" id="krp-media-select_${contactCount}" class="krp-media-select-button" data-contact="${contactCount}">Bild auswählen</button>
                                            </div>
                                            <div id="krp-image-preview_${contactCount}" class="krp-image-preview-container"></div>
                                            <input type="hidden" id="contact_image_url_${contactCount}" name="contact_image_url[]">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                ;

                var contactDiv = document.createElement('div');
                contactDiv.innerHTML = contactHtml.trim();
                contactsContainer.appendChild(contactDiv);

                var contactNameInput = contactDiv.querySelector(#contact_name_${contactCount});
                var contactTitle = contactDiv.querySelector('h3');

                contactNameInput.addEventListener('input', function() {
                    contactTitle.textContent = #${contactCount} - ${contactNameInput.value || 'Neuer Kontakt'};
                });

                var deleteContactButton = contactDiv.querySelector('.delete_contact_button');
                deleteContactButton.addEventListener('click', function() {
                    contactDiv.remove();
                    updateContactTitles();
                });

                var contactTitleElement = contactDiv.querySelector('.contact_title');
                contactTitleElement.addEventListener('click', function() {
                    var contactDetails = document.getElementById('contact_details_' + contactTitleElement.getAttribute('data-contact'));
                    contactDetails.classList.toggle('open');
                    contactTitleElement.querySelector('.toggle_arrow').classList.toggle('open');
                });

                initMediaSelectButtons();
                });

                function updateContactTitles() {
                    var contactEntries = contactsContainer.querySelectorAll('.contact_entry');
                    contactEntries.forEach(function(contactEntry, index) {
                        var contactTitle = contactEntry.querySelector('h3');
                        var contactNameInput = contactEntry.querySelector('input[id^="contact_name_"]');
                        contactTitle.textContent = #${index + 1} - ${contactNameInput.value || 'Neuer Kontakt'};
                    });
                }

                var deleteContactButtons = contactsContainer.querySelectorAll('.delete_contact_button');
                deleteContactButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var contactEntry = button.closest('.contact_entry');
                        contactEntry.remove();
                        updateContactTitles();
                    });
                });

                var contactTitles = contactsContainer.querySelectorAll('.contact_title');
                contactTitles.forEach(function(contactTitle) {
                    contactTitle.addEventListener('click', function() {
                        var contactDetails = document.getElementById('contact_details_' + contactTitle.getAttribute('data-contact'));
                        contactDetails.classList.toggle('open');
                        contactTitle.querySelector('.toggle_arrow').classList.toggle('open');
                    });
                });

                var contactNameInputs = contactsContainer.querySelectorAll('input[id^="contact_name_"]');
                contactNameInputs.forEach(function(input) {
                    input.addEventListener('input', function() {
                        var contactTitle = input.closest('.contact_entry').querySelector('h3');
                        var contactNumber = Array.prototype.indexOf.call(contactNameInputs, input) + 1;
                        contactTitle.textContent = #${contactNumber} - ${input.value || 'Neuer Kontakt'};
                    });
                });

                var addContactAbteilungButtons = contactsContainer.querySelectorAll('.add_contact_abteilung_button');
                addContactAbteilungButtons.forEach(function(button) {
                    button.addEventListener('click', function() {
                        var jobDetails = document.getElementById('contact_details_' + button.getAttribute('data-contact'));
                        var contactAbteilungList = jobDetails.querySelector('ul');
                        var newContactAbteilungList = document.createElement('li');
                        newContactAbteilungList.innerHTML =
                            <input type="text" name="contact_abteilung[${button.getAttribute('data-contact')}][]" class="regular-text" required>
                                <button class="delete_bereich_button remove-standort" data-contact="${button.getAttribute('data-contact')}">X</button>
                                ;
                                contactAbteilungList.appendChild(newContactAbteilungList);

                                var deleteBereichButton = newContactAbteilungList.querySelector('.delete_bereich_button');
                                deleteBereichButton.addEventListener('click', function() {
                                newContactAbteilungList.remove();
                            });
                                });
                                });

                                var deleteBereichButtons = contactsContainer.querySelectorAll('.delete_bereich_button');
                                deleteBereichButtons.forEach(function(button) {
                                button.addEventListener('click', function() {
                                    var bereichEntry = button.closest('li');
                                    bereichEntry.remove();
                                });
                            });

                                function initMediaSelectButtons() {
                                var mediaSelectButtons = document.querySelectorAll('.krp-media-select-button');
                                mediaSelectButtons.forEach(function(button) {
                                button.addEventListener('click', function(event) {
                                event.preventDefault();
                                var contactIndex = button.getAttribute('data-contact');
                                var frame;

                                if (frame) {
                                frame.open();
                                return;
                            }
                                frame = wp.media({
                                title: 'Wähle oder lade ein Bild hoch',
                                button: { text: 'Bild auswählen' },
                                multiple: false
                            });

                                frame.on('select', function() {
                                var attachment = frame.state().get('selection').first().toJSON();
                                updatePreview(attachment.url, contactIndex);
                            });

                                frame.open();
                            });
                            });
                            }

                                function updatePreview(url, contactIndex) {
                                var previewContainer = document.getElementById('krp-image-preview_' + contactIndex);
                                previewContainer.innerHTML = '<img src="' + url + '" alt="Bildvorschau">';

                                var hiddenInput = document.getElementById('contact_image_url_' + contactIndex);
                                if (!hiddenInput) {
                                hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.id = 'contact_image_url_' + contactIndex;
                                hiddenInput.name = 'contact_image_url[]';
                                previewContainer.appendChild(hiddenInput);
                            }
                                hiddenInput.value = url;
                            }

                                initMediaSelectButtons();
                                });
        </script>
    </div>
    <?php
}

function krp_save_contacts() {
    // Kontakte speichern
    $contacts = array();
    if (isset($_POST['contact_name'])) {
        $contact_abteilung = isset($_POST['contact_abteilung']) ? array_map(function($bereichs) {
            return array_map('sanitize_text_field', $bereichs);
        }, $_POST['contact_abteilung']) : array();

        foreach ($_POST['contact_name'] as $index => $name) {
            $contacts[] = array(
                'contact_name' => sanitize_text_field($name),
                'contact_tel' => sanitize_text_field($_POST['contact_tel'][$index]),
                'contact_email' => sanitize_email($_POST['contact_email'][$index]),
                'contact_abteilung' => isset($contact_abteilung[$index]) ? $contact_abteilung[$index] : array(),
                'contact_info' => sanitize_textarea_field($_POST['contact_info'][$index]),
                'contact_image_url' => !empty($_POST['contact_image_url'][$index]) ? esc_url_raw($_POST['contact_image_url'][$index]) : esc_url_raw(get_template_directory_uri() . '/assets/img/Platzhalterbild.jpg'),
            );
        }
    }

    update_option('krp_saved_contacts', $contacts);

    // Weiterleitung
    wp_redirect(add_query_arg('updated', 'true', wp_get_referer()));
    exit;
}

add_action('admin_post_save_krp_contacts', 'krp_save_contacts');

?>