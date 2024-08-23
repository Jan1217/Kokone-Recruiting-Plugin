<?php

/*
 * Excluded file that creates and handles the wordpress krp menu
 */

// Create Admin Menu
function krp_create_menu() {
    add_menu_page(
        'Kokone Recruiting Plugin',
        'Kokone Recruiting Plugin',
        'manage_options',
        'krp-settings',
        'krp_settings_page',
        'dashicons-admin-generic'
    );
}
add_action('admin_menu', 'krp_create_menu');

function krp_settings_page() {
    $license_handler = new KokoneLicenseHandler();
    $is_license_valid = $license_handler->is_license_valid(); // Lizenzüberprüfung
    $page_title = get_option('krp_website_page_title');
    $page = get_page_by_title($page_title);
    $page_url = $page ? get_permalink($page->ID) : '#';

    ?>
    <div class="wrap">
        <h1>Kokone Recruiting Plugin</h1>
        <p>
            Willkommen beim Kokone Recruiting Plugin. Zu der Plugin Seite <a href="<?php echo esc_url($page_url); ?>" target="_blank"><?php echo esc_html($page_title); ?></a>
        </p>
        <h2 class="nav-tab-wrapper">
            <?php
            // Tabs definieren
            $tabs = [
                'website' => 'Website',
                'design' => 'Design',
                'kontakt' => 'Kontakt',
                'jobs' => 'Jobs',
                'ausbildung' => 'Ausbildung',
                'lizenz' => 'Lizenz',
            ];

            // Tabs anzeigen, wenn die Lizenz gültig ist, ansonsten nur Lizenz-Tab
            foreach ($tabs as $tab_id => $tab_name) {
                if ($tab_id === 'lizenz' || $is_license_valid) {
                    $active_class = ($tab_id === 'lizenz') ? 'nav-tab-active' : ''; // Lizenz-Tab als Standard aktiv
                    echo '<a href="#krp-tab-' . $tab_id . '" class="nav-tab ' . $active_class . '" onclick="switchTab(event, \'krp-tab-' . $tab_id . '\')">' . $tab_name . '</a>';
                }
            }
            ?>
        </h2>

        <!-- Lizenz-Tab immer anzeigen -->
        <form id="krp-settings-form-4" method="post" enctype="multipart/form-data">
            <div id="krp-tab-lizenz" class="krp-tab-content" style="display: block;">
                <?php echo $license_handler->getForm($_POST); ?>
            </div>
        </form>

        <?php if ($is_license_valid): ?>
            <!-- Form für Website, Design, Kontakt -->
            <form id="krp-settings-form-1" method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <?php
                settings_fields('krp_settings_group');
                do_settings_sections('krp-settings');

                foreach (['website', 'design', 'kontakt'] as $tab_id) {
                    ?>
                    <div id="krp-tab-<?php echo $tab_id; ?>" class="krp-tab-content" style="display: none;">
                        <?php
                        do_settings_sections('krp-settings-' . $tab_id);
                        submit_button('Aktualisieren', 'primary', 'krp_update_plugin_page');
                        submit_button('Seite Löschen', 'delete', 'krp_delete_plugin_page');
                        ?>
                    </div>
                    <?php
                }
                ?>
            </form>

            <!-- Form für Jobs -->
            <form id="krp-settings-form-2" method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <div id="krp-tab-jobs" class="krp-tab-content" style="display: none;">
                    <?php
                    do_settings_sections('krp-settings-jobs');
                    submit_button('Aktualisieren', 'primary', 'krp_update_plugin_page');
                    submit_button('Seite Löschen', 'delete', 'krp_delete_plugin_page');
                    ?>
                </div>
            </form>

            <!-- Form für Ausbildung -->
            <form id="krp-settings-form-3" method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data">
                <div id="krp-tab-ausbildung" class="krp-tab-content" style="display: none;">
                    <?php
                    do_settings_sections('krp-settings-ausbildung');
                    submit_button('Aktualisieren', 'primary', 'krp_update_plugin_page');
                    submit_button('Seite Löschen', 'delete', 'krp_delete_plugin_page');
                    ?>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <script>
        // Speichert den aktiven Tab im lokalen Speicher des Browsers
        function switchTab(event, tabId) {
            event.preventDefault();
            var tabs = document.querySelectorAll('.krp-tab-content');
            tabs.forEach(function(tab) {
                tab.style.display = 'none';
            });

            document.getElementById(tabId).style.display = 'block';
            var tabLinks = document.querySelectorAll('.nav-tab');
            tabLinks.forEach(function(link) {
                link.classList.remove('nav-tab-active');
            });

            event.currentTarget.classList.add('nav-tab-active');

            // Speichern des aktiven Tabs
            localStorage.setItem('activeTab', tabId);
        }

        // Beim Laden der Seite den zuletzt aktiven Tab wiederherstellen
        document.addEventListener('DOMContentLoaded', function() {
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                document.querySelectorAll('.krp-tab-content').forEach(function(tab) {
                    tab.style.display = 'none';
                });
                document.getElementById(activeTab).style.display = 'block';
                document.querySelector('.nav-tab[href="#' + activeTab + '"]').classList.add('nav-tab-active');
            } else {
                // Falls kein Tab gespeichert ist, zeige den Lizenz-Tab
                document.getElementById('krp-tab-lizenz').style.display = 'block';
                document.querySelector('.nav-tab[href="#krp-tab-lizenz"]').classList.add('nav-tab-active');
            }
        });
    </script>
    <?php
}

class KokoneLicenseHandler{
    public $api_key = 'ck_d57da536fe9a0d20d14b4206dcd6a3af1dddc440';
    public $api_secret = 'cs_4e03cbd546d824f1a84339af6a9a685909a807f9';

    //this function checks the license_key in case is is set
    public function __construct(){
        if(get_option('krp_license_key') != ""){
            add_action('init',[$this,'checkKey']);
        }
    }

    // Check if license is valid
    public function is_license_valid() {
        // Implement your logic here to determine if the license is valid
        // For example, you might check if the license key exists and is active
        return (get_option('krp_active') == "true");
    }

    //check the license key via the license server
    public function checkKey(){
        if (get_transient( 'krp_keycheck_result' ) == false) {
            $curl = curl_init();
            $license = get_option('krp_license_key');
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://digitalbestattungen.de/wp-json/lmfwc/v2/licenses/$license?consumer_key=".$this->api_key."&consumer_secret=".$this->api_secret,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                echo '<div class="updated fade"><p>Der Lizenzserver ist nicht erreichbar. Bitte kontaktieren Sie den Support.</p></div>';
            } else {
                $response = json_decode($response);
                if ($response->success == '1') {
                    $krp_license_expire = $response->data->expiresAt;
                    if($krp_license_expire !== null){
                        $krp_license_expire = substr($krp_license_expire, 0, -9);
                        $krp_license_expire = date("d.m.Y", strtotime($krp_license_expire));
                        if( strtotime($krp_license_expire) < strtotime('now') ) {
                            update_option('krp_active', "false");
                            delete_transient('krp_keycheck_result');
                            echo '<div class="updated fade"><p>KokoneCustomCalculator ist nicht aktiviert. Bitte geben Sie Ihren Lizenzschlüssel auf der Einstellungs-Seite ein.<br>Wenn Sie bereits einen Schlüssel erworben haben, erneuern Sie Ihre Lizenz.</p></div>';
                        } else {
                            update_option('krp_active', "true");
                            set_transient( 'krp_keycheck_result', "key_is_valid", 60*60*24 );
                        }
                    }
                    else{
                        update_option('krp_active', "true");
                        set_transient( 'krp_keycheck_result', "key_is_valid", 60*60*24 );
                    }
                } else {
                    update_option('krp_active', "false");
                    delete_transient( 'krp_keycheck_result' );
                    echo '<div class="updated fade"><p>KokoneCustomCalculator ist nicht aktiviert. Bitte geben Sie Ihren Lizenzschlüssel auf der Einstellungs-Seite ein.<br>Wenn Sie bereits einen Schlüssel erworben haben, erneuern Sie Ihre Lizenz.</p></div>';
                }
            }
        }
    }
    //display the form for the plugins options-menu
    public function getForm($post){
        $rstr = '<form action="" method="post">
            <table class="form-table">
                <tr>
                    <th style="width:100px;"><label for="krp_license_key">Lizenzschlüssel</label></th>
                    <td ><input class="regular-text" type="text" id="krp_license_key" name="krp_license_key"  value="'.get_option('krp_license_key').'" ></td>
                </tr>
            </table>';
        if (get_option('krp_license_key') == "" ){
            $rstr .= '<p>Bitte geben Sie zur Aktivierung Ihren Lizenzschlüssel ein. Sie haben diesen Schlüssel beim Kauf des Plugins erhalten.</p>';
        }
        else{
            if(get_option('krp_active') == "true"){
                $rstr .= '<p>Ablaufdatum Ihrer Lizenz: '.((get_option('krp_license_expire') == "0")?" Kein Ablaufdatum":get_option('krp_license_expire')).'</p>';
            }else{
                $rstr .= '<p>Die Lizenz ist nicht aktiviert.</p>';
            }
        }
        $rstr .= '<p class="submit">';
        if(get_option('krp_license_key') == ""){
            $rstr .= '<input type="submit" name="activate_license" value="Aktivieren" class="button-primary" />';
        }
        else{
            $rstr .= '<input type="submit" name="deactivate_license" value="Deaktivieren" class="button" />';
        }
        $rstr .= '</p>';
        $rstr .= '</form><style>/*input[value="Änderungen speichern"]{display: none!important;}*/</style>';
        if(isset($post['activate_license'])) {
            $license_key = $post['krp_license_key'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://digitalbestattungen.de/wp-json/lmfwc/v2/licenses/activate/$license_key?consumer_key=".$this->api_key."&consumer_secret=".$this->api_secret,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $response = json_decode($response);
            if ($err) {
                $rstr .='<div class="updated fade"><p>Der Lizenzserver ist nicht erreichbar. Bitte kontaktieren Sie den Support.</p></div>';
            } else {
                if ($response->success) {
                    $krp_license_expire = $response->data->expiresAt;
                    if($krp_license_expire !== null){
                        $krp_license_expire = substr($krp_license_expire, 0, -9);
                        $krp_license_expire = date("d.m.Y", strtotime($krp_license_expire));
                        if( strtotime($krp_license_expire) < strtotime('now') ) {
                            update_option('krp_license_expire', $krp_license_expire);
                            update_option('krp_license_key', "");
                            update_option('krp_active',"false");
                            $rstr .= '<div class="updated fade"><p>Ihre Lizenz ist abgelaufen</p></div>';
                        } else {
                            update_option('krp_license_expire', $krp_license_expire);
                            update_option('krp_license_key', $license_key);
                            update_option('krp_active',"true");
                            $rstr .= '<div class="updated fade"><p>Danke. Ihr Lizenzschlüssel ist nun aktiviert.</p></div>';
                        }
                    }
                    else{
                        update_option('krp_license_expire', '0');
                        update_option('krp_license_key', $license_key);
                        update_option('krp_active',"true");
                        $rstr .= '<div class="updated fade"><p>Danke. Ihr Lizenzschlüssel ist nun aktiviert. <input type="button" class="button" value="Neu laden" onclick="window.location.reload();"></input></p></div>';
                    }
                } else {
                    update_option('krp_license_key', '');
                    $rstr .= '<div class="updated fade"><p>Der Schlüssel den Sie eingegeben haben ist bereits aktiviert oder nicht vergeben.</p></div>';
                }
            }
        }
        if(isset($post['deactivate_license'])){
            $license_key = $post['krp_license_key'];
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://digitalbestattungen.de/wp-json/lmfwc/v2/licenses/deactivate/$license_key?consumer_key=".$this->api_key."&consumer_secret=".$this->api_secret,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $response = json_decode($response);
            if ($err) {
                echo '<div class="updated fade"><p>Der Lizenzserver ist nicht erreichbar. Bitte kontaktieren Sie den Support.</p></div>';
            } else {
                if ($response->success == '1') {
                    update_option('krp_license_key','');
                    update_option('krp_active','false');
                    delete_transient( 'krp_keycheck_result' );
                    echo '<div class="updated fade"><p>Danke. Der Lizenz-Schlüssel ist nun deaktiviert.</p></div>';
                } else {
                    update_option('krp_license_key', '');
                    delete_transient( 'krp_keycheck_result' );
                    echo '<div class="updated fade"><p>Der Lizenz-Schlüssel war nicht valide oder wurde bereits deaktiviert.</p></div>';
                }
            }
        }
        return $rstr;
    }
}

