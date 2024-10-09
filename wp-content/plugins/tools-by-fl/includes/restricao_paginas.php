<?php
// Função para exibir e processar o formulário de restrição de páginas
function interface_restricao_paginas() {
    if (isset($_POST['paginas_restritas'])) {
        // Salvar as páginas restritas e tipos de usuários
        $restricoes = [];
        foreach ($_POST['paginas_restritas'] as $page_id => $user_role) {
            $restricoes[$page_id] = $user_role;
        }
        update_option('paginas_restritas', $restricoes);
        echo '<div class="updated"><p>Configurações salvas!</p></div>';
    }

    // Obter as páginas e restrições atuais
    $paginas_restritas = get_option('paginas_restritas', []);
    $todas_paginas = get_pages();
    $todos_roles = wp_roles()->roles;

    // Definir uma hierarquia dos papéis de usuário
    $hierarquia_roles = ['subscriber', 'contributor', 'author', 'editor', 'administrator'];

    ?>
    <fieldset>
    <legend>
    Restringir Acesso às Páginas 
    <i style="color:red; cursor:help" title="As páginas selecionadas serão ocultadas automaticamente do menu se o usuário não tiver permissão para visualizá-las, conforme o nível de acesso definido.">
        ?
    </i>
</legend>
    <div class="wrap">
        <form method="post">
            <div class="form-container">
                <?php foreach ($todas_paginas as $pagina): ?>
                    <div class="form-item">
                        <label for="user_roles_<?php echo $pagina->ID; ?>">
                            <?php echo $pagina->post_title; ?>
                        </label>
                        <select name="paginas_restritas[<?php echo $pagina->ID; ?>]" id="user_roles_<?php echo $pagina->ID; ?>">
                            <option value="all" <?php echo (isset($paginas_restritas[$pagina->ID]) && $paginas_restritas[$pagina->ID] == 'all') ? 'selected' : ''; ?>>Todos</option>
                            <?php foreach ($hierarquia_roles as $role_slug): ?>
                                <option value="<?php echo $role_slug; ?>" <?php echo (isset($paginas_restritas[$pagina->ID]) && $paginas_restritas[$pagina->ID] == $role_slug) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($role_slug); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endforeach; ?>
            </div>

            <br><br>
            <?php submit_button('Salvar Configurações'); ?>
        </form>
    </div>
    </fieldset>
    <?php
}
// Função para restringir o acesso às páginas com base na hierarquia de usuários
function restringir_acesso_paginas_personalizadas() {
    $paginas_restritas = get_option('paginas_restritas', []);
    $hierarquia_roles = ['subscriber', 'contributor', 'author', 'editor', 'administrator'];

    if (is_page() && array_key_exists(get_the_ID(), $paginas_restritas)) {
        $pagina_atual = $paginas_restritas[get_the_ID()];
        $user = wp_get_current_user();

        // Se a página está configurada para "Todos", permitir o acesso
        if ($pagina_atual == 'all') {
            return;
        }

        // Verificar o papel do usuário
        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url());
            exit;
        }

        // Obter o papel do usuário e comparar com a hierarquia
        $user_roles = $user->roles;
        $user_role = !empty($user_roles) ? $user_roles[0] : null;

        // Se o papel do usuário está abaixo do permitido, redirecionar para login
        if ($user_role && array_search($user_role, $hierarquia_roles) < array_search($pagina_atual, $hierarquia_roles)) {
            wp_redirect(wp_login_url());
            exit;
        }
    }
}
add_action('template_redirect', 'restringir_acesso_paginas_personalizadas');

// Função para ocultar páginas do menu de navegação para usuários sem permissão
function ocultar_paginas_menu($items, $args) {
    $paginas_restritas = get_option('paginas_restritas', []);
    $hierarquia_roles = ['subscriber', 'contributor', 'author', 'editor', 'administrator'];
    $user = wp_get_current_user();
    $user_role = !empty($user->roles) ? $user->roles[0] : null;

    foreach ($items as $key => $item) {
        if (array_key_exists($item->object_id, $paginas_restritas)) {
            $pagina_atual = $paginas_restritas[$item->object_id];

            // Se a página não é para "Todos" e o usuário não tem o papel necessário, remover do menu
            if ($pagina_atual !== 'all' && (!$user_role || array_search($user_role, $hierarquia_roles) < array_search($pagina_atual, $hierarquia_roles))) {
                unset($items[$key]);
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'ocultar_paginas_menu', 10, 2);
?>
