<?php
session_start(); // Inicia a sessão para armazenar a tabela selecionada

function processar_selecao_tabela() {
    if (isset($_POST['selecionar_tabela'])) {
        $_SESSION['tabela_selecionada'] = sanitize_text_field($_POST['tabela_selecionada']);
    }
}
add_action('init', 'processar_selecao_tabela');

function exibir_mensagem_vinculacao($tipo, $mensagem) {
    add_action('admin_notices', function() use ($tipo, $mensagem) {
        $classe = ($tipo == 'erro') ? 'notice notice-error' : 'notice notice-success';
        echo "<div class='$classe'><p>$mensagem</p></div>";
    });
}

function interface_gerenciar_tabelas() {
    global $wpdb;

    // Buscar todas as tabelas com o prefixo do WordPress e relacionadas ao RPG
    $tabelas_rpg = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}T20_%'");

    // Recuperar a tabela selecionada da sessão
    $tabela_selecionada = $_SESSION['tabela_selecionada'] ?? null;

    ?>
    <style>
        fieldset {
            display: block;
            margin: 5px;
            padding: 10px;
            border-width: 2px;
            border-style: groove;
            border-color: rgb(192, 192, 192);
            border-image: initial;
        }
        legend {
            font-size: 15px;
        }
        .button {
            background-color: #0073aa;
            color: white;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }
        
        .button:hover {
            background-color: #005177;
        }

    </style>
    <div style="display: flex;flex-wrap:wrap;padding:20px;justify-content:center;">
        <!-- Gerenciar Tabelas de RPG -->
        <fieldset style="width: 30%; margin-bottom: 20px;">
            <legend>Db Editor</legend>
            <form method="POST">
                <h3>Selecionar Tabela</h3>
                <select name="tabela_selecionada">
                    <?php foreach ($tabelas_rpg as $tabela): ?>
                        <option value="<?php echo str_replace($wpdb->prefix, '', $tabela); ?>" 
                            <?php selected($tabela_selecionada, str_replace($wpdb->prefix, '', $tabela)); ?>>
                            <?php echo ucfirst(str_replace($wpdb->prefix, '', $tabela)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="submit" name="selecionar_tabela" value="Selecionar Tabela">
            </form>

            <!-- Formulário para criar uma nova tabela -->
            <form method="POST">
                <h3>Criar Nova Tabela</h3>
                <label for="nome_tabela">Nome da Tabela:</label>
                <input type="text" name="nome_tabela" required>
                <input type="submit" name="criar_tabela" value="Criar Tabela">
            </form>
        </fieldset>

        <?php
            if ($tabela_selecionada) {
                ?>
                <fieldset style="width: 60%; margin-bottom: 20px;">
                    <legend>Estrutura da Tabela: <?php echo ucfirst($tabela_selecionada); ?></legend>
                    <?php mostrar_campos_tabela($tabela_selecionada); ?>
                </fieldset>
            
                <fieldset style="width: 92%;">
                    <legend>Editor de Colunas</legend>
                    <?php 
                    if (isset($_POST['adicionar_nova_coluna'])) {
                        interface_criar_coluna($tabela_selecionada); 
                    } elseif (isset($_POST['atualizar_coluna'])) {
                        $coluna_atualizar = sanitize_text_field($_POST['coluna_atualizar']);
                        interface_atualizar_coluna($tabela_selecionada, $coluna_atualizar); 
                    } elseif (isset($_POST['abrir_vincular_coluna'])) {
                        interface_vincular_coluna($tabela_selecionada); 
                    }else {
                        echo "<p>Selecione uma ação para editar as colunas.</p>";
                    }?>
                </fieldset>
                
                <fieldset style="width: 55%; margin-bottom: 20px;">
                    <legend>Dados da Tabela: <?php echo ucfirst($tabela_selecionada); ?></legend>
                    <?php mostrar_dados_tabela($tabela_selecionada); ?>
                </fieldset>
            
                <fieldset style="width: 35%; margin-bottom: 20px;">
                    <legend>Editor de Dados</legend>
                    <?php 
                    if (isset($_POST['adicionar_dado'])) {
                        exibir_formulario_inserir_dados($tabela_selecionada); 
                    } elseif (isset($_POST['abrir_atualizar_dado'])) {
                        $id_dado = sanitize_text_field($_POST['id_dado']);
                        exibir_formulario_atualizar_dado($tabela_selecionada, $id_dado); 
                    } else {
                        echo "<p>Selecione uma ação para editar os dados.</p>";
                    }
                    ?>
                </fieldset>
                <?php
            }
        ?>
    </div>
    <?php
}

