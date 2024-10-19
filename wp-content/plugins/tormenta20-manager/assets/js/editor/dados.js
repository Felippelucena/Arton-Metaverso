        function obterTodosDados($, tabelas) {
            let dadosCarregados = {};
            let faltamDados = false;
        
            // Verificar se os dados estão no localStorage
            tabelas.forEach(tabela => {
                const dado = localStorage.getItem(tabela);
                if (dado) {
                    dadosCarregados[tabela] = JSON.parse(dado);
                } else {
                    faltamDados = true;
                }
            });
            // Se todos os dados estiverem no localStorage, retorne
            if (!faltamDados) {
                return dadosCarregados; // Pode retornar os dados diretamente se não faltar nada
            }
        
            // Se faltar algum dado, faz a requisição AJAX
            $.ajax({
                url: tmData.ajaxurl, // Usando a variável localizada
                method: 'POST',
                data: {
                    action: 'tm_obter_todos_dados',
                    tabelas: tabelas // Enviar o array de tabelas para o servidor
                },
                success: function(response) {
                    tabelas.forEach(tabela => {
                        if (response[tabela]) {
                            localStorage.setItem(tabela, JSON.stringify(response[tabela]));
                        }
                    });
                    console.log("Dados salvos no localStorage:", response);
                },
                error: function(error) {
                    console.log("Erro ao obter dados:", error);
                }
            });
        }