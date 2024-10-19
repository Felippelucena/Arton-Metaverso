<?php
/*
Plugin Name: Gerenciador Tormenta20
Description: Plugin para gerenciar partidas de Tormenta20.
Version: 1.0
Author: Felippe Lucena
*/

// Evita acesso direto ao arquivo
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . 'includes/personagem.php';
require_once plugin_dir_path(__FILE__) . 'includes/consultas.php';

function tm_carregar_scripts() {
    $current_user = wp_get_current_user();
    
    // Carregar jQuery, se ainda não estiver carregado
    wp_enqueue_script('jquery');
    
    // Carregar scripts globais
    wp_enqueue_script('utils-js', plugins_url('/assets/js/utils.js', __FILE__), array('jquery'), null, true);
    wp_enqueue_script('tm-personagem-js', plugins_url('/assets/js/personagem.js', __FILE__), array('jquery'), '1.0', true);
    wp_enqueue_script('tm-t20tools-js', plugins_url('/assets/js/t20tools.js', __FILE__), array('jquery'), '1.0', true);

    // Scripts específicos para a página "editor"
    if (is_page('editor')) {
        wp_enqueue_script('dados-js', plugins_url('/assets/js/editor/dados.js', __FILE__), array('jquery'), null, true);
        wp_enqueue_script('atributos-js', plugins_url('/assets/js/editor/atributos.js', __FILE__), array('jquery','utils-js'), null, true);
        wp_enqueue_script('tm-editor-manager-js', plugins_url('/assets/js/editor/personagem-manager.js', __FILE__), array('jquery', 'dados-js', 'utils-js','atributos-js'), null, true);
        // Adiciona as variáveis ajaxurl e userData para o JavaScript
        wp_localize_script('tm-editor-manager-js', 'tmData', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'userData' => array('display_name' => $current_user->display_name)
        ));

        // Adiciona a variável ajaxurl para o JavaScript específico do editor
        wp_localize_script('tm-editor-manager-js', 'ajaxurl', admin_url('admin-ajax.php'));

    }
}
add_action('wp_enqueue_scripts', 'tm_carregar_scripts');