//EDITOR DE CAMPOS

// Função para exibir campos da tabela selecionada
function mostrar_campos_tabela($tabela) {
    global $wpdb;
    $tabela_completa = $wpdb->prefix . $tabela;
    $campos = $wpdb->get_results("DESCRIBE $tabela_completa");

    // Verifica chaves estrangeiras na tabela
    $chaves_estrangeiras = $wpdb->get_results("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = '$tabela_completa' 
        AND CONSTRAINT_SCHEMA = DATABASE() 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    // Cria um array para fácil acesso às chaves estrangeiras
    $foreign_keys = [];
    if ($chaves_estrangeiras) {
        foreach ($chaves_estrangeiras as $fk) {
            $foreign_keys[$fk->COLUMN_NAME] = [
                'referenced_table' => $fk->REFERENCED_TABLE_NAME,
                'referenced_column' => $fk->REFERENCED_COLUMN_NAME
            ];
        }
    }

    if ($campos) {
        echo "<table class='widefat fixed'>";
        echo "<thead><tr><th style='width: 130px;'>Campo</th><th style='width: 80px;'>Tipo</th><th style='width: 40px;'>Nulo</th><th style='width: 40px;'>Chave</th><th>Default</th><th style='width: 100px;'>Extra</th><th style='width: 100px;'>Vínculo</th><th>Ações</th></tr></thead>";
        echo "<tbody>";
        foreach ($campos as $campo) {
            echo "<tr>";
            echo "<td>{$campo->Field}</td>";
            echo "<td>{$campo->Type}</td>";
            echo "<td>{$campo->Null}</td>";
            echo "<td>{$campo->Key}</td>";
            echo "<td>{$campo->Default}</td>";
            echo "<td>{$campo->Extra}</td>";

            // Verifica se a coluna tem uma chave estrangeira
            if (isset($foreign_keys[$campo->Field])) {
                $referenced_table = $foreign_keys[$campo->Field]['referenced_table'];
                $referenced_column = $foreign_keys[$campo->Field]['referenced_column'];
                echo "<td>Vinculado a {$referenced_table}({$referenced_column})</td>";
            } else {
                echo "<td>Sem vínculo</td>";
            }

            // Adicionar botões de Excluir e Atualizar
            echo "<td>";
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
            echo "<input type='hidden' name='coluna_excluir' value='{$campo->Field}'>";
            echo "<input type='submit' name='excluir_coluna' value='Excluir' class='button'>";
            echo "</form>";

            echo " ";

            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
            echo "<input type='hidden' name='coluna_atualizar' value='{$campo->Field}'>";
            echo "<input type='submit' name='atualizar_coluna' value='Atualizar' class='button'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        // Botão "Adicionar Coluna"
        echo "<div style='display:flex; margin-top: 20px; gap:10px'>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
        echo "<input type='submit' name='adicionar_nova_coluna' value='Adicionar Coluna' class='button button-primary'>";
        echo "</form>";

        // Botão "Vincular Coluna"
        echo "<form method='POST'>";
        echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
        echo "<input type='submit' name='abrir_vincular_coluna' value='Vincular Coluna' class='button button-primary'>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>Não foi possível obter os campos da tabela.</p>";
    }
}

function interface_criar_coluna($tabela_selecionada) {
    $tipos_dados = [
        'INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'BIGINT', 'FLOAT', 
        'DOUBLE', 'DECIMAL(10,2)', 'VARCHAR(255)', 'TEXT', 'DATE', 
        'DATETIME', 'TIMESTAMP', 'TIME', 'CHAR(1)'
    ];

    ?>
    <!-- Formulário para adicionar uma nova coluna -->
    <fieldset class="form-section">
        <legend>Adicionar Coluna</legend>
        <form method="POST">
            <label for="nome_coluna">Nome da Coluna:</label>
            <input type="text" name="nome_coluna" required>

            <label for="tipo_coluna">Tipo de Dado:</label>
            <select name="tipo_coluna" required>
                <?php foreach ($tipos_dados as $tipo): ?>
                    <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="nulo">Permitir Nulo:</label>
            <select name="nulo">
                <option value="NOT NULL">Não</option>
                <option value="NULL">Sim</option>
            </select>

            <label for="default">Valor Padrão (opcional):</label>
            <input type="text" name="default">

            <label for="extras">Extras (opcional, ex: AUTO_INCREMENT):</label>
            <input type="text" name="extras">

            <input type="hidden" name="tabela_selecionada" value="<?php echo esc_attr($tabela_selecionada); ?>">
            <input type="submit" name="adicionar_coluna" value="Adicionar Coluna">
        </form>
    </fieldset>
    <?php
}

function interface_vincular_coluna($tabela_selecionada) {
    global $wpdb;

    // Obtém tabelas que começam com o prefixo 'T20_'
    $tabelas_referenciadas = $wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}T20_%'");

    ?>
    <fieldset class="form-section">
        <legend>Vincular Coluna</legend>
        <form method="POST">
            <label for="coluna_vincular">Coluna a Vincular:</label>
            <input type="text" name="coluna_vincular" required>

            <label for="tabela_referenciada">Tabela Referenciada:</label>
            <select name="tabela_referenciada" required>
                <?php foreach ($tabelas_referenciadas as $tabela): ?>
                    <option value="<?php echo esc_attr($tabela); ?>">
                        <?php selected($tabelas_referenciadas, str_replace($wpdb->prefix, '', $tabela)); ?>
                        <?php echo ucfirst(str_replace($wpdb->prefix, '', $tabela)); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="coluna_referenciada">Coluna Referenciada:</label>
            <input type="text" name="coluna_referenciada" required>

            <input type="hidden" name="tabela_selecionada" value="<?php echo esc_attr($tabela_selecionada); ?>">
            <input type="hidden" name="acao" value="vincular_coluna"> <!-- Adicione esse campo para identificar a ação -->
            <input type="submit" value="Vincular Coluna" class="button button-primary">
        </form>
    </fieldset>
    <?php
}
function processar_vincular_coluna() {
    if (isset($_POST['acao']) && $_POST['acao'] === 'vincular_coluna') {
        global $wpdb;

        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $coluna_vincular = sanitize_text_field($_POST['coluna_vincular']);
        $tabela_referenciada = sanitize_text_field($_POST['tabela_referenciada']);
        $tabela_referenciada = str_replace($wpdb->prefix, '', $tabela_referenciada);
        $coluna_referenciada = sanitize_text_field($_POST['coluna_referenciada']);
        $tabela_completa = $wpdb->prefix . $tabela_selecionada;

        // Verificação da existência da tabela selecionada
        if (!$wpdb->get_var("SHOW TABLES LIKE '$tabela_completa'")) {
            exibir_mensagem_vinculacao('erro', "Erro: A tabela '$tabela_completa' não existe.");
            return;
        }

        // Verificação da existência da coluna na tabela selecionada
        $colunas_selecionada = $wpdb->get_col("SHOW COLUMNS FROM $tabela_completa LIKE '$coluna_vincular'");
        if (empty($colunas_selecionada)) {
            exibir_mensagem_vinculacao('erro', "Erro: A coluna '$coluna_vincular' não existe na tabela '$tabela_completa'.");
            return;
        }

        // Verificação da existência da tabela referenciada
        if (!$wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}$tabela_referenciada'")) {
            exibir_mensagem_vinculacao('erro', "Erro: A tabela '{$wpdb->prefix}$tabela_referenciada' não existe.");
            return;
        }

        // Verificação da existência da coluna na tabela referenciada
        $colunas_referenciada = $wpdb->get_col("SHOW COLUMNS FROM {$wpdb->prefix}$tabela_referenciada LIKE '$coluna_referenciada'");
        if (empty($colunas_referenciada)) {
            exibir_mensagem_vinculacao('erro', "Erro: A coluna '$coluna_referenciada' não existe na tabela '{$wpdb->prefix}$tabela_referenciada'.");
            return;
        }

        // Criar a query de vínculo da coluna como chave estrangeira
        $sql = "ALTER TABLE $tabela_completa 
                ADD CONSTRAINT fk_{$coluna_vincular} 
                FOREIGN KEY ($coluna_vincular) 
                REFERENCES {$wpdb->prefix}$tabela_referenciada($coluna_referenciada)";

        // Executar a query
        $resultado = $wpdb->query($sql);

        if ($resultado !== false) {
            exibir_mensagem_vinculacao('sucesso', "Coluna '$coluna_vincular' vinculada à tabela '$tabela_referenciada' com sucesso!");
        } else {
            exibir_mensagem_vinculacao('erro', "Erro ao vincular a coluna '$coluna_vincular' à tabela '$tabela_referenciada'.");
        }
    }
}
add_action('init', 'processar_vincular_coluna');