function krp_add_custom_css_to_head() {
    $custom_css = get_option('custom_css_field');
    if (!empty($custom_css)) {
        echo '<style type="text/css">' . $custom_css . '</style>';
    }
}
add_action('wp_head', 'krp_add_custom_css_to_head');

function krp_create_or_update_page() {
    $krp_page_title = get_option('krp_website_page_title');
    $custom_fonts = get_option('custom_fonts_field');

    $krp_hero_text = get_option('krp_website_hero_text_field');
    $krp_hero_text_selection = get_option('krp_hero_text_selection_field');
    $krp_hero_text_color = get_option('krp_website_hero_text_color');
    $krp_website_hero_image_url = get_option('krp_website_hero_picture', '');
    $krp_website_hero_bg_color = get_option('krp_website_hero_bg_color');

    $secondary_nav_bg_color = get_option('krp_website_secondary_navigation_bg_color');
    $secondary_nav_contact_bg_color = get_option('krp_website_secondary_navigation_contact_bg_color');
    $secondary_nav_text_color = get_option('krp_website_secondary_navigation_text_color');

    $main_text_jobs_field = get_option('krp_website_main_text_jobs_field');
    $main_text_jobs_select_position = get_option('krp_website_main_text_jobs_selection_position');
    $main_text_jobs_color_field = get_option('krp_website_main_text_jobs_color');
    $main_text_ausbildung_field = get_option('krp_website_main_text_ausbildung_field');
    $main_text_ausbildung_select_position = get_option('krp_website_main_text_ausbildung_selection_position');
    $main_text_ausbildung_color_field = get_option('krp_website_main_text_ausbildung_color');
    $main_bg_color = get_option('krp_website_main_bg_color');
    $main_details_bg_color = get_option('krp_website_main_details_bg_color');
    $main_selection_column_field = get_option('krp_website_main_selection_column_field');

    $krp_kontakt_tel = get_option('krp_kontakt_allgemein_tel_field');
    $krp_kontakt_address_street = get_option('krp_kontakt_allgemein_street_field');
    $krp_kontakt_address_number = get_option('krp_kontakt_allgemein_number_field');
    $krp_kontakt_address_zip = get_option('krp_kontakt_allgemein_zip_field');
    $krp_kontakt_address_city = get_option('krp_kontakt_allgemein_city_field');
    $krp_kontakt_address_additional = get_option('krp_kontakt_allgemein_additional_field');
    $krp_kontakt_email = get_option('krp_kontakt_allgemein_email_field');
    $krp_kontakt_oh_monday = get_option('krp_kontakt_allgemein_oh_monday');
    $krp_kontakt_oh_dienstag = get_option('krp_kontakt_allgemein_oh_dienstag');
    $krp_kontakt_oh_mittwoch = get_option('krp_kontakt_allgemein_oh_mittwoch');
    $krp_kontakt_oh_donnerstag = get_option('krp_kontakt_allgemein_oh_donnerstag');
    $krp_kontakt_oh_freitag = get_option('krp_kontakt_allgemein_oh_freitag');
    $krp_kontakt_oh_samstag = get_option('krp_kontakt_allgemein_oh_samstag');
    $krp_kontakt_oh_sonntag = get_option('krp_kontakt_allgemein_oh_sonntag');
    $krp_kontakt_oh_display = get_option('krp_kontakt_allgemein_oh_display');

    $krp_kontakt_fax = get_option('krp_kontakt_allgemein_fax_field');

    $jobs = get_option('krp_saved_jobs', array());
    $jobs_html = '';
    $job_details_html = '';
    $jobs_location_html = '';

    if (!empty($jobs)) {
        foreach ($jobs as $index => $job) {
            $job_image = esc_url($job['job_image']);
            $job_title = esc_html($job['job_title']);
            $job_bereich = implode(' und ', array_map('esc_html', $job['job_bereich']));
            $job_bereich_create_p_tag = implode('', array_map(function($bereich) {
                return '<p class="jbc-single-p-tag">' . esc_html($bereich) . '</p>';
            }, $job['job_bereich']));
            $job_id = $index + 1; // ID für Referenz
            $contact_person_job_details_name = esc_html($job['selected_contact_job_details_name']);
            $contact_person_job_details_tel = esc_html($job['selected_contact_job_details_tel']);
            $contact_person_job_details_email = esc_html($job['selected_contact_job_details_email']);
            $contact_person_job_details_info = esc_html($job['selected_contact_job_details_info']);
            $contact_person_job_details_image_url = esc_url_raw($job['selected_contact_job_details_image_url']);

            $jobs_html .= '
            <div>
                <div class="job-tile-main">
                    <div class="job-tile" data-job-id="' . $job_id . '" data-location="' . esc_attr($job['job_standort']) . '" data-hero-img="' . $job_image . '" onclick="showJobDetails(' . $job_id . ')">
                        <img src="' . $job_image . '" alt="' . $job_title . '" class="job-image">
                        <h2 class="job-title">' . $job_title . '</h2>
                        <div class="job-bereich">' . $job_bereich_create_p_tag . '</div>
                    </div>
                </div>
                <button class="job-tile-info-button" onclick="showJobDetails(' . $job_id . ')">Weitere Infos hier</button>
            </div>
            ';

            $jobs_location_html .= '
            <div class="job-tile-main">
                <div class="job-tile" data-location="' . esc_attr($job['job_standort']) . '" onclick="showContent(\'jobs\'); showJobList(); showJobDetails(' . $job_id . ')">
                    <img src="' . $job_image . '" alt="' . $job_title . '" class="job-image">
                    <p class="job_tile_standort" style="padding: unset">Standort ' . esc_attr($job['job_standort']) . '</p>
                    <h2 class="job-title">' . $job_title . '</h2>
                    <p class="job-bereich">Im Bereich ' . $job_bereich . '</p>
                </div>
                <button class="job-tile-info-button" onclick="showContent(\'jobs\'); showJobList(); showJobDetails(' . $job_id . ')">Weitere Infos hier</button>
            </div>
            ';

            $job_details_html .= '
            <div id="job-details-' . $job_id . '" class="job-details hidden">
                <div class="job-details-container">
                    <div class="job-details-fullwidth" style="display: flex; flex-wrap: wrap;">
                        <div class="job-details-left" style="flex: 1; padding: 20px;">
                        </div>
                        <div class="job-details-right" style="flex: 1.1; padding: 20px;">
                            <h2>
                                <span class="h1Intro">Zur Erweiterung unseres Teams am Standort ' . esc_html($job['job_standort']) . '</span>
                                <span class="h1Title">' . $job_title . '</span>
                                <span class="h1Subtitle">im Bereich ' . $job_bereich . '</span>
                            </h2>
                        </div>
                    </div>
                    <div class="job-details-left-right" style="display: flex; flex-wrap: wrap;">
                        <div class="job-details-left" style="flex: 1; padding: 20px;">
                            <div class="contact-box">
                                <img src="' . $contact_person_job_details_image_url . '" alt="' . esc_html($job['contact_name']) . '">
                                <h2>' . $contact_person_job_details_name . '</h2>
                                <p>' . $contact_person_job_details_info . '</p>
                                <div class="contact-person-tel-email-display">
                                    <p><strong>Telefon:</strong> <a href="tel:' . $contact_person_job_details_tel . '">' . $contact_person_job_details_tel . '</a></p>
                                    <p><strong>Email:</strong> <a href="mailto:' . $contact_person_job_details_email . '">' . $contact_person_job_details_email . '</a></p>
                                </div>
                            </div>
                            <div class="job_details_image">
                                <img src="' . $job_image . '" alt="' . $job_title . '">
                            </div>
                        </div>
                        <div class="job-details-right" style="flex: 1.1; padding: 20px;">
                            <p>' . wp_kses_post($job['job_company_info']) . '</p>
                            <h3>Ihre Tätigkeiten:</h3>
                            <p>' . wp_kses_post($job['job_tasks']) . '</p>
                            <h3>Wir freuen uns über Ihre Bewerbung, wenn Sie:</h3>
                            <p>' . wp_kses_post($job['job_application']) . '</p>
                            <a href="' . esc_url($job['job_application_pdf']) . '" target="_blank">Stellenanzeige als PDF herunterladen</a>
                            <h3>Bitte senden Sie Ihre Bewerbungsunterlagen an</h3>
                            <div>
                                <p style="padding-bottom: 0 !important;">' . $contact_person_job_details_name . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_street . ' ' . $krp_kontakt_address_number . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_zip . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_city . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_additional . '</p>
                                <p>' . $contact_person_job_details_email . '</p>
                            </div>  
                            <p><a href="#bewerbungsformular_jobs">Oder nutzen Sie das Bewerbungsformular unten</a></p>
                            <a href="#jobs" onclick="showJobList()">Zurück zu Jobs</a>
                        </div>
                    </div>
                </div>
                <!-- Bewerbungsformular -->
                <div class="form-container" id="bewerbungsformular_jobs">
                    <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
                        <input type="hidden" name="contact_person_email" value="' . $contact_person_job_details_email . '">
                        <div class="form-row">
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="job_bewerbung_vorname" class="required">Vorname</label>
                                    <input id="job_bewerbung_vorname" name="job_bewerbung_vorname" type="text" placeholder="Ihr Vorname">
                                </div>
                                <div class="form-group">
                                    <label for="job_bewerbung_strasse" class="required">Straße, Nr</label>
                                    <input id="job_bewerbung_strasse" name="job_bewerbung_strasse" type="text" placeholder="Straße">
                                </div>
                                <div class="form-group">
                                    <label for="job_bewerbung_telefon">Telefonnummer</label>
                                    <input id="job_bewerbung_telefon" name="job_bewerbung_telefon" type="text" placeholder="Telefonnummer">
                                </div>
                                 <div class="form-group">
                                    <label for="job_bewerbung_nachricht">Ihre Nachricht (optional)</label>
                                    <textarea id="job_bewerbung_nachricht" name="job_bewerbung_nachricht" rows="5" placeholder="Ihre Nachricht"></textarea>
                                </div>
                            </div>
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="job_bewerbung_nachname" class="required">Nachname</label>
                                    <input id="job_bewerbung_nachname" name="job_bewerbung_nachname" type="text" placeholder="Ihr Nachname">
                                </div>
                                <div class="form-group">
                                    <label for="job_bewerbung_ort" class="required">PLZ, Wohnort</label>
                                    <input id="job_bewerbung_ort" name="job_bewerbung_ort" type="text" placeholder="PLZ &amp; Wohnort">
                                </div>
                                <div class="form-group">
                                    <label for="job_bewerbung_email" class="required">E-Mail-Adresse</label>
                                    <input id="job_bewerbung_email" name="job_bewerbung_email" type="email" placeholder="E-Mail-Adresse">
                                </div>
                                <div class="form-group">
                                    <label for="job_bewerbung_dateien1">Bewerbungsunterlagen</label>
                                    <p>Max. 2 Dateien, jeweils nicht größer als 10MB. Erlaubt: PDF, Word, Zip, JPG, JPEG oder PNG.</p>
                                    <input id="job_bewerbung_dateien1" name="job_bewerbung_dateien1" type="file" accept=".pdf, .doc, .docx, .zip, .jpg, .jpeg, .png">
                                    <input id="job_bewerbung_dateien2" name="job_bewerbung_dateien2" type="file" accept=".pdf, .doc, .docx, .zip, .jpg, .jpeg, .png">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" name="job_bewerbung_submit" value="Bewerbung absenden">
                        </div>
                        <div class="job-bewerbung-error-message" id="error-message"></div>
                    </form>
                </div>
            </div>
            ';
        }
    } else {
        $jobs_html = '<p class="no_job_ausbildung">Derzeit sind keine Jobs verfügbar.</p>';
        $jobs_location_html = '<p class="no_job_ausbildung">Derzeit sind keine Jobs verfügbar.</p>';
    }

    $ausbildungen = get_option('krp_saved_ausbildungen', array());
    $ausbildung_html = '';
    $ausbildung_details_html = '';
    $ausbildung_location_html = '';


    if (!empty($ausbildungen)) {
        foreach ($ausbildungen as $index => $ausbildung) {
            $ausbildung_image = esc_url($ausbildung['ausbildung_image']);
            $ausbildung_title = esc_html($ausbildung['ausbildung_title']);
            $ausbildung_bereich = implode(' und ', array_map('esc_html', $ausbildung['ausbildung_bereich']));
            $ausbildung_id = $index + 1; // ID für Referenz
            $contact_person_ausbildung_details_name = esc_html($ausbildung['selected_contact_ausbildung_details_name']);
            $contact_person_ausbildung_details_tel = esc_html($ausbildung['selected_contact_ausbildung_details_tel']);
            $contact_person_ausbildung_details_email = esc_html($ausbildung['selected_contact_ausbildung_details_email']);
            $contact_person_ausbildung_details_info = esc_html($ausbildung['selected_contact_ausbildung_details_info']);
            $contact_person_ausbildung_details_image_url = esc_url_raw($ausbildung['selected_contact_ausbildung_details_image_url']);

            $ausbildung_html .= '
            <div class="ausbildung-tile-main">
                <div class="ausbildung-tile" data-location="' . esc_attr($ausbildung['ausbildung_standort']) . '" onclick="showAusbildungDetails(' . $ausbildung_id . ')">
                    <img src="' . $ausbildung_image . '" alt="' . $ausbildung_title . '" class="ausbildung-image">
                    <h2 class="ausbildung-title">' . $ausbildung_title . '</h2>
                    <p class="ausbildung-bereich">Im Bereich ' . $ausbildung_bereich . '</p>
                </div>
                <button class="ausbildung-tile-info-button" onclick="showAusbildungDetails(' . $ausbildung_id . ')">Weitere Infos hier</button>
            </div>
            ';

            $ausbildung_location_html .= '
            <div class="ausbildung-tile-main">
                <div class="ausbildung-tile" data-location="' . esc_attr($ausbildung['ausbildung_standort']) . '" onclick="showAusbildungDetails(' . $ausbildung_id . ')">
                    <img src="' . $ausbildung_image . '" alt="' . $ausbildung_title . '" class="ausbildung-image">
                    <p class="ausbildung_tile_standort" style="padding: unset">Standort ' . esc_attr($ausbildung['ausbildung_standort']) . '</p>
                    <h2 class="ausbildung-title">' . $ausbildung_title . '</h2>
                    <p class="ausbildung-bereich">Im Bereich ' . $ausbildung_bereich . '</p>
                </div>
                <button class="ausbildung-tile-info-button" onclick="showAusbildungDetails(' . $ausbildung_id . ')">Weitere Infos hier</button>
            </div>
            ';

            $ausbildung_details_html .= '
            <div id="ausbildung-details-' . $ausbildung_id . '" class="ausbildung-details hidden">
                <div class="ausbildung-details-container">
                    <div class="ausbildung-details-fullwidth" style="padding: 20px;">
                        <h2>
                            <span class="h2Intro">Zur Erweiterung unseres Teams am Standort ' . esc_html($ausbildung['ausbildung_standort']) . '</span>
                            <span class="h2Title">' . $ausbildung_title . '</span>
                            <span class="h2Subtitle">im Bereich ' . $ausbildung_bereich . '</span>
                        </h2>
                    </div>
                    <div class="ausbildung-details-left-right" style="display: flex; flex-wrap: wrap;">
                        <div class="ausbildung-details-left" style="flex: 1; padding: 20px;">
                            <div class="contact-box">
                                <img src="' . $contact_person_ausbildung_details_image_url . '" alt="' . esc_html($ausbildung['contact_name']) . '">
                                <h2>' . $contact_person_ausbildung_details_name . '</h2>
                                <p>' . $contact_person_ausbildung_details_info . '</p>
                                <div>
                                    <p><strong>Telefon:</strong> <a href="tel:' . $contact_person_ausbildung_details_tel . '">' . $contact_person_ausbildung_details_tel . '</a></p>
                                    <p><strong>Email:</strong> <a href="mailto:' . $contact_person_ausbildung_details_email . '">' . $contact_person_ausbildung_details_email . '</a></p>
                                </div>
                            </div>
                            <div class="ausbildung_details_image">
                                <img src="' . $ausbildung_image . '" alt="' . $ausbildung_title . '">
                            </div>
                        </div>
                        <div class="ausbildung-details-right" style="flex: 1.1; padding: 20px;">
                            <p>' . wp_kses_post($ausbildung['ausbildung_company_info']) . '</p>
                            <h2>Ihre Tätigkeiten:</h2>
                            <p>' . wp_kses_post($ausbildung['ausbildung_tasks']) . '</p>
                            <h2>Wir freuen uns über Ihre Bewerbung, wenn Sie:</h2>
                            <p>' . wp_kses_post($ausbildung['ausbildung_application']) . '</p>
                            <a href="' . esc_url($ausbildung['ausbildung_application_pdf']) . '" target="_blank">Ausbildungsanzeige als PDF herunterladen</a>
                            <h2>Bitte senden Sie Ihre Bewerbungsunterlagen an</h2>
                            <div>
                                <p style="padding-bottom: 0 !important;">' . $contact_person_ausbildung_details_name . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_street . ' ' . $krp_kontakt_address_number . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_zip . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_city . '</p>
                                <p style="padding-bottom: 0 !important;">' . $krp_kontakt_address_additional . '</p>
                                <p>' . $contact_person_ausbildung_details_email . '</p>
                            </div>  
                            <p><a href="#bewerbungsformular_ausbildung">Oder nutzen Sie das Bewerbungsformular unten</a></p>
                            <a href="#ausbildungen" onclick="showAusbildungList()">Zurück zu Ausbildungen</a>
                        </div>
                    </div>
                    <!-- Bewerbungsformular -->
                    <div class="form-container" id="bewerbungsformular_ausbildung">
                         <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateForm()">
                         <input type="hidden" name="ausbildung_contact_person_email" value="' . $contact_person_ausbildung_details_email . '">
                            <div class="form-row">
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_vorname" class="required">Vorname</label>
                                        <input id="ausbildung_bewerbung_vorname" name="ausbildung_bewerbung_vorname" type="text" placeholder="Ihr Vorname">
                                    </div>
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_strasse" class="required">Straße, Nr</label>
                                        <input id="ausbildung_bewerbung_strasse" name="ausbildung_bewerbung_strasse" type="text" placeholder="Straße">
                                    </div>
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_telefon">Telefonnummer</label>
                                        <input id="ausbildung_bewerbung_telefon" name="ausbildung_bewerbung_telefon" type="text" placeholder="Telefonnummer">
                                    </div>
                                     <div class="form-group">
                                        <label for="ausbildung_bewerbung_nachricht">Ihre Nachricht (optional)</label>
                                        <textarea id="ausbildung_bewerbung_nachricht" name="ausbildung_bewerbung_nachricht" rows="5" placeholder="Ihre Nachricht"></textarea>
                                    </div>
                                </div>
                                <div class="form-column">
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_nachname" class="required">Nachname</label>
                                        <input id="ausbildung_bewerbung_nachname" name="ausbildung_bewerbung_nachname" type="text" placeholder="Ihr Nachname">
                                    </div>
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_ort" class="required">PLZ, Wohnort</label>
                                        <input id="ausbildung_bewerbung_ort" name="ausbildung_bewerbung_ort" type="text" placeholder="PLZ &amp; Wohnort">
                                    </div>
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_email" class="required">E-Mail-Adresse</label>
                                        <input id="ausbildung_bewerbung_email" name="ausbildung_bewerbung_email" type="email" placeholder="E-Mail-Adresse">
                                    </div>
                                    <div class="form-group">
                                        <label for="ausbildung_bewerbung_dateien1">Bewerbungsunterlagen</label>
                                        <p>Max. 2 Dateien, jeweils nicht größer als 10MB. Erlaubt: PDF, Word, Zip, JPG, JPEG oder PNG.</p>
                                        <input id="ausbildung_bewerbung_dateien1" name="ausbildung_bewerbung_dateien1" type="file" accept=".pdf, .doc, .docx, .zip, .jpg, .jpeg, .png">
                                        <input id="ausbildung_bewerbung_dateien2" name="ausbildung_bewerbung_dateien2" type="file" accept=".pdf, .doc, .docx, .zip, .jpg, .jpeg, .png">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="submit" name="ausbildung_bewerbung_submit" value="Bewerbung absenden">
                            </div>
                            <div class="ausbildung-bewerbung-error-message" id="error-message"></div>
                        </form>
                    </div>
                </div>
            </div>';
        }
    } else {
        $ausbildung_html = '<p class="no_job_ausbildung">Derzeit sind keine Ausbildungen verfügbar.</p>';
        $ausbildung_location_html = '<p class="no_job_ausbildung">Derzeit sind keine Ausbildungen verfügbar.</p>';
    }

    // Abrufen des ausgewählten Kontakts
    $selected_contact_contact_tab = get_option('krp_selected_contact_contact_tab', '');

    // Optional: Abrufen der gespeicherten Kontakte, um mehr Details des ausgewählten Kontakts anzuzeigen
    $contacts = get_option('krp_saved_contacts', array());
    $contact_details = '';
    foreach ($contacts as $contact) {
        $contact_img = esc_url($contact['contact_image_url']);

        if ($contact['contact_name'] === $selected_contact_contact_tab) {
            $contact_details = '
                <div class="contact-box-main" style="display: flex; flex-direction: row-reverse;">
                    <img src="' . $contact_img . '" alt="' . esc_html($contact['contact_name']) . '" style="max-width: 30% !important;border: 2px solid ' . $secondary_nav_bg_color . ';border-radius: 0 8px 8px 0;">
                    <div class="contact-box" style="border-radius: 8px 0 0 8px !important;">
                        <h2>' . esc_html($contact['contact_name']) . ' - <span>' . implode(' und ', array_map('esc_html', $contact['contact_abteilung'])) . '</span></h2>
                        <p>' . esc_html($contact['contact_info']) . '</p>
                        <p>Bei Fragen erreichen Sie ' . esc_html($contact['contact_name']) . ' telefonisch unter der Nummer ' . esc_html($contact['contact_tel']) . '</p>
                        <h5>Bewerbungen</h5>
                        <p>Bei Bewerbungen nutzen Sie entweder das das Online-Bewerbungsformular für die jeweiligen <a href="#jobs" onclick="showContent(\'jobs\')">Jobs</a> oder für <a href="#ausbildung" onclick="showContent(\'ausbildung\')">Ausbildungen</a> oder Senden Sie eine E-Mail an ' . esc_html($contact['contact_email']) . '</p>
                    </div>
                </div>
                
            ';
            break;
        }
    }

    $unique_locations = array_unique(array_merge(
        array_map(function($job) { return $job['job_standort']; }, $jobs),
        array_map(function($ausbildung) { return $ausbildung['ausbildung_standort']; }, $ausbildungen)
    ));
    $location_options = '';
    foreach ($unique_locations as $location) {
        $location_options .= '<option value="' . esc_attr($location) . '">' . esc_html($location) . '</option>';
    }

    $page_content = '
        <style>
            /* WordPress */
            h1.entry-title.main_title {
                display: none;
            }
            h1.has-text-align-center.wp-block-post-title {
                display: none;
            }
            .wp-block-spacer {
                display: none;
            }
            body:not(.et-tb) #main-content .container, body:not(.et-tb-has-header) #main-content .container {
                padding-top: 0 !important;
            }
            /* Nur auf diese Seite anwenden */
            .plugin-page {
                font-family: ' . $custom_fonts . ';
            }
            .plugin-page .hero {
                background-color: ' . $krp_website_hero_bg_color . ';
                background-image: url(' . esc_url($krp_website_hero_image_url) . ');
                height: 300px;
                display: flex;
                justify-content: ' . $krp_hero_text_selection . ';
                align-items: center;
                color: white;
                text-align: center;
            }
            .plugin-page .hero h1 {
                font-size: 8em;
                color: ' . $krp_hero_text_color . ';
            }
            /* Sekundäre Navigation */
            .plugin-page .secondary-nav {
                display: flex;
                justify-content: center;
                background-color: ' . $secondary_nav_bg_color . ';
            }
            .plugin-page .secondary-nav .contact_color {
                background-color: ' . $secondary_nav_contact_bg_color . ';
                border-radius: 8px;
                margin: 4px;
            }
            .plugin-page .secondary-nav a {
                color: ' . $secondary_nav_text_color . ';
                padding: 15px 20px;
                text-decoration: none;
                display: inline-block;
                margin: 4px;
            }
            .krp_sec_nav_item.active {
                font-weight: bold;
                border-bottom: 6px solid ' . $secondary_nav_contact_bg_color . '; 
            }
            /* Ende Sekundäre Navigation */
            .plugin-page .content {
                background-color: ' . $main_bg_color . ';
                padding: 20px;
            }
            .plugin-page .hidden {
                display: none;
            }
            /* Jobs */
            .job-tiles-container {
                display: grid;
                grid-template-columns: repeat(' . $main_selection_column_field . ', 1fr);
                gap: 20px;
            }
            .job-tile {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                border-radius: 8px 8px 0 0 ;
                background-color: '. $main_details_bg_color .';
                cursor: pointer;
                padding: 10px;
                box-sizing: border-box;
                min-height: 400px;
            }
            .job-tile-main {
                display: flex;
                flex-direction: column;
            }
            .job-image {
                max-width: 100%;
                border-radius: 4px 4px 0 0;
                min-height: 200px;
                max-height: 200px;
                width: 100%;
                height: auto;
                object-fit: cover;
            }
            .job-title {
                margin: 0 0 10px 0;
                overflow: hidden;
                text-overflow: ellipsis;
                font-weight: 600; 
            }
            .job-bereich {
                color: #666;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .job-details {
                margin: 10px 0;
                padding: 20px;
            }
            .job-details-container {
                background-color: '. $main_details_bg_color .'
            }
            .job_details_image img {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                padding: 20px;
                border-radius: 8px;
                margin: 100px 0 0 0;
            }   
            button.job-tile-info-button {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                border-radius: 0 0 8px 8px;
                color: ' . $secondary_nav_text_color . ';
                background-color: ' . $secondary_nav_bg_color . '
            }
            button.job-tile-info-button:hover {
                cursor: pointer;
                color: unset;
                background-color: '. $main_details_bg_color .';
            }
            .job_tile_standort {
                background-color: ' . $secondary_nav_contact_bg_color . ';
                color: ' . $secondary_nav_text_color . ';
            }
            .h1Intro {
                display: block;
                font-weight: 300;
            }
            .h1Title {
                display: block;
                font-weight: bold;
                font-size: 35px; 
            }
            .h1Subtitle {
                font-weight: 300;
            }
            div#main-jobs-text {
                color: ' . $main_text_jobs_color_field . ';
                font-size: 16px;
                text-align: ' . $main_text_jobs_select_position . ';
            }
            .job-bereich p::before {
                content: "";
                display: inline-block;
                margin-right: 10px;
                width: 10px;
                height: 10px;
                background-color: ' . $main_bg_color . ';
            }
            .jbc-single-p-tag {
                margin-left: 10px;
            }
            /* Ausbildungen */
            .ausbildung-tiles-container {
                display: grid;
                grid-template-columns: repeat(' . $main_selection_column_field . ', 1fr);
                gap: 20px;
            }
            .ausbildung-tile {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                border-radius: 8px 8px 0 0;
                background-color: '. $main_details_bg_color .';
                text-align: center;
                cursor: pointer;
                padding: 10px;
                box-sizing: border-box;
                min-height: 400px;
            }
            .ausbildung-tile-main {
                display: flex;
                flex-direction: column;
            }
            .ausbildung-image {
                max-width: 100%;
                border-radius: 4px 4px 0 0;
                max-height: 200px;
                width: 100%;
                height: auto;
                object-fit: cover;
            }
            .ausbildung-title {
                font-size: 1.2em;
                margin: 10px 0;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .ausbildung-bereich {
                color: #666;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .ausbildung-details {
                margin: 10px 0;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: '. $main_details_bg_color .';
            }
            .ausbildung_details_image img {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                padding: 20px;
                border-radius: 8px;
                max-width: 360px;
                height: auto;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transform: rotate(-5deg);
                transform-origin: top left;
                margin: 100px 20px 20px 20px;
            }   
            button.ausbildung-tile-info-button {
                width: 100%;
                padding: 10px;
                border-radius: 0 0 8px 8px;
            }
            button.ausbildung-tile-info-button:hover {
                cursor: pointer;
                color: ' . $secondary_nav_text_color . ';
                background-color: ' . $secondary_nav_bg_color . ';
            }
            .ausbildung_tile_standort {
                background-color: ' . $secondary_nav_contact_bg_color . ';
                color: ' . $secondary_nav_text_color . ';
            }
            div#main-ausbildung-text {
                color: ' . $main_text_ausbildung_color_field . ';
                font-size: 16px;
                text-align: ' . $main_text_jobs_select_position . ';
            }
            div#main-ausbildung-text h3 {
                color: ' . $main_text_ausbildung_color_field . ';
            }
            /* Filter und Suche */
            .search-filter-container {
                display: flex;
                margin-bottom: 20px;
                gap: 10px;
            }
            .search-filter-container input, .search-filter-container select {
                padding: 10px;
                font-size: 16px;
                width: 2200px
            }
            .search-filter-container select {
                width: 100%;
            }
            .ort-restrict-headline {
                border-bottom:2px solid ' . $secondary_nav_bg_color . ';
                margin-bottom: 20px;
                padding: 10px;
                color: ' . $secondary_nav_bg_color . ';
                font-weight: bold;
            }
            .ort-restrict-job-tiles-container {
                display: grid;
                grid-template-columns: repeat(' . $main_selection_column_field . ', 1fr);
                gap: 20px;
            }
            .ort-restrict-ausbildung-tiles-container {
                display: grid;
                grid-template-columns: repeat(' . $main_selection_column_field . ', 1fr);
                gap: 20px;
            }
            button.ort-restrict-filter-button {
                padding: 0 15px 0 15px;
                border-radius: 8px;
                border: none;
                background-color: white;
            }
            button.ort-restrict-filter-button:hover {
                cursor: pointer;
                color: ' . $secondary_nav_text_color . ';
                background-color: ' . $secondary_nav_bg_color . ';
            }
            /* Bewerbungsformular */
             .form-container {
                padding: 20px;
                margin-top: 50px;
                border: 2px solid ' . $secondary_nav_bg_color . ';
                border-radius: 8px;
            }
            .form-row {
                display: flex;
                justify-content: space-between;
                gap: 20px;
            }
            .form-column {
                flex: 1;
            }
            .form-group {
                margin-bottom: 15px;
            }
            .form-group label {
                color: white; 
                display: block;
                margin-bottom: 5px;
            }
            .form-group label.required::after {
                content: ;
                color: red;
                margin-left: 5px;
            }
            .form-group input,
            .form-group textarea {
                width: 100%;
                padding: 8px;
                box-sizing: border-box;
            }
            .form-group input[type="file"] {
                padding: 3px;
            }
            .form-group input[type="submit"] {
                background-color: ' . $secondary_nav_bg_color . ';
                color: white;
                border: none;
                padding: 10px 15px;
                cursor: pointer;
            }
            .form-group input[type="submit"]:hover {
                color: black;
                background-color: '. $main_details_bg_color .';
            }
            .error-message {
                color: red;
                font-size: 0.875em;
                margin-top: 10px;
            }
            /* Kontakt Box*/
            .contact-box {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                padding: 20px;
                border-radius: 8px;
                background-color: '. $main_details_bg_color .';
            }
            .contact-box img {
                max-width: 100%;
                height: auto;
            }
            .contact-box h2 {
                font-weight: 600;
                margin: 10px 0 5px;
                font-size: 1.5em;
                color: #333;
            }
            .contact-box p {
                margin: 5px 0;
                color: #666;
            }
            .contact-box .department {
                font-weight: bold;
            }
            /* Mobile */
            @media only screen and (max-width: 768px) {
                .job-details-left {
                    flex-direction: column;
                    padding: 10px;
                }
                
                .contact-box,
                .job_details_image {
                    margin: 0 auto;
                    padding-top: 20px;
                    width: 100%;
                }
            
                .contact-box img,
                .job_details_image img {
                    max-width: 100%;
                    height: auto;
                    transform: none;
                    margin: 0 auto;
                }
            
                .job_details_image img {
                    border: 2px solid ' . $secondary_nav_bg_color . ';
                    padding: 10px;
                    border-radius: 4px;
                    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                }
            
                .contact-box h2 {
                    font-size: 1.2em;
                }
            }
            /* Weitere */
            .no_job_ausbildung {
                border-radius: 10px;
                background: '. $main_details_bg_color .';
                text-align: center;
                padding: 40px;
                font-weight: 900;
                font-size: 18px;
                border: 2px solid ' . $secondary_nav_bg_color . ';
            }
            .krp_kontakt_oh_display_div {
                display: ' . $krp_kontakt_oh_display .';
            }
            .krp_kontakt_allgemein_headline {
                border-bottom: 2px solid ' . $secondary_nav_bg_color . ';
                margin-bottom: 10px;
            }
            .krp_kontakt_allgemein_div_main {
                border: 2px solid ' . $secondary_nav_bg_color . ';
                padding: 20px;
                border-radius: 8px;
                background-color: '. $main_details_bg_color .';
                margin-bottom: 40px;
                display: flex;
                width: 50%;
            }
        </style>
        <div class="plugin-page">
            <div class="hero">
                <h1>' . $krp_hero_text . '</h1>
            </div>
            <div class="secondary-nav-container">
                <div class="secondary-nav" id="secondaryNav">
                    <a class="krp_sec_nav_item" href="#jobs" onclick="showContent(\'jobs\'); showJobList(); setActive(this)">Jobs</a>
                    <a class="krp_sec_nav_item" href="#ausbildung" onclick="showContent(\'ausbildung\'); showAusbildungList(); setActive(this)">Ausbildung</a>
                    <a class="krp_sec_nav_item" href="#ort-restrict" onclick="showContent(\'ort-restrict\'); setActive(this)">Ort einschränken</a>
                    <a class="krp_sec_nav_item contact_color" href="#kontakt" onclick="showContent(\'kontakt\'); setActive(this)">Kontakt</a>
                </div>
            </div>
            <div class="content">
                <div id="jobs">
                    <div id="main-jobs-text" style="margin-bottom: 40px">' . $main_text_jobs_field . '</div>
                    <div class="job-tiles-container">
                        ' . $jobs_html . '
                    </div>
                    <div id="job-details-container">
                        ' . $job_details_html . '
                    </div>
                </div>
                <div id="ausbildung" class="hidden">
                    <div id="main-ausbildung-text" style="margin-bottom: 40px">' . $main_text_ausbildung_field . '</div>
                    <div class="ausbildung-tiles-container">
                        ' . $ausbildung_html . '
                    </div>
                    <div id="ausbildung-details-container">
                        ' . $ausbildung_details_html . '
                    </div>
                </div>
                <div id="ort-restrict" class="hidden">
                    <div class="search-filter-container">
                        <input type="text" id="job-ausbildung-search" placeholder="Geben Sie Jobtitel oder Ausbildungsberuf ein...">
                        <select id="job-ausbildung-location-filter">
                            <option value="">Alle Standorte</option>
                            ' . $location_options . '
                        </select>
                        <button class="ort-restrict-filter-button" id="filter-button" title="Klicken Sie hier, um die Ergebnisse basierend auf Ihrer Suche zu filtern.">Filtern</button>
                    </div>
                    <div>
                        <h3 class="ort-restrict-headline">Verfügbare Jobs</h3>
                        <div class="ort-restrict-job-tiles-container" id="ort-restrict-job-tiles-container">
                            ' . $jobs_location_html . '
                        </div>
                        <div id="job-details-container">
                            ' . $job_details_html . '
                        </div>
                        <h3 class="ort-restrict-headline" style="margin-top: 20px;">Verfügbare Ausbildungen</h3>
                        <div class="ort-restrict-ausbildung-tiles-container" id="ort-restrict-ausbildung-tiles-container">
                            ' . $ausbildung_location_html . '
                        </div>
                    </div>
                </div>
                <div id="kontakt" class="hidden">
                    <h3 class="ort-restrict-headline">Allgemeine Informationen</h3>
                    <div class="krp_kontakt_allgemein_div_main">
                        <div style="flex: 1; padding-right: 20px;">
                            <div>
                                <h5 class="krp_kontakt_allgemein_headline">Telefon</h5>
                                <p>' . $krp_kontakt_tel . '</p>
                            </div>
                            <div>
                                <h5 class="krp_kontakt_allgemein_headline">Addresse</h5>
                                <p>' . $krp_kontakt_address_street . ' ' . $krp_kontakt_address_number . '</p>
                                <p>' . $krp_kontakt_address_zip . '</p>
                                <p>' . $krp_kontakt_address_city . '</p>
                                <p>' . $krp_kontakt_address_additional . '</p>
                            </div>
                            <div>
                                <h5 class="krp_kontakt_allgemein_headline">Email</h5>
                                <p>' . $krp_kontakt_email . '</p>
                            </div>
                            <div class="krp_kontakt_oh_display_div">
                                <h5 class="krp_kontakt_allgemein_headline">Öffnungszeiten:</h5>
                                <p>' . $krp_kontakt_oh_monday . '</p>
                                <p>' . $krp_kontakt_oh_dienstag . '</p>
                                <p>' . $krp_kontakt_oh_mittwoch . '</p>
                                <p>' . $krp_kontakt_oh_donnerstag . '</p>
                                <p>' . $krp_kontakt_oh_freitag . '</p>
                                <p>' . $krp_kontakt_oh_samstag . '</p>
                                <p>' . $krp_kontakt_oh_sonntag . '</p>
                            </div>
                            <div>
                                <h5 class="krp_kontakt_allgemein_headline">Fax</h5>
                                <p>' . $krp_kontakt_fax . '</p>
                            </div>
                        </div>
                    </div>
                    <div class="krp_kontakt_allgemein_div_main_kontakt_details">
                        <h3 class="ort-restrict-headline">Ihr Ansprechpartner</h3>
                        <div id="kontakt-details">
                            ' . $contact_details . '
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <script>
            function showContent(section) {
                const sections = document.querySelectorAll(".content > div");
                sections.forEach(sec => sec.classList.add("hidden"));
                document.getElementById(section).classList.remove("hidden");
            }
            function setActive(element) {
                const items = document.querySelectorAll(".krp_sec_nav_item");
                items.forEach(item => item.classList.remove("active"));
                element.classList.add("active");
            }
            function showJobDetails(jobId) {
                const jobDetails = document.querySelectorAll("#job-details-container > .job-details");
                jobDetails.forEach(detail => detail.classList.add("hidden"));
                const details = document.getElementById("job-details-" + jobId);
                details.classList.remove("hidden");
                document.querySelector(".job-tiles-container").classList.add("hidden");
                document.getElementById("main-jobs-text").classList.add("hidden");
                
                updateHeroImage(jobId);
            }
            function showJobList() {
                document.querySelector(".job-tiles-container").classList.remove("hidden");
                const jobDetails = document.querySelectorAll("#job-details-container > .job-details");
                jobDetails.forEach(detail => detail.classList.add("hidden"));
                document.getElementById("main-jobs-text").classList.remove("hidden");
                
                resetHeroImage();
            }
            function showAusbildungDetails(ausbildungId) {
                const ausbildungDetails = document.querySelectorAll("#ausbildung-details-container > .ausbildung-details");
                ausbildungDetails.forEach(detail => detail.classList.add("hidden"));
                const details = document.getElementById("ausbildung-details-" + ausbildungId);
                details.classList.remove("hidden");
                document.querySelector(".ausbildung-tiles-container").classList.add("hidden");
                document.getElementById("main-ausbildung-text").classList.add("hidden");
            }
            function showAusbildungList() {
                document.querySelector(".ausbildung-tiles-container").classList.remove("hidden");
                const ausbildungDetails = document.querySelectorAll("#ausbildung-details-container > .ausbildung-details");
                ausbildungDetails.forEach(detail => detail.classList.add("hidden"));
                document.getElementById("main-ausbildung-text").classList.remove("hidden");
            }
        </script>
    ';

    // Überprüfen, ob die Seite bereits existiert
    $args = array(
        'post_type' => 'page',
        'post_status' => 'any',
        'name' => sanitize_title($krp_page_title),
        'posts_per_page' => 1
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        // Bestehende Seite aktualisieren
        $existing_page_id = $query->posts[0]->ID;
        $update_page = array(
            'ID' => $existing_page_id,
            'post_content' => $page_content,
        );
        wp_update_post($update_page);
        add_action('admin_notices', function() use ($existing_page_id) {
            $page_url = get_permalink($existing_page_id);
            echo '<div class="updated"><p>Die Seite wurde erfolgreich aktualisiert. <a href="' . esc_url($page_url) . '" target="_blank">Zu Ihrer Seite</a></p></div>';
        });
    } else {
        // Neue Seite erstellen
        $new_page = array(
            'post_title' => $krp_page_title,
            'post_content' => $page_content,
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        $new_page_id = wp_insert_post($new_page);

        if ($new_page_id) {
            add_action('admin_notices', function () use ($new_page_id) {
                $page_url = get_permalink($new_page_id);
                echo '<div class="updated"><p>Die Seite wurde erfolgreich erstellt. <a href="' . esc_url($page_url) . '" target="_blank">Zu Ihrer Seite</a></p></div>';
            });
        } else {
            add_action('admin_notices', function () {
                echo '<div class="error"><p>Es gab ein Problem beim Erstellen der Seite.</p></div>';
            });
        }
    }
}

