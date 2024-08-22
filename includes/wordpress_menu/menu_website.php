<?php
/*
 * Excluded file that handles the website tab in the WordPress krp admin menu
 */

// Callback functions for settings sections
function krp_website_section_callback() {
    ?>
        <h2>Website Einstellungen</h2>
    <?php
}

function krp_website_allgemein_section_callback() {
    ?>
        <h3>Allgemeine Einstellungen</h3>
        <p>In diesem Abschnitt kannst du grundlegende Einstellungen für deine Website konfigurieren. Danach kannst du zu den weiteren Tabs gehen für weitere Einstellungen.</p>
    <?php
}

function krp_website_allgemein_page_title_field_callback() {
    $krp_page_title = get_option('krp_website_page_title');
    ?>
    <input type="text" name="krp_website_page_title" value="<?php echo esc_attr($krp_page_title); ?>" placeholder="Ihr Seiten Name" style="width: 450px;"/>
    <p><i>* Wichtig: Falls Änderungen am Seiten Namen vorgenommen werden, bitte die Alte Seite löschen. Der Seiten Inhalt wird ohne Probleme übernommen, da eine neue Seite erstellt wird.</i></p>
    <?php
}

function krp_website_hero_section_callback() {
    ?>
        <h3>Hero Einstellungen</h3>
        <p>In den Hero Einstellungen kannst du den Hero von deiner Plugin-Seite anpassen. Standardmäßig ist kein Bild vorhanden, sondern es wird die Hero Hintergrundfarbe genommen so lange kein Bild hochgeladen wurde.</p>
    <?php
}

function krp_website_hero_text_field_callback() {
    $krp_hero_text = get_option('krp_website_hero_text_field');
    ?>
    <div class="krp-text-editor">
        <div class="toolbar" data-editor-id="editor-main-text-hero">
            <button type="button" onclick="toggleTag('b', 'editor-main-text-hero')">Bold</button>
            <button type="button" onclick="toggleTag('i', 'editor-main-text-hero')">Italic</button>
            <button type="button" onclick="toggleTag('u', 'editor-main-text-hero')">Underline</button>
            <button type="button" onclick="insertLineBreak('editor-main-text-hero')">Zeilenumbruch</button>
            <button type="button" onclick="toggleTag('ol', 'editor-main-text-hero')">List</button>
            <button type="button" onclick="insertLink('editor-main-text-hero')">Insert Link</button>
            <button type="button" onclick="toggleTag('h1', 'editor-main-text-hero')">H1</button>
            <button type="button" onclick="toggleTag('h2', 'editor-main-text-hero')">H2</button>
            <button type="button" onclick="toggleTag('h3', 'editor-main-text-hero')">H3</button>
            <button type="button" onclick="toggleTag('h4', 'editor-main-text-hero')">H4</button>
            <button type="button" onclick="toggleTag('h5', 'editor-main-text-hero')">H5</button>
            <button type="button" onclick="toggleTag('h6', 'editor-main-text-hero')">H6</button>
        </div>
        <div class="editor-container">
            <textarea id="editor-main-text-hero" name="krp_website_hero_text_field" style="width: 800px; height: 250px;"><?php echo esc_textarea($krp_hero_text); ?></textarea>
        </div>
    </div>

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

function krp_website_hero_text_select_position_field_callback() {
    // Hole die aktuellen Werte aus den Optionen
    $krp_hero_text_selection = get_option('krp_hero_text_selection_field', 'center');

    ?>
    <div id="krp-hero-text-selection">
        <label>
            <input type="radio" name="krp_hero_text_selection_field" value="left" <?php checked($krp_hero_text_selection, 'left'); ?>>
            Links
        </label><br>
        <label>
            <input type="radio" name="krp_hero_text_selection_field" value="center" <?php checked($krp_hero_text_selection, 'center'); ?>>
            Mitte (Standardwert)
        </label><br>
        <label>
            <input type="radio" name="krp_hero_text_selection_field" value="right" <?php checked($krp_hero_text_selection, 'right'); ?>>
            Rechts
        </label>
    </div>
    <?php
}

function krp_website_hero_text_color_field_callback() {
    $krp_hero_text_color = get_option('krp_website_hero_text_color');
    ?>
        <input type="color" name="krp_website_hero_text_color" value="<?php echo esc_attr($krp_hero_text_color); ?>" />
    <?php
}

function krp_website_hero_picture_callback() {
    // URL des derzeit ausgewählten Bildes, falls vorhanden
    $image_url = get_option('krp_website_hero_picture', '');

    ?>
    <div style="display: flex; align-items: center; padding:">
        <!-- Button zum Öffnen der Medienbibliothek -->
        <button type="button" id="upload_image_button">
            Bild auswählen
        </button>

        <!-- Container für die Bildvorschau -->
        <div id="image_preview" style="display: inline-block; margin-left: 15px;">
            <?php if ($image_url): ?>
                <img id="preview_image" src="<?php echo esc_url($image_url); ?>" style="max-width: 200px; height: auto;" />
            <?php endif; ?>
        </div>
    </div>


    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var frame;
            $('#upload_image_button').on('click', function(e) {
                e.preventDefault();

                // Wenn der Frame bereits existiert, öffne ihn erneut
                if (frame) {
                    frame.open();
                    return;
                }

                // Erstelle einen neuen Frame
                frame = wp.media({
                    title: 'Bild auswählen',
                    button: {
                        text: 'Bild auswählen'
                    },
                    multiple: false
                });

                // Wenn das Bild ausgewählt wird
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    var imageUrl = attachment.url;

                    // Bildvorschau aktualisieren
                    $('#image_preview').html('<img id="preview_image" src="' + imageUrl + '" style="max-width: 150px; max-height: 150px;" />');

                    // Speichere die Bild-URL in den Optionen
                    $.post(ajaxurl, {
                        action: 'krp_save_hero_picture',
                        image_url: imageUrl
                    });
                });

                // Öffne den Frame
                frame.open();
            });

            // Optionale Fehlerbehandlung: Zeigt eine Warnung, wenn keine Bild-URL vorhanden ist
            $('#upload_image_button').on('click', function() {
                if ($('#image_preview img').length === 0) {
                    alert('Bitte wählen Sie ein Bild aus der Medienbibliothek aus.');
                }
            });
        });
    </script>
    <?php
}
function krp_save_hero_picture() {
    if (isset($_POST['image_url'])) {
        $image_url = esc_url_raw($_POST['image_url']);
        update_option('krp_website_hero_picture', $image_url);
    }
    wp_die();
}
add_action('wp_ajax_krp_save_hero_picture', 'krp_save_hero_picture');
add_action('admin_enqueue_scripts', 'krp_enqueue_media');
function krp_enqueue_media()
{
    wp_enqueue_media();

}