function interface_atualizar_coluna($tabela_selecionada, $coluna_atualizar) {
    $tipos_dados = [
        'INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'BIGINT', 'FLOAT', 
        'DOUBLE', 'DECIMAL(10,2)', 'VARCHAR(255)', 'TEXT', 'DATE', 
        'DATETIME', 'TIMESTAMP', 'TIME', 'CHAR(1)'
    ];

    ?>
    <!-- Formulário para atualizar uma coluna existente -->
    <fieldset class="form-section">
        <legend>Atualizar Coluna: <strong><?php echo esc_html($coluna_atualizar); ?></strong></legend>
        <form method="POST">
            <input type="hidden" name="nome_coluna_atualizar" value="<?php echo esc_attr($coluna_atualizar); ?>">

            <label for="novo_nome_coluna">Novo Nome da Coluna (opcional):</label>
            <input type="text" name="novo_nome_coluna">

            <label for="novo_tipo_coluna">Novo Tipo de Dado (opcional):</label>
            <select name="novo_tipo_coluna">
                <option value="">-- Selecione --</option>
                <?php foreach ($tipos_dados as $tipo): ?>
                    <option value="<?php echo $tipo; ?>"><?php echo $tipo; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="novo_nulo">Novo Permitir Nulo (opcional):</label>
            <select name="novo_nulo">
                <option value="">-- Selecione --</option>
                <option value="NOT NULL">Não</option>
                <option value="NULL">Sim</option>
            </select>

            <label for="novo_default">Novo Valor Padrão (opcional):</label>
            <input type="text" name="novo_default">

            <label for="novo_extras">Novos Extras (opcional, ex: AUTO_INCREMENT):</label>
            <input type="text" name="novo_extras">

            <input type="hidden" name="tabela_selecionada" value="<?php echo esc_attr($tabela_selecionada); ?>">
            <input type="submit" name="salvar_alteracoes" value="Salvar Alterações">
        </form>
    </fieldset>
    <?php
}

