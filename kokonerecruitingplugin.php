<?php
/**
 * Plugin Name: Kokone Recruiting Plugin
 * Plugin URI: https://kokone.de
     * Description: Das Kokone Recruiting Plugin verwaltet Stellenausschreibungen auf WordPress-Websites und bietet einfache Tools für Erstellung, Kontakt und Bewerbung. Ideal zur Optimierung von Recruiting-Prozessen.
 * Version: 3.0.8
 * Author: Jan Lörwald
 * Author URI: https://hbwa.de
 * License: GPL3
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue Scripts and Styles
function krp_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style('kokonerecruitingplugin-css', plugins_url('/assets/css/krp.css', __FILE__));

    // Enqueue JS
    wp_enqueue_script('kokonerecruitingplugin-js', plugins_url('/assets/js/krp.js', __FILE__), array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'krp_enqueue_scripts');

// Define the base path for the includes directory
$krp_includes_dir_menu = plugin_dir_path(__FILE__) . 'includes/wordpress_menu/';

// Include other PHP files
require_once $krp_includes_dir_menu . 'menu_admin.php';
require_once $krp_includes_dir_menu . 'menu_website.php';
require_once $krp_includes_dir_menu . 'menu_ausbildung.php';
require_once $krp_includes_dir_menu . 'menu_design.php';
require_once $krp_includes_dir_menu . 'menu_jobs.php';
require_once $krp_includes_dir_menu . 'menu_kontakt.php';
require_once $krp_includes_dir_menu . 'menu_lizenz.php';

// GitHub-Update-Funktionalität hinzufügen
require 'libraries/plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Setze den Pfad zum Unterordner und den Branch
$updateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/Jan1217/Kokone-Recruiting-Plugin/',
    __FILE__,
    'kokonerecruitingplugin'
);

// Optional: Setze den Branch, falls nicht der Standard-Branch (z.B. 'main' oder 'master')
$updateChecker->setBranch('main');
