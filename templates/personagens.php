<?php
function pagina_personagens_html()
{
    ob_start(); ?>
    <style>
        .div_personagens {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/11/rpg_arte_abstrata.jpeg');
            background-size: cover;
            background-position: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6);
            background-blend-mode: overlay;
        }
        .div_tabela {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.8);
        }
    </style>
    <div class="div_personagens align-content-center" style="min-height: 100vh;">
        <div class="container">

            <!-- Envolva a tabela com um contêiner table-responsive -->
            <div class="table-responsive div_tabela">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal_criar_personagem">
                    Criar Personagem
                </button>
                <table id="lista_personagens" class="display table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Atributos</th>
                            <th>Raça</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Linhas de personagens serão preenchidas pelo JavaScript -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Nome</th>
                            <th>Atributos</th>
                            <th>Raça</th>
                            <th>Ações</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal_criar_personagem" tabindex="-1" aria-labelledby="modal_criar_personagemLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_criar_personagemLabel">Criar Personagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <input type="text" id="nome_novo_personagem" class="form-control" placeholder="Nome do jogador">
                        <button id="criar_novo_personagem" type="button" class="btn btn-danger">
                            <i class="bi bi-person-add"></i> Criar
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function() {
            // Inicia o DataTable
            const table = jQuery('#lista_personagens').DataTable({
                info: false,
                paging: false,
                searching: false
            });

            // Função para carregar personagens do localStorage e exibir na tabela
            function carregarPersonagens() {
                table.clear().draw();
                for (let i = 0; i < localStorage.length; i++) {
                    const chave = localStorage.key(i);
                    if (chave.startsWith("perso_")) {
                        const personagem = JSON.parse(localStorage.getItem(chave));
                        let atributos = "";
                        for (let atributo in personagem.atributos) {
                            let soma = 0;
                            for (let tag in personagem.atributos[atributo]) {
                                soma += Number(personagem.atributos[atributo][tag]) || 0;
                            }
                            atributos += `${atributo}: ${soma} `;
                        }
                        table.row.add([
                            personagem.nome || "N/A",
                            atributos || "N/A",
                            personagem.raca.nome || "N/A",
                            `<button class="btn btn-sm btn-warning" onclick="atualizarPersonagem('${chave}')">Editar</button>
                            <button class="btn btn-sm btn-secondary" onclick="excluirPersonagem('${chave}')">Excluir</button>`
                        ]).draw();
                    }
                }
            }

            // Chama a função para carregar personagens ao carregar a página
            carregarPersonagens();

            // Adiciona evento para criar novo personagem
            document.querySelector("#criar_novo_personagem").addEventListener("click", () => {
                const nome = document.querySelector("#nome_novo_personagem").value;
                if (!nome) {
                    alert("Por favor, insira um nome para o personagem.");
                    return;
                }
                const personagem = new Personagem(nome);
                personagem.jogador = tmData.userData.display_name;
                const pericias = JSON.parse(localStorage.getItem("pericias"));
                for (let pericia in pericias) {
                    personagem.pericias[pericias[pericia].nome] = {
                        atributo: pericias[pericia].atributo,
                        modificadores: {},
                        treino: false
                    };
                }

                const chaveAleatoria = `${Date.now()}-${Math.random()}`;
                const nomeStorage = `perso_${chaveAleatoria}`;
                localStorage.setItem(nomeStorage, JSON.stringify(personagem));
                localStorage.setItem("notificacao", JSON.stringify({
                    tipo: "success",
                    mensagem: `${nome} criado com sucesso!`
                }));
                window.location.href = `/ficha-personagem/?p=${nomeStorage}`;
            });

            // Função para atualizar personagem
            window.atualizarPersonagem = function(chave) {
                window.location.href = `/ficha-personagem/?p=${chave}`;
            };

            // Função para excluir personagem
            window.excluirPersonagem = function(chave) {
                if (confirm("Tem certeza de que deseja excluir este personagem?")) {
                    localStorage.removeItem(chave);
                    carregarPersonagens(); // Atualiza a lista
                }
            };
        });
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('pagina_personagens', 'pagina_personagens_html');