// Função para criar uma nova tabela
function processar_criacao_tabela() {
    if (isset($_POST['criar_tabela'])) {
        global $wpdb;
        $nome_tabela = sanitize_text_field($_POST['nome_tabela']);
        $charset_collate = $wpdb->get_charset_collate();
        $nome_tabela_completo = 'T20_' . $nome_tabela;
        $sql = "CREATE TABLE {$wpdb->prefix}$nome_tabela_completo (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nome varchar(55) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        exibir_mensagem_vinculacao('sucesso', "Tabela '$nome_tabela' criada com sucesso!");
    }
}
add_action('init', 'processar_criacao_tabela');

// Processa a adição de coluna na tabela selecionada
function processar_adicao_coluna_dinamica() {
    if (isset($_POST['adicionar_coluna'])) {
        global $wpdb;

        // Sanitização dos dados recebidos
        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $tabela_completa = $wpdb->prefix . $tabela_selecionada;
        $nome_coluna = sanitize_text_field($_POST['nome_coluna']);
        $tipo_coluna = sanitize_text_field($_POST['tipo_coluna']);
        $nulo = sanitize_text_field($_POST['nulo']);
        $default = isset($_POST['default']) && $_POST['default'] !== '' ? sanitize_text_field($_POST['default']) : null;
        $extras = isset($_POST['extras']) && $_POST['extras'] !== '' ? sanitize_text_field($_POST['extras']) : null;

        // Construção da query SQL dinâmica
        $sql = "ALTER TABLE $tabela_completa ADD COLUMN $nome_coluna $tipo_coluna $nulo";

        // Adiciona valor padrão, se fornecido
        if (!is_null($default)) {
            // Verifica se o tipo de dado é numérico ou string para definir aspas no valor padrão
            if (in_array($tipo_coluna, ['VARCHAR(255)', 'TEXT', 'DATE', 'DATETIME', 'TIMESTAMP', 'TIME', 'CHAR(1)'])) {
                $sql .= " DEFAULT '$default'";
            } else {
                $sql .= " DEFAULT $default";
            }
        }

        // Adiciona extras, se fornecido (ex: AUTO_INCREMENT)
        if (!is_null($extras)) {
            $sql .= " $extras";
        }

        // Executa a query no banco de dados
        $resultado = $wpdb->query($sql);

        // Verifica o resultado e exibe mensagem apropriada
        if ($resultado !== false) {
            exibir_mensagem_vinculacao('sucesso', "Coluna '$nome_coluna' adicionada à tabela '$tabela_selecionada' com sucesso!");
        } else {
            exibir_mensagem_vinculacao('erro', "Erro ao adicionar a coluna '$nome_coluna' à tabela '$tabela_selecionada'.");
        }
    }
}
add_action('init', 'processar_adicao_coluna_dinamica');


