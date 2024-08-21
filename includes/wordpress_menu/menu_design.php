<?php
/*
 * Excluded file that handles the design tab in the WordPress KRP admin menu
 */

// Callback function for the design section
function krp_design_section_callback() {
    ?>
    <div class="krp_wp design_tab">
        <div class="krp_wp design_tab headline">
            <h2>Design Tab hier kannst du Design Einstellungen vornehmen</h2>
            <p>In diesem Bereich kannst du weitere Designeinstellungen vornehmen, um das Erscheinungsbild deiner Seite anzupassen.</p>
        </div>
    </div>
    <?php
}

function krp_design_css_section_callback() {
    ?>
    <div class="krp_wp design_tab custom_css">
        <h2>CSS Einstellungen</h2>
        <p>Diese CSS-Einstellungen sind nur für die Plugin-Seite relevant und beeinflussen nicht die Hauptseiten deiner Website.</p>
    </div>
    <?php
}

function krp_design_css_field_callback() {
    ?>
    <div class="krp_wp design_tab custom_css">
        <textarea id="custom_css_field" name="custom_css_field" rows="15" cols="180"><?php echo esc_textarea( get_option('custom_css_field') ); ?></textarea>
    </div>
    <?php
}

function krp_design_font_section_callback() {
    ?>
    <div class="krp_wp design_tab custom_fonts">
        <h2>Font Einstellungen</h2>
        <p>Standardmäßig werden die bereits ausgewählten Schriften verwendet. Wenn du andere Schriften für die Plugin-Seite verwenden möchtest, kannst du diese hier hochladen und auswählen.</p>
    </div>
    <?php
}

function krp_design_font_field_callback() {
    // Array mit verfügbaren Schriftarten
    $font_options = array(
        'Arial, Helvetica, sans-serif' => 'Arial',
        'Verdana, Geneva, sans-serif' => 'Verdana',
        'Georgia, serif' => 'Georgia',
        'Times New Roman, Times, serif' => 'Times New Roman',
        'Courier New, Courier, monospace' => 'Courier New',
        'Comic Sans MS, cursive, sans-serif' => 'Comic Sans MS',
    );

    $current_value = get_option('custom_fonts_field'); // Aktueller Wert der Option

    ?>
    <div class="krp_wp design_tab custom_fonts">
        <select id="custom_fonts_field" name="custom_fonts_field">
            <?php
            // Dropdown-Optionen basierend auf dem Array erstellen
            foreach ($font_options as $font_key => $font_name) {
                $selected = ($current_value == $font_key) ? 'selected' : '';
                echo '<option value="' . esc_attr($font_key) . '" ' . $selected . '>' . esc_html($font_name) . '</option>';
            }
            ?>
        </select>
    </div>
    <?php
}


function krp_design_padding_section_callback() {
    ?>
    <div class="krp_wp design_tab custom_mar_pad">
        <h2>Padding Einstellungen</h2>
        <p>Die hier festgelegten Padding-Einstellungen gelten ausschließlich für die Plugin-Seite.</p>
    </div>
    <?php
}

function krp_design_padding_field_callback() {
    $krp_design_padding = get_option('krp_design_padding_field')
    ?>
    <div class="krp_wp design_tab custom_mar_pad">
        <input type="text" id="padding_field" name="krp_design_padding_field" size="80" value="<?php echo esc_attr($krp_design_padding); ?>">
    </div>
    <?php
}

function krp_design_margin_section_callback() {
    ?>
    <div class="krp_wp design_tab custom_mar_pad">
        <h2>Margin Einstellungen</h2>
        <p>Die hier festgelegten Margin-Einstellungen gelten ausschließlich für die Plugin-Seite.</p>
    </div>
    <?php
}

function krp_design_margin_field_callback() {
    $krp_design_margin = get_option('krp_design_margin_field');
    ?>
    <div class="krp_wp design_tab custom_mar_pad">
        <input type="text" id="margin_field" name="krp_design_margin_field" size="80" value="<?php echo esc_attr($krp_design_margin); ?>"><br>
    </div>
    <?php
}
?>