function krp_website_hero_bg_color_callback() {
    $hero_bg_color = get_option('krp_website_hero_bg_color');
    ?>
        <input type="color" name="krp_website_hero_bg_color" value="<?php echo esc_attr($hero_bg_color); ?>" />
    <?php
}

function krp_website_secondary_navigation_section_callback() {
    ?>
        <h3>Sekundäre Navigation Einstellungen</h3>
        <p>Die Sekundäre Navigation wird automaitsch erstellt. Diese kannst du nur in der Farbe anpassen.</p>
    <?php
}

function krp_website_secondary_navigation_bg_color_callback() {
    $secondary_nav_bg_color = get_option('krp_website_secondary_navigation_bg_color');
    ?>
        <input type="color" name="krp_website_secondary_navigation_bg_color" value="<?php echo esc_attr($secondary_nav_bg_color); ?>" />
    <?php
}

function krp_website_secondary_navigation_contact_bg_color_callback() {
    $secondary_nav_contact_bg_color = get_option('krp_website_secondary_navigation_contact_bg_color');
    ?>
        <input type="color" name="krp_website_secondary_navigation_contact_bg_color" value="<?php echo esc_attr($secondary_nav_contact_bg_color); ?>" />
    <?php
}

function krp_website_secondary_navigation_text_color_callback() {
    $secondary_nav_text_color = get_option('krp_website_secondary_navigation_text_color');
    ?>
    <input type="color" name="krp_website_secondary_navigation_text_color" value="<?php echo esc_attr($secondary_nav_text_color); ?>" />
    <?php
}

function krp_website_main_section_callback() {
    ?>
        <h3>Hauptsektion Einstellungen</h3>
        <p>Du kannst hier den Reitern Jobs und Ausbildung noch Texte hinzufügen, über den Kacheln. Ebenfalls kannst du Einstellen wie viele Kacheln angezeigt werden sollen und was die Hintergrundfarbe sein soll..</p>
    <?php
}