// Processa a atualização de coluna na tabela selecionada
function processar_atualizacao_coluna_dinamica() {
    if (isset($_POST['salvar_alteracoes'])) {
        global $wpdb;
        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $tabela_completa = $wpdb->prefix . $tabela_selecionada;
        $nome_coluna_atualizar = sanitize_text_field($_POST['nome_coluna_atualizar']);
        $novo_nome_coluna = sanitize_text_field($_POST['novo_nome_coluna']);
        $novo_tipo_coluna = sanitize_text_field($_POST['novo_tipo_coluna']);
        $novo_nulo = sanitize_text_field($_POST['novo_nulo']);
        $novo_default = sanitize_text_field($_POST['novo_default']);
        $novo_extras = sanitize_text_field($_POST['novo_extras']);

        // Se o novo nome estiver vazio, mantém o nome atual
        if (empty($novo_nome_coluna)) {
            $novo_nome_coluna = $nome_coluna_atualizar;
        }

        // Monta a definição da coluna
        $column_definition = $novo_tipo_coluna;

        if (!empty($novo_nulo)) {
            $column_definition .= ' ' . $novo_nulo;
        }

        if (!empty($novo_default)) {
            $column_definition .= " DEFAULT '" . esc_sql($novo_default) . "'";
        }

        if (!empty($novo_extras)) {
            $column_definition .= ' ' . $novo_extras;
        }

        // Executa a consulta para atualizar a coluna
        $sql = "ALTER TABLE $tabela_completa CHANGE `$nome_coluna_atualizar` `$novo_nome_coluna` $column_definition;";
        $wpdb->query($sql);
        
        exibir_mensagem_vinculacao('sucesso', "Coluna '$nome_coluna_atualizar' atualizada com sucesso!");
    }
}
add_action('init', 'processar_atualizacao_coluna_dinamica');


// Processa a exclusão de coluna na tabela selecionada
function processar_exclusao_coluna_dinamica() {
    if (isset($_POST['excluir_coluna'])) {
        global $wpdb;
        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $tabela_completa = $wpdb->prefix . $tabela_selecionada;
        $coluna_excluir = sanitize_text_field($_POST['coluna_excluir']);

        $sql = "ALTER TABLE $tabela_completa DROP COLUMN $coluna_excluir;";
        $wpdb->query($sql);

        exibir_mensagem_vinculacao('sucesso', "Coluna '$coluna_excluir' excluída com sucesso!");
    }
}
add_action('init', 'processar_exclusao_coluna_dinamica');


//EDITOR DE DADOS

