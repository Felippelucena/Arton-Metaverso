<?php
// Função para criar o menu no painel administrativo
function meu_menu_personalizado() {
    add_menu_page(
        'Ferramentas por Felippe Lucena', // Título da página
        'Tools by FL',       // Texto do menu
        'manage_options',             // Capacidade necessária
        'restricao_paginas',          // Slug do menu
        'pagina_plugin',    // Função que renderiza a página
        'dashicons-admin-tools',             // Ícone do menu
        20                            // Posição do menu
    );
    add_submenu_page(
    'restricao_paginas',          // Slug da página pai
    'Gerenciar Tabelas',          // Título da subpágina
    'Gerenciar Tabelas',          // Texto do submenu
    'manage_options',             // Capacidade necessária
    'gerenciar_tabelas',          // Slug da subpágina
    'interface_gerenciar_tabelas' // Função que renderiza a subpágina
    );
}
add_action('admin_menu', 'meu_menu_personalizado');

// Função que renderiza a página principal do plugin
function pagina_plugin() {
    ?>
    <style>
        .form-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .form-item {
            margin-bottom: 10px;
        }
        .form-item label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .form-item select, .form-item input[type="text"], .form-item input[type="number"] {
            width: 100%;
        }
        table.widefat {
            width: 100%;
            margin-bottom: 20px;
        }
        table.widefat th, table.widefat td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        fieldset {
            display: block;
            margin-inline-start: 2px;
            margin-inline-end: 2px;
            padding-block-start: 0.35em;
            padding-inline-start: 0.75em;
            padding-inline-end: 0.75em;
            padding-block-end: 0.625em;
            min-inline-size: min-content;
            border-width: 2px;
            border-style: groove;
            border-color: rgb(192, 192, 192);
            border-image: initial;
        }
        legend {
            font-size:15px;
        }
    </style>
    <div class="wrap" style="display:flex;flex-wrap:wrap;">
        <h1 style="width:100%">Ferramentas por Felippe Lucena</h1>
        <div style="width:50%">
            <?php
            // Incluir a interface de restrição de páginas
            interface_restricao_paginas();
            ?>
        </div>
        <div style="width:50%">
            <fieldset>
            <legend>Ferramenta</legend>
                <p>algum app aqui</p>
            </fieldset>
        </div>
        
    </div>
    <?php
}
?>
