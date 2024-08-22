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

    </div>

   <?php
}