function krp_website_main_text_jobs_field_callback() {
    $main_text_jobs_field = get_option('krp_website_main_text_jobs_field');
    ?>
    <div class="krp-text-editor">
        <div class="toolbar" data-editor-id="editor-main-text-jobs">
            <button type="button" onclick="toggleTag('b', 'editor-main-text-jobs')">Bold</button>
            <button type="button" onclick="toggleTag('i', 'editor-main-text-jobs')">Italic</button>
            <button type="button" onclick="toggleTag('u', 'editor-main-text-jobs')">Underline</button>
            <button type="button" onclick="insertLineBreak('editor-main-text-jobs')">Zeilenumbruch</button>
            <button type="button" onclick="toggleTag('ol', 'editor-main-text-jobs')">List</button>
            <button type="button" onclick="insertLink('editor-main-text-jobs')">Insert Link</button>
            <button type="button" onclick="toggleTag('h1', 'editor-main-text-jobs')">H1</button>
            <button type="button" onclick="toggleTag('h2', 'editor-main-text-jobs')">H2</button>
            <button type="button" onclick="toggleTag('h3', 'editor-main-text-jobs')">H3</button>
            <button type="button" onclick="toggleTag('h4', 'editor-main-text-jobs')">H4</button>
            <button type="button" onclick="toggleTag('h5', 'editor-main-text-jobs')">H5</button>
            <button type="button" onclick="toggleTag('h6', 'editor-main-text-jobs')">H6</button>
        </div>
        <div class="editor-container">
            <textarea id="editor-main-text-jobs" name="krp_website_main_text_jobs_field" style="width: 800px; height: 250px;"><?php echo esc_textarea($main_text_jobs_field); ?></textarea>
        </div>
    </div>

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

function krp_website_main_text_jobs_color_field_callback() {
    $main_text_jobs_color_field = get_option('kpr_website_main_text_jobs_color_field');
    ?>
    <input type="color" name="krp_website_main_text_jobs_color" value="<?php echo esc_attr($main_text_jobs_color_field); ?>" />
    <?php
}

function krp_website_main_text_ausbildung_field_callback() {
    $main_text_ausbildung_field = get_option('krp_website_main_text_ausbildung_field');
    ?>
    <div class="krp-text-editor">
        <div class="toolbar" data-editor-id="editor-main-text-ausbildung">
            <button type="button" onclick="toggleTag('b', 'editor-main-text-ausbildung')">Bold</button>
            <button type="button" onclick="toggleTag('i', 'editor-main-text-ausbildung')">Italic</button>
            <button type="button" onclick="toggleTag('u', 'editor-main-text-ausbildung')">Underline</button>
            <button type="button" onclick="insertLineBreak('editor-main-text-ausbildung')">Zeilenumbruch</button>
            <button type="button" onclick="toggleTag('ol', 'editor-main-text-ausbildung')">List</button>
            <button type="button" onclick="insertLink('editor-main-text-ausbildung')">Insert Link</button>
            <button type="button" onclick="toggleTag('h1', 'editor-main-text-ausbildung')">H1</button>
            <button type="button" onclick="toggleTag('h2', 'editor-main-text-ausbildung')">H2</button>
            <button type="button" onclick="toggleTag('h3', 'editor-main-text-ausbildung')">H3</button>
            <button type="button" onclick="toggleTag('h4', 'editor-main-text-ausbildung')">H4</button>
            <button type="button" onclick="toggleTag('h5', 'editor-main-text-ausbildung')">H5</button>
            <button type="button" onclick="toggleTag('h6', 'editor-main-text-ausbildung')">H6</button>
        </div>
        <div class="editor-container">
            <textarea id="editor-main-text-ausbildung" name="krp_website_main_text_ausbildung_field" style="width: 800px; height: 250px;"><?php echo esc_textarea($main_text_ausbildung_field); ?></textarea>
        </div>
    </div>

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

function krp_website_main_text_ausbildung_color_field_callback() {
    $main_text_ausbildung_color_field = get_option('kpr_website_main_text_ausbildung_color_field');
    ?>
    <input type="color" name="krp_website_main_text_ausbildung_color" value="<?php echo esc_attr($main_text_ausbildung_color_field); ?>" />
    <?php
}

function krp_website_main_bg_color_callback() {
    $main_bg_color = get_option('krp_website_main_bg_color');
    ?>
        <input type="color" name="krp_website_main_bg_color" value="<?php echo esc_attr($main_bg_color); ?>" />
    <?php
}

function krp_website_main_details_bg_color_callback() {
    $main_details_bg_color = get_option('krp_website_main_details_bg_color');
    ?>
    <input type="color" name="krp_website_main_details_bg_color" value="<?php echo esc_attr($main_details_bg_color); ?>" />
    <?php
}

function krp_website_main_selection_field_callback() {
    $main_selection_field = get_option('krp_website_main_selection_field');
    $options = array('Option 1', 'Option 2', 'Option 3');
    ?>
        <select name="krp_website_main_selection_field">
            <?php foreach ($options as $option) : ?>
                <?php $selected = ($main_selection_field === $option) ? 'selected' : ''; ?>
                <option value="<?php echo esc_attr($option); ?>" <?php echo $selected; ?>><?php echo esc_html($option); ?></option>
            <?php endforeach; ?>
        </select>
    <?php
}

function krp_website_main_selection_column_field_callback() {
    $main_selection_column_field = get_option('krp_website_main_selection_column_field');
    ?>
    <input id="krp_website_main_selection_column_field" class="krp_input_website" type="text" placeholder="Nur Zahlen erlaubt" name="krp_website_main_selection_column_field" value="<?php echo esc_attr($main_selection_column_field); ?>" />
    <script>
        const selectionColumnInput = document.getElementById('krp_website_main_selection_column_field');

        selectionColumnInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d]/g, '');
        });
    </script>
    <?php
}


?>