// Função para exibir os dados da tabela
function mostrar_dados_tabela($tabela) {
    global $wpdb;
    $tabela_completa = $wpdb->prefix . $tabela;
    $dados = $wpdb->get_results("SELECT * FROM $tabela_completa");

    if ($dados) {
        // Adicionar div com rolagem e limitar a altura
        echo "<div style='max-height: 400px; overflow-y: auto;'>"; // Adiciona rolagem se o conteúdo exceder 400px

        echo "<table class='widefat fixed'>";
        echo "<thead><tr>";
    
        // Cabeçalhos da tabela
        foreach ($dados[0] as $coluna => $valor) {
            echo "<th>$coluna</th>";
        }
        echo "<th>Ações</th>"; // Nova coluna 'Ações'
        echo "</tr></thead>";
    
        echo "<tbody>";
        

        foreach ($dados as $linha) {
            
            echo "<tr>";
            foreach ($linha as $valor) {
                // Limitar o número de caracteres exibidos
                $texto_curto = (strlen($valor) > 30) ? substr($valor, 0, 30) . '...' : $valor;
    
                // Exibir valor truncado com o valor completo no atributo title
                echo "<td title='" . esc_attr($valor) . "'>$texto_curto</td>";
            }
    
            // Coluna 'Ações' com botões 'Excluir' e 'Atualizar'
            echo "<td>";
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
            echo "<input type='hidden' name='id_dado' value='{$linha->id}'>";
            echo "<input type='submit' name='excluir_dado' value='Excluir' class='button'>";
            echo "</form>";
    
            echo " ";
    
            echo "<form method='POST' style='display:inline;'>";
            echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
            echo "<input type='hidden' name='id_dado' value='{$linha->id}'>";
            echo "<input type='submit' name='abrir_atualizar_dado' value='Atualizar' class='button'>";
            echo "</form>";
            echo "</td>";
    
            echo "</tr>";
            
            $contador_linhas++;
        }
        echo "</tbody></table>";
        echo "</div>"; // Fechar a div de rolagem
    
        // Botão "Adicionar Dado"
        echo "<div style='margin-top: 20px;'>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
        echo "<input type='submit' name='adicionar_dado' value='Adicionar Dado' class='button button-primary'>";
        echo "</form>";
        echo "</div>";
    } else {
        echo "<p>Não foi possível obter os dados da tabela.</p>";
        // Botão "Adicionar Dado"
        echo "<div style='margin-top: 20px;'>";
        echo "<form method='POST'>";
        echo "<input type='hidden' name='tabela_selecionada' value='$tabela'>";
        echo "<input type='submit' name='adicionar_dado' value='Adicionar Dado' class='button button-primary'>";
        echo "</form>";
        echo "</div>";
    }

}


