<?php
/*
Plugin Name: Gerenciador Tormenta20
Description: Plugin para gerenciar partidas de Tormenta20.
Version: 3.0
Author: Felippe Lucena
*/

// Evita acesso direto ao arquivo
if (! defined('ABSPATH')) {
    exit;
}

global $tormenta_db;

$tormenta_db = new wpdb(APP_DB_USER, APP_DB_PASSWORD,APP_DB_NAME,APP_DB_HOST);

require_once plugin_dir_path(__FILE__) . 'includes/consultas.php';
require_once plugin_dir_path(__FILE__) . 'templates/ficha.php';
require_once plugin_dir_path(__FILE__) . 'templates/nav_bar.php';
require_once plugin_dir_path(__FILE__) . 'templates/personagens.php';
require_once plugin_dir_path(__FILE__) . 'templates/modais/atributos.php';
require_once plugin_dir_path(__FILE__) . 'templates/modais/atributos_comprar.php';
require_once plugin_dir_path(__FILE__) . 'templates/modais/atributos_rolar.php';
require_once plugin_dir_path(__FILE__) . 'templates/modais/racas.php';
require_once plugin_dir_path(__FILE__) . 'templates/modais/ficha_guiada.php';
require_once plugin_dir_path(__FILE__) . 'templates/campos_ficha/atributos.php';

function tm_carregar_assets() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css');
    wp_enqueue_style('rpg-icons', 'https://cdnjs.cloudflare.com/ajax/libs/rpg-awesome/0.2.0/css/rpg-awesome.min.css');
    
    $assets_url = plugins_url('assets/', __FILE__);
    $assets_dir = plugin_dir_path(__FILE__) . 'assets/';

    $css_files = glob($assets_dir . 'css/*.css');
    foreach ($css_files as $css_file) {
        $css_filename = basename($css_file);
        wp_enqueue_style('tm-' . $css_filename, $assets_url . 'css/' . $css_filename);
    }

    $js_files = glob($assets_dir . 'js/*.js');
    $last_script_handle = '';
    foreach ($js_files as $js_file) {
        $js_filename = basename($js_file);
        $script_handle = 'tm-' . $js_filename;
        wp_enqueue_script($script_handle, $assets_url . 'js/' . $js_filename, array('jquery'), null, true);
        $last_script_handle = $script_handle;
    }

    wp_localize_script($last_script_handle, 'tmData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'userData' => array('display_name' => wp_get_current_user()->display_name)
    ));

    if (is_page('ficha-personagem')) {
        wp_enqueue_script('personagem-script', plugin_dir_url(__FILE__) . 'assets/js/ficha_personagem/personagem-manager.js', array('jquery'), null, true);
        wp_enqueue_script('ficha-guiada-script', plugin_dir_url(__FILE__) . 'assets/js/ficha_personagem/ficha-guiada-manager.js', array('personagem-script','jquery'), null, true);
        wp_enqueue_script('ficha-personagem-script', plugin_dir_url(__FILE__) . 'assets/js/ficha_personagem/ficha-manager.js', array('personagem-script', 'jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'tm_carregar_assets');


function dequeue_hello_elementor_styles() {
    wp_dequeue_style('hello-elementor');
    wp_deregister_style('hello-elementor');
}
add_action('wp_enqueue_scripts', 'dequeue_hello_elementor_styles', 20);