function change_hero_img_job_tile($krp_website_hero_image_url) {
    ?>
    <script>
        function updateHeroImage(jobId) {
            const jobTile = document.querySelector('.job-tile[data-job-id="' + jobId + '"]');
            const heroImage = jobTile.getAttribute('data-hero-img\');
            document.querySelector('.hero').style.backgroundImage = 'url(' + heroImage + ')';
        }

        function resetHeroImage() {
            const defaultHeroImage = '<?php echo esc_url($krp_website_hero_image_url); ?>'; // Use PHP to get default hero image
            document.querySelector('.hero\').style.backgroundImage = 'url(' + defaultHeroImage + ')';
        }
    </script>
    <?php
}

function filter_jobs_ausbildungen() {
    ?>
    <script>
        function filterJobsAusbildungen() {
            // Hole die Suchanfrage und den ausgewählten Standort
            var searchQuery = document.getElementById("job-ausbildung-search").value.toLowerCase();
            var selectedLocation = document.getElementById("job-ausbildung-location-filter").value.toLowerCase();

            // Filter Jobs
            var jobTiles = document.querySelectorAll(".ort-restrict-job-tiles-container .job-tile-main");
            jobTiles.forEach(function(tile) {
                var jobTitle = tile.querySelector(".job-title").textContent.toLowerCase();
                var jobLocation = tile.querySelector(".job-tile").getAttribute("data-location").toLowerCase();

                var titleMatch = jobTitle.includes(searchQuery);
                var locationMatch = selectedLocation === "" || jobLocation === selectedLocation;

                if (titleMatch && locationMatch) {
                    tile.style.display = "block";
                } else {
                    tile.style.display = "none";
                }
            });

            // Filter Ausbildungen
            var ausbildungTiles = document.querySelectorAll(".ort-restrict-ausbildung-tiles-container .ausbildung-tile-main");
            ausbildungTiles.forEach(function(tile) {
                var ausbildungTitle = tile.querySelector(".ausbildung-title").textContent.toLowerCase();
                var ausbildungLocation = tile.querySelector(".ausbildung-tile").getAttribute("data-location").toLowerCase();

                var titleMatch = ausbildungTitle.includes(searchQuery);
                var locationMatch = selectedLocation === "" || ausbildungLocation === selectedLocation;

                if (titleMatch && locationMatch) {
                    tile.style.display = "block";
                } else {
                    tile.style.display = "none";
                }
            });
        }

        // Event Listener für den Filter-Button
        document.getElementById("filter-button").addEventListener("click", filterJobsAusbildungen);
    </script>
    <?php
}

// Hook the function to wp_footer to ensure it is output on the page
add_action('wp_footer', 'filter_jobs_ausbildungen');

function job_bewerbung_form_handler() {
    if (isset($_POST['job_bewerbung_submit'])) {
        $errors = array();

        // Daten sammeln und validieren
        $job_bewerbung_vorname = sanitize_text_field($_POST['job_bewerbung_vorname']);
        $job_bewerbung_nachname = sanitize_text_field($_POST['job_bewerbung_nachname']);
        $job_bewerbung_strasse = sanitize_text_field($_POST['job_bewerbung_strasse']);
        $job_bewerbung_ort = sanitize_text_field($_POST['job_bewerbung_ort']);
        $job_bewerbung_telefon = sanitize_text_field($_POST['job_bewerbung_telefon']);
        $job_bewerbung_email = sanitize_email($_POST['job_bewerbung_email']);
        $job_bewerbung_nachricht = sanitize_textarea_field($_POST['job_bewerbung_nachricht']);

        // Kontaktpersonen-E-Mail aus dem Formular abrufen
        $job_contact_person_email = sanitize_email($_POST['job_contact_person_email']);

        // Validierung der E-Mail-Adresse
        if (!is_email($job_bewerbung_email) || !is_email($job_contact_person_email)) {
            $errors[] = 'Ungültige E-Mail-Adresse.';
        }

        // Wenn es Fehler gibt, Fehlermeldungen in die Div schreiben
        if (!empty($errors)) {
            echo '<script>
                    document.getElementById("job-bewerbung-error-message").innerHTML = "' . implode('<br>', $errors) . '";
                    document.getElementById("job-bewerbung-error-message").style.display = "block";
                  </script>';
            return;  // Verarbeitung abbrechen
        }

        // E-Mail-Adressen und Betreff
        $job_bewerbung_email_1 = $job_contact_person_email;
        $job_bewerbung_subject = 'Neue Job Bewerbung von ' . $job_bewerbung_vorname . ' ' . $job_bewerbung_nachname;

        // E-Mail-Inhalte
        $job_bewerbung_application_message = "<html><body>";
        $job_bewerbung_application_message .= "<h2>Neue Job Bewerbung</h2>";
        $job_bewerbung_application_message .= "<p><strong>Vorname:</strong> $job_bewerbung_vorname</p>";
        $job_bewerbung_application_message .= "<p><strong>Nachname:</strong> $job_bewerbung_nachname</p>";
        $job_bewerbung_application_message .= "<p><strong>Straße und Hausnummer:</strong> $job_bewerbung_strasse</p>";
        $job_bewerbung_application_message .= "<p><strong>PLZ, Wohnort:</strong> $job_bewerbung_ort</p>";
        $job_bewerbung_application_message .= "<p><strong>Telefonnummer:</strong> $job_bewerbung_telefon</p>";
        $job_bewerbung_application_message .= "<p><strong>Email:</strong> $job_bewerbung_email</p>";
        $job_bewerbung_application_message .= "<p><strong>Nachricht:</strong><br>$job_bewerbung_nachricht</p>";
        $job_bewerbung_application_message .= "</body></html>";

        // E-Mail-Header
        $job_bewerbung_headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: Bewerbung <noreply@hbwa.de>',
        );

        // Dateien verarbeiten und Anhänge hinzufügen
        $job_bewerbung_attachments = array();
        $job_bewerbung_upload_dir = wp_upload_dir();

        for ($i = 1; $i <= 2; $i++) {
            if (!empty($_FILES['dateien' . $i]['name'])) {
                $job_bewerbung_uploaded_file = $_FILES['dateien' . $i];
                if (is_uploaded_file($job_bewerbung_uploaded_file['tmp_name'])) {
                    $job_bewerbung_upload_file_path = $job_bewerbung_upload_dir['path'] . '/' . basename($job_bewerbung_uploaded_file['name']);
                    if (move_uploaded_file($job_bewerbung_uploaded_file['tmp_name'], $job_bewerbung_upload_file_path)) {
                        $job_bewerbung_attachments[] = $job_bewerbung_upload_file_path;
                    }
                }
            }
        }

        // Bewerbung senden
        if (!wp_mail($job_bewerbung_email_1, $job_bewerbung_subject, $job_bewerbung_application_message, $job_bewerbung_headers, $job_bewerbung_attachments)) {
            echo '<script>
                    document.getElementById("job-bewerbung-error-message").innerHTML = "Es gab ein Problem beim Senden Ihrer Bewerbung.";
                    document.getElementById("job-bewerbung-error-message").style.display = "block";
                  </script>';
            return;  // Verarbeitung abbrechen
        }

        wp_redirect(home_url('/plugin-seite'));
        exit;
    }
}
add_action('init', 'job_bewerbung_form_handler');

function krp_delete_page() {
    // Funktion zum Löschen der Seite
    $krp_page_title = get_option('krp_website_page_title'); // Titel der zu löschenden Seite

    // Überprüfen, ob die Seite existiert
    $args = array(
        'post_type' => 'page',
        'post_status' => 'any',
        'name' => sanitize_title($krp_page_title),
        'posts_per_page' => 1
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        // Seite gefunden, jetzt löschen
        $page_id = $query->posts[0]->ID;
        wp_delete_post($page_id, true); // true für endgültiges Löschen
        add_action('admin_notices', function() use ($krp_page_title) {
            echo '<div class="updated"><p>Die Seite "' . $krp_page_title . '" wurde erfolgreich gelöscht.</p></div>';
        });
    } else {
        // Seite nicht gefunden
        add_action('admin_notices', function() use ($krp_page_title) {
            echo '<div class="error"><p>Die Seite "' . $krp_page_title . '" konnte nicht gefunden werden.</p></div>';
        });
    }
}

// Register settings sections and fields
function krp_register_sections_and_fields() {
    // Website Tab
    add_settings_section('krp_website_section', '', 'krp_website_section_callback', 'krp-settings-website');

    add_settings_section('krp_website_allgemein_section', '', 'krp_website_allgemein_section_callback', 'krp-settings-website');
    add_settings_field('krp_website_allgemein_page_title_field', 'Seiten Name' , 'krp_website_allgemein_page_title_field_callback', 'krp-settings-website', 'krp_website_allgemein_section');

    add_settings_section('krp_website_hero_section', '', 'krp_website_hero_section_callback', 'krp-settings-website');
    add_settings_field('krp_website_hero_text_field', 'Hero Text', 'krp_website_hero_text_field_callback', 'krp-settings-website', 'krp_website_hero_section');
    add_settings_field('krp_website_hero_text_select_position_field', 'Hero Text Anordnung', 'krp_website_hero_text_select_position_field_callback', 'krp-settings-website', 'krp_website_hero_section');
    add_settings_field('krp_website_hero_text_color_field', 'Hero Text Farbe', 'krp_website_hero_text_color_field_callback', 'krp-settings-website', 'krp_website_hero_section');
    add_settings_field('krp_website_hero_picture_field', 'Hero Bild', 'krp_website_hero_picture_callback', 'krp-settings-website', 'krp_website_hero_section');
    add_settings_field('krp_website_hero_bg_color_field', 'Hero Hintergrundfarbe', 'krp_website_hero_bg_color_callback', 'krp-settings-website', 'krp_website_hero_section');

    add_settings_section('krp_website_secondary_navigation_section', '', 'krp_website_secondary_navigation_section_callback', 'krp-settings-website');
    add_settings_field('krp_website_secondary_navigation_bg_color_field', 'Sekundäre Navigation Hintergrundfarbe' , 'krp_website_secondary_navigation_bg_color_callback', 'krp-settings-website', 'krp_website_secondary_navigation_section');
    add_settings_field('krp_website_secondary_navigation_contact_bg_color_field', 'Kontakt Reiter Hintergrundfarbe', 'krp_website_secondary_navigation_contact_bg_color_callback', 'krp-settings-website', 'krp_website_secondary_navigation_section');
    add_settings_field('krp_website_secondary_navigation_text_color_field', 'Sekundäre Navigation Textfarbe', 'krp_website_secondary_navigation_text_color_callback', 'krp-settings-website', 'krp_website_secondary_navigation_section');

    add_settings_section('krp_website_main_section', '', 'krp_website_main_section_callback', 'krp-settings-website');
    add_settings_field('krp_website_main_text_jobs_field', 'Job Tab Text', 'krp_website_main_text_jobs_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_text_jobs_select_postion_field', 'Job Text Anordnung', 'krp_website_main_text_jobs_select_postion_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_text_jobs_color_field', 'Job Tab Textfarbe', 'krp_website_main_text_jobs_color_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_text_ausbildung_field', 'Ausbildung Tab Text', 'krp_website_main_text_ausbildung_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_text_ausbildung_select_postion_field', 'Ausbildung Text Anordnung', 'krp_website_main_text_ausbildung_select_postion_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_text_ausbildung_color_field', 'Ausbildung Tab Textfarbe', 'krp_website_main_text_ausbildung_color_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_bg_color_field', 'Main Hintergrundfarbe', 'krp_website_main_bg_color_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_details_bg_color_field', 'Main Job/ Ausbildung Hintergrundfarbe', 'krp_website_main_details_bg_color_callback', 'krp-settings-website', 'krp_website_main_section');
    //add_settings_field('krp_website_main_selection_field', 'Anzahl der insgesamten Jobs / Ausbildungen pro Seite', 'krp_website_main_selection_field_callback', 'krp-settings-website', 'krp_website_main_section');
    add_settings_field('krp_website_main_selection_column_field', 'Anzahl der Jobs / Ausbildungen pro Spalte', 'krp_website_main_selection_column_field_callback', 'krp-settings-website', 'krp_website_main_section');

    // Design Tab
    add_settings_section('krp_design_section', '', 'krp_design_section_callback', 'krp-settings-design');
    add_settings_section('krp_design_css_section', '', 'krp_design_css_section_callback', 'krp-settings-design');
    add_settings_field('krp_design_css_field', 'CSS Eingabe', 'krp_design_css_field_callback', 'krp-settings-design', 'krp_design_css_section');
    add_settings_section('krp_design_font_section', '', 'krp_design_font_section_callback', 'krp-settings-design');
    add_settings_field('krp_design_font_field', 'Font Auswahl', 'krp_design_font_field_callback', 'krp-settings-design', 'krp_design_font_section');
    add_settings_section('krp_design_padding_section', '', 'krp_design_padding_section_callback', 'krp-settings-design');
    add_settings_field('krp_design_padding_field', 'Padding Eingabe', 'krp_design_padding_field_callback', 'krp-settings-design', 'krp_design_padding_section');
    add_settings_section('krp_design_margin_section', '', 'krp_design_margin_section_callback', 'krp-settings-design');
    add_settings_field('krp_design_margin_field', 'Margin Eingabe', 'krp_design_margin_field_callback', 'krp-settings-design', 'krp_design_margin_section');

    // Job Tab
    add_settings_section('krp_jobs_create_section', '', 'krp_job_create_section_callback', 'krp-settings-jobs');

    // Ausbildung Tab
    add_settings_section('krp_ausbildung_create_section', '', 'krp_ausbildung_create_section_callback', 'krp-settings-ausbildung');

    // Kontakt Tab
    add_settings_section('krp_kontakt_section', 'Kontakt', 'krp_kontakt_section_callback', 'krp-settings-kontakt');
    add_settings_section('krp_kontakt_allgemein_section', '', 'krp_kontakt_allgemein_section_callback', 'krp-settings-kontakt');
    add_settings_field('krp_kontakt_allgemein_tel_field', 'Telefon', 'krp_kontakt_allgemein_tel_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_allgemein_address_field', 'Adresse', 'krp_kontakt_allgemein_address_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_allgemein_email_field', 'Email Adresse', 'krp_kontakt_allgemein_email_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_allgemein_opening_hours_field', 'Öffnungszeiten', 'krp_kontakt_allgemein_opening_hours_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_allgemein_fax_field', 'Fax', 'krp_kontakt_allgemein_fax_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_allgemein_company_standorte_field', 'Standorte', 'krp_kontakt_allgemein_company_standorte_field_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_field('krp_kontakt_select_section', 'Kontaktperson auf der Kontaktseite', 'krp_kontakt_select_section_callback', 'krp-settings-kontakt', 'krp_kontakt_allgemein_section');
    add_settings_section('krp_kontakt_create_section', '', 'krp_kontakt_create_section_callback', 'krp-settings-kontakt');
    register_setting('krp_kontakt_section', 'krp_contacts');

    // Lizenz Tab
    add_settings_section('krp_lizenz_section', 'Lizenz', 'krp_lizenz_section_callback', 'krp-settings-lizenz');
}
add_action('admin_init', 'krp_register_sections_and_fields');

// Save the new options
function krp_save_settings() {
    if (isset($_POST['krp_website_page_title'])) {
        update_option('krp_website_page_title', sanitize_text_field($_POST['krp_website_page_title']));
    }
    if (isset($_POST['krp_website_hero_text_field'])) {
        $html_content = wp_kses_post($_POST['krp_website_hero_text_field']);
        update_option('krp_website_hero_text_field', $html_content);
    }
    if (isset($_POST['krp_hero_text_selection_field'])) {
        update_option('krp_hero_text_selection_field', sanitize_text_field($_POST['krp_hero_text_selection_field']));
    }
    if (isset($_POST['krp_website_hero_text_color'])) {
        update_option('krp_website_hero_text_color', sanitize_text_field($_POST['krp_website_hero_text_color']));
    }
    if(isset($_POST['krp_website_hero_bg_color'])) {
        update_option('krp_website_hero_bg_color', sanitize_text_field($_POST['krp_website_hero_bg_color']));
    }
    if(isset($_POST['krp_website_secondary_navigation_bg_color'])) {
        update_option('krp_website_secondary_navigation_bg_color', sanitize_text_field($_POST['krp_website_secondary_navigation_bg_color']));
    }
    if(isset($_POST['krp_website_secondary_navigation_contact_bg_color'])) {
        update_option('krp_website_secondary_navigation_contact_bg_color', sanitize_text_field($_POST['krp_website_secondary_navigation_contact_bg_color']));
    }
    if(isset($_POST['krp_website_secondary_navigation_text_color'])) {
        update_option('krp_website_secondary_navigation_text_color', sanitize_text_field($_POST['krp_website_secondary_navigation_text_color']));
    }
    if (isset($_POST['krp_website_main_text_jobs_field'])) {
        $html_content = wp_kses_post($_POST['krp_website_main_text_jobs_field']);
        update_option('krp_website_main_text_jobs_field', $html_content);
    }
    if (isset($_POST['krp_website_main_text_jobs_selection_position'])) {
        update_option('krp_website_main_text_jobs_selection_position', sanitize_text_field($_POST['krp_website_main_text_jobs_selection_position']));
    }
    if(isset($_POST['krp_website_main_text_jobs_color'])) {
        update_option('krp_website_main_text_jobs_color', sanitize_text_field($_POST['krp_website_main_text_jobs_color']));
    }
    if (isset($_POST['krp_website_main_text_ausbildung_field'])) {
        $html_content = wp_kses_post($_POST['krp_website_main_text_ausbildung_field']);
        update_option('krp_website_main_text_ausbildung_field', $html_content);
    }
    if (isset($_POST['krp_website_main_text_ausbildung_selection_position'])) {
        update_option('krp_website_main_text_ausbildung_selection_position', sanitize_text_field($_POST['krp_website_main_text_ausbildung_selection_position']));
    }
    if(isset($_POST['krp_website_main_text_ausbildung_color'])) {
        update_option('krp_website_main_text_ausbildung_color', sanitize_text_field($_POST['krp_website_main_text_ausbildung_color']));
    }
    if(isset($_POST['krp_website_main_bg_color'])) {
        update_option('krp_website_main_bg_color', sanitize_text_field($_POST['krp_website_main_bg_color']));
    }
    if(isset($_POST['krp_website_main_details_bg_color'])) {
        update_option('krp_website_main_details_bg_color', sanitize_text_field($_POST['krp_website_main_details_bg_color']));
    }
    if (isset($_POST['krp_website_main_selection_field'])) {
        update_option('krp_website_main_selection_field', sanitize_text_field($_POST['krp_website_main_selection_field']));
    }
    if (isset($_POST['krp_website_main_selection_column_field'])) {
        update_option('krp_website_main_selection_column_field', sanitize_text_field($_POST['krp_website_main_selection_column_field']));
    }
    if (isset($_POST['custom_css_field'])) {
        update_option('custom_css_field', sanitize_text_field($_POST['custom_css_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_tel_field'])) {
        update_option('krp_kontakt_allgemein_tel_field', sanitize_text_field($_POST['krp_kontakt_allgemein_tel_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_street_field'])) {
        update_option('krp_kontakt_allgemein_street_field', sanitize_text_field($_POST['krp_kontakt_allgemein_street_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_number_field'])) {
        update_option('krp_kontakt_allgemein_number_field', sanitize_text_field($_POST['krp_kontakt_allgemein_number_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_zip_field'])) {
        update_option('krp_kontakt_allgemein_zip_field', sanitize_text_field($_POST['krp_kontakt_allgemein_zip_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_city_field'])) {
        update_option('krp_kontakt_allgemein_city_field', sanitize_text_field($_POST['krp_kontakt_allgemein_city_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_additional_field'])) {
        update_option('krp_kontakt_allgemein_additional_field', sanitize_text_field($_POST['krp_kontakt_allgemein_additional_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_email_field'])) {
        update_option('krp_kontakt_allgemein_email_field', sanitize_text_field($_POST['krp_kontakt_allgemein_email_field']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_monday'])) {
        update_option('krp_kontakt_allgemein_oh_monday', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_monday']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_dienstag'])) {
        update_option('krp_kontakt_allgemein_oh_dienstag', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_dienstag']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_mittwoch'])) {
        update_option('krp_kontakt_allgemein_oh_mittwoch', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_mittwoch']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_donnerstag'])) {
        update_option('krp_kontakt_allgemein_oh_donnerstag', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_donnerstag']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_freitag'])) {
        update_option('krp_kontakt_allgemein_oh_freitag', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_freitag']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_samstag'])) {
        update_option('krp_kontakt_allgemein_oh_samstag', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_samstag']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_sonntag'])) {
        update_option('krp_kontakt_allgemein_oh_sonntag', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_sonntag']));
    }
    if (isset($_POST['krp_kontakt_allgemein_oh_display'])) {
        update_option('krp_kontakt_allgemein_oh_display', sanitize_text_field($_POST['krp_kontakt_allgemein_oh_display']));
    }
    if (isset($_POST['krp_kontakt_allgemein_fax_field'])) {
        update_option('krp_kontakt_allgemein_fax_field', sanitize_text_field($_POST['krp_kontakt_allgemein_fax_field']));
    }
    if (isset($_POST['krp_company_standorte'])) {
        $standorte = array_map('sanitize_text_field', $_POST['krp_company_standorte']);
        update_option('krp_kontakt_allgemein_company_standorte_field', $standorte);
    }
    if (isset($_POST['selected_contact_contact_tab'])) {
        update_option('krp_selected_contact_contact_tab', sanitize_text_field($_POST['selected_contact_contact_tab']));
    }
    if (isset($_POST['krp_design_padding_field'])) {
        update_option('krp_design_padding_field', sanitize_text_field($_POST['krp_design_padding_field']));
    }
    if (isset($_POST['krp_design_margin_field'])) {
        update_option('krp_design_margin_field', sanitize_text_field($_POST['krp_design_margin_field']));
    }
}

// Hook into the form submission to create a new page
if (isset($_POST['krp_update_plugin_page'])) {
    add_action('admin_init', 'krp_create_or_update_page');
    add_action('admin_init', 'krp_save_settings');
}

// Hook into the form submission to delete a page
if (isset($_POST['krp_delete_plugin_page'])) {
    add_action('admin_init', 'krp_delete_page');
}

?>