// Função para verificar se o campo está vinculado a outra tabela
function verificar_vinculo($campo, $tabela_atual) {
    global $wpdb;

    // Supondo que as chaves estrangeiras seguem o padrão nome_campo e estão vinculadas a id na tabela referenciada
    $chave_estrangeira = $wpdb->get_results("
        SELECT REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = '{$tabela_atual}' 
        AND COLUMN_NAME = '{$campo}' 
        AND CONSTRAINT_SCHEMA = '{$wpdb->dbname}' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");

    return $chave_estrangeira ? $chave_estrangeira[0] : false;
}

// Função para exibir o formulário de inserção de novos dados
function exibir_formulario_inserir_dados($tabela) {
    global $wpdb;
    $tabela_completa = $wpdb->prefix . $tabela;
    $campos = $wpdb->get_results("DESCRIBE $tabela_completa");

    if ($campos) {
        ?>
        <form method="POST" style="margin-top: 20px;">
            <table class="form-table">
                <?php foreach ($campos as $campo): 
                    // Ignorar campos auto_increment
                    if ($campo->Extra === 'auto_increment') continue;

                    // Se o campo permite NULL ou tem um valor padrão, não deve ser obrigatório
                    $is_required = ($campo->Null === 'NO' && $campo->Default === null) ? 'required' : '';

                    // Verificar se o campo é uma chave estrangeira e está vinculado a outra tabela
                    $vinculo = verificar_vinculo($campo->Field, $tabela_completa);

                    if ($vinculo) {
                        // Obter as opções da tabela referenciada
                        $tabela_referenciada = $vinculo->REFERENCED_TABLE_NAME;
                        $opcoes = $wpdb->get_results("SELECT id, nome FROM $tabela_referenciada");

                        ?>
                        <tr>
                            <th>
                                <label for="<?php echo esc_attr($campo->Field); ?>"><?php echo esc_html($campo->Field); ?>:</label>
                            </th>
                            <td>
                                <select name="<?php echo esc_attr($campo->Field); ?>" <?php echo $is_required; ?>>
                                    <option value="">Selecione uma opção</option>
                                    <?php foreach ($opcoes as $opcao): ?>
                                        <option value="<?php echo esc_attr($opcao->id); ?>"><?php echo esc_html($opcao->nome); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                    } else {
                        // Determinar o tipo de campo
                        $field_type = 'text'; // Tipo padrão

                        // Ajustar o tipo de input baseado no tipo do campo
                        if (strpos($campo->Type, 'int') !== false || strpos($campo->Type, 'float') !== false || strpos($campo->Type, 'double') !== false) {
                            $field_type = 'number';
                        } elseif (strpos($campo->Type, 'text') !== false) {
                            $field_type = 'textarea'; // Para campos de texto longo
                        }
                        ?>
                        <tr>
                            <th>
                                <label for="<?php echo esc_attr($campo->Field); ?>"><?php echo esc_html($campo->Field); ?>:</label>
                            </th>
                            <td>
                                <?php if ($field_type === 'textarea'): ?>
                                    <textarea name="<?php echo esc_attr($campo->Field); ?>" rows="4" cols="50" <?php echo $is_required; ?>></textarea>
                                <?php else: ?>
                                    <input type="<?php echo $field_type; ?>" name="<?php echo esc_attr($campo->Field); ?>" <?php echo $is_required; ?>>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                endforeach; ?>
            </table>

            <input type="hidden" name="tabela_selecionada" value="<?php echo esc_attr($tabela); ?>">
            <input type="submit" name="inserir_dados" value="Inserir Dados" class="button button-primary">
        </form>
        <?php
    } else {
        exibir_mensagem_vinculacao('erro', "Não foi possível obter os campos da tabela.");
    }
}

// Processar a inserção de novos dados
function processar_insercao_dados() {
    if (isset($_POST['inserir_dados'])) {
        global $wpdb;
        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $tabela_completa = $wpdb->prefix . $tabela_selecionada;

        // Obter colunas da tabela
        $campos = $wpdb->get_results("DESCRIBE $tabela_completa");
        $dados = [];

        // Criar um array associativo com os valores enviados
        foreach ($campos as $campo) {
            if ($campo->Extra === 'auto_increment') continue; // Ignorar auto_increment

            // Verificar se o campo está presente no $_POST e não é vazio
            if (isset($_POST[$campo->Field]) && $_POST[$campo->Field] !== '') {
                $dados[$campo->Field] = sanitize_text_field($_POST[$campo->Field]);
            }
        }

        // Inserir os dados na tabela (somente campos preenchidos)
        $resultado = $wpdb->insert($tabela_completa, $dados);

        // Verificar sucesso
        if ($resultado) {
            exibir_mensagem_vinculacao('sucesso', "Dados inseridos com sucesso!");
        } else {
            exibir_mensagem_vinculacao('erro', "Erro ao inserir dados.");
        }
    }
}
add_action('init', 'processar_insercao_dados');

// Função para exibir o formulário de atualização de dados
function exibir_formulario_atualizar_dado($tabela, $id) {
    global $wpdb;
    $tabela_completa = $wpdb->prefix . $tabela;
    $dados = $wpdb->get_row("SELECT * FROM $tabela_completa WHERE id = $id");
    $campos = $wpdb->get_results("DESCRIBE $tabela_completa");

    // Exibir o formulário normalmente
    if ($dados && $campos) {
        ?>
        <form method="POST" style="margin-top: 20px;">
            <table class="form-table">
                <?php foreach ($campos as $campo): 
                    $nome_campo = $campo->Field;
                    $valor = isset($dados->$nome_campo) ? $dados->$nome_campo : '';

                    // Ignorar campos auto_increment
                    if ($campo->Extra === 'auto_increment') continue;

                    // Se o campo permite NULL ou tem um valor padrão, não deve ser obrigatório
                    $is_required = ($campo->Null === 'NO' && $campo->Default === null) ? 'required' : '';

                    // Determinar o tipo de campo
                    $field_type = 'text'; // Tipo padrão

                    // Ajustar o tipo de input baseado no tipo do campo
                    if (strpos($campo->Type, 'int') !== false || strpos($campo->Type, 'float') !== false || strpos($campo->Type, 'double') !== false) {
                        $field_type = 'number';
                    } elseif (strpos($campo->Type, 'text') !== false) {
                        $field_type = 'textarea'; // Para campos de texto longo
                    }
                ?>
                    <tr>
                        <th>
                            <label for="<?php echo esc_attr($nome_campo); ?>"><?php echo esc_html($nome_campo); ?>:</label>
                        </th>
                        <td>
                            <?php if ($nome_campo === 'id'): ?>
                                <input type="text" name="<?php echo esc_attr($nome_campo); ?>" value="<?php echo esc_attr($valor); ?>" readonly>
                            <?php elseif ($field_type === 'textarea'): ?>
                                <textarea name="<?php echo esc_attr($nome_campo); ?>" rows="4" cols="50" <?php echo $is_required; ?>><?php echo esc_html($valor); ?></textarea>
                            <?php else: ?>
                                <input type="<?php echo esc_attr($field_type); ?>" name="<?php echo esc_attr($nome_campo); ?>" value="<?php echo esc_attr($valor); ?>" <?php echo $is_required; ?>>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <input type="hidden" name="tabela_selecionada" value="<?php echo esc_attr($tabela); ?>">
            <input type="hidden" name="id_atualizar" value="<?php echo esc_attr($id); ?>">
            <input type="submit" name="atualizar_dado" value="Atualizar Dados" class="button button-primary">
        </form>
        <?php
    } else {
        exibir_mensagem_vinculacao('erro', "Não foi possível obter os dados para atualização.");
    }
}

// Função para processar a atualização de dados
function processar_atualizacao_dado() {
    if (isset($_POST['atualizar_dado'])) {
        global $wpdb;

        $tabela = sanitize_text_field($_POST['tabela_selecionada']);
        $id = sanitize_text_field($_POST['id_atualizar']);
        
        // Coleta os campos a partir da estrutura da tabela
        $tabela_completa = $wpdb->prefix . $tabela;
        $campos = $wpdb->get_results("DESCRIBE $tabela_completa");
        
        // Coletar dados atualizados do formulário
        $dados_atualizados = [];
        foreach ($campos as $campo) {
            $nome_campo = $campo->Field;

            // Ignorar campos auto_increment
            if ($campo->Extra === 'auto_increment') continue;

            // Coletar dados atualizados, mantendo o valor original se o campo não estiver no $_POST
            $dados_atualizados[$nome_campo] = isset($_POST[$nome_campo]) ? sanitize_text_field($_POST[$nome_campo]) : '';
        }

        // Remover o campo 'id' dos dados a serem atualizados, pois ele não pode ser atualizado
        unset($dados_atualizados['id']);

        // Atualizar os dados da tabela
        $resultado = $wpdb->update($tabela_completa, $dados_atualizados, array('id' => $id));

        if ($resultado !== false) {
            exibir_mensagem_vinculacao('sucesso', "Dado com ID $id atualizado com sucesso.");
        } else {
            exibir_mensagem_vinculacao('erro', "Erro ao atualizar o dado com ID $id.");
        }
    }
}
add_action('init', 'processar_atualizacao_dado');

// Função para processar a exclusão de dados
function processar_exclusao_dados() {
    if (isset($_POST['excluir_dado'])) {
        $tabela_selecionada = sanitize_text_field($_POST['tabela_selecionada']);
        $id_dado = sanitize_text_field($_POST['id_dado']);

        excluir_dado($tabela_selecionada, $id_dado);
    }
}
add_action('init', 'processar_exclusao_dados');

// Função para excluir dados da tabela
function excluir_dado($tabela, $id) {
    global $wpdb;
    $tabela_completa = $wpdb->prefix . $tabela;

    // Excluir o dado baseado no campo ID (ou outro campo único)
    $wpdb->delete($tabela_completa, array('id' => $id));

    exibir_mensagem_vinculacao('sucesso', "Dado com ID $id excluído com sucesso.");
}

?> 