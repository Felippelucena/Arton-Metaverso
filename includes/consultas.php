<?php
function tm_consultar_livro() {
    global $wpdb;

    // Obter a lista de tabelas enviada pelo AJAX
    $tabelas = isset($_POST['tabelas']) ? $_POST['tabelas'] : [];

    $dados = array();

    foreach ($tabelas as $tabela) {
        // Montar o nome da tabela completo
        $nome_tabela = "wp_T20_" . $tabela;

        // Consultar os dados da tabela
        $resultado = $wpdb->get_results("SELECT * FROM {$nome_tabela}", ARRAY_A);

        // Adicionar os dados ao array de resposta
        $dados[$tabela] = $resultado;
    }

    // Retornar resposta JSON
    wp_send_json($dados);
}
add_action('wp_ajax_tm_consultar_livro', 'tm_consultar_livro');
add_action('wp_ajax_nopriv_tm_consultar_livro', 'tm_consultar_livro');