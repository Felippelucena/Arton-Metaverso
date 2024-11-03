<?php
/*
Plugin Name: Gerenciador Tormenta20
Description: Plugin para gerenciar partidas de Tormenta20.
Version: 2.56
Author: Felippe Lucena
*/

// Evita acesso direto ao arquivo
if (! defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'includes/consultas.php';
require_once plugin_dir_path(__FILE__) . 'templates/ficha.php';
require_once plugin_dir_path(__FILE__) . 'templates/nav_bar.php';
require_once plugin_dir_path(__FILE__) . 'templates/personagens.php';

function tm_carregar_assets() {
    // Carregar jQuery, se ainda não estiver carregado
    wp_enqueue_script('jquery');
    // Carregar o Bootstrap Icons
    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1/font/bootstrap-icons.min.css');
    wp_enqueue_style('rpg-icons', 'https://cdnjs.cloudflare.com/ajax/libs/rpg-awesome/0.2.0/css/rpg-awesome.min.css');
    

    // Caminho para a pasta de assets dentro do plugin
    $assets_url = plugins_url('assets/', __FILE__);
    $assets_dir = plugin_dir_path(__FILE__) . 'assets/';

    // Carregar arquivos CSS
    $css_files = glob($assets_dir . 'css/*.css');
    foreach ($css_files as $css_file) {
        $css_filename = basename($css_file);
        wp_enqueue_style('tm-' . $css_filename, $assets_url . 'css/' . $css_filename);
    }

    // Carregar arquivos JS
    $js_files = glob($assets_dir . 'js/*.js');
    $last_script_handle = '';
    foreach ($js_files as $js_file) {
        $js_filename = basename($js_file);
        $script_handle = 'tm-' . $js_filename;
        wp_enqueue_script($script_handle, $assets_url . 'js/' . $js_filename, array('jquery'), null, true);
        $last_script_handle = $script_handle; // Guarda o último script
    }

    // Adiciona `tmData` e `ajaxurl` ao último script carregado
    wp_localize_script($last_script_handle, 'tmData', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'userData' => array('display_name' => wp_get_current_user()->display_name)
    ));

    // a pagina for /ficha_personagem carrega o script
    if (is_page('ficha-personagem')) {
        wp_enqueue_script('ficha-personagem-script', plugin_dir_url(__FILE__) . 'assets/js/ficha_personagem/personagem-manager.js', array('jquery'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'tm_carregar_assets');


function dequeue_hello_elementor_styles() {
    // Remove o estilo principal do tema Hello Elementor
    wp_dequeue_style('hello-elementor');
    wp_deregister_style('hello-elementor');
}
add_action('wp_enqueue_scripts', 'dequeue_hello_elementor_styles', 20);
