<?php
function ficha_personagem_html()
{
    ob_start(); ?>
    <!--Estilos tabela pericia-->
    <style>
        .tab_pericias_header {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/pericia_head.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        .tab_pericias_body {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/pericia_body.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        .tab_pericias_footer {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/pericia_footer.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        /*definir tamanhos expecificos para cada coluna da tabela de pericias */
        .tab_pericias_body tr td:nth-child(1) {
            width: 65%;
        }

        .tab_pericias_body tr td:nth-child(2) {
            width: 15%;
        }

        .tab_pericias_body tr td:nth-child(3) {
            width: 10%;
        }

        .tab_pericias_body tr td:nth-child(4) {
            width: 10%;
        }
    </style>
    <!--Inicio da Ficha-->
    <div class="div-pagina">
        <div class="container div-ficha p-3 rounded-3" id="ficha_de_personagem">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
                <!--Nome Personagem-->
                <div class="col-6 col-lg-3 order-2">
                    <label for="ficha_nome" class="form-text px-4">Personagem</label>
                    <div class="input-group mb-3">
                        <input type="text" id="ficha_nome" class="form-control font-t20 fs-4 lh-1" placeholder="Nome do Personagem">
                    </div>
                </div>
                <!--Jogador-->
                <div class="col-6 col-lg-3 order-3">
                    <label for="ficha_jogador" class="form-text px-4">Jogador</label>
                    <div class="input-group mb-3">
                        <input type="text" id="ficha_jogador" class="form-control" placeholder="Nome do jogador" disabled>
                        <button id="ficha_jogador_config" type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modal_jogador">
                            <i class="bi bi-gear-fill"></i>
                        </button>
                    </div>
                </div>
                <!--Tituloda Ficha-->
                <div class="col-lg-6 col-md-12 order-1 order-lg-3">
                    <div class="input-group bg-dark" style="border-radius:30px;">
                        <input type="text" id="ficha_titulo" class="form-control font-t20 lh-1 titulo_ficha" value="Tormenta 20" disabled>
                        <button id="botao_ficha_guiada" style="border-left:1px solid #aaa" class="btn btn-dark" type="button">
                            <span class="game-icons--cyborg-face fs-3 diamante"></span>
                        </button>
                    </div>
                </div>

            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="row g-3 justify-content-center">
                        <!--Raça-->
                        <div class="col-6">
                            <label for="ficha_raca" class="form-text px-4">Raça</label>
                            <div class="input-group mb-3">
                                <input type="text" id="ficha_raca" class="form-control" placeholder="Escolha sua raça" disabled>
                                <button class="ficha_raca_modal btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_raca">
                                    <i class="bi bi-gear-fill"></i>
                                </button>
                            </div>
                        </div>
                        <!--Origem-->
                        <div class="col-6">
                            <label for="ficha_origem" class="form-text px-4">Origem</label>
                            <div class="input-group mb-3">
                                <input type="text" id="ficha_origem" class="form-control" placeholder="Escolha sua Origem" disabled>
                                <button id="ficha_origem_config" class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_origem">
                                    <i class="bi bi-gear-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-0 justify-content-center">
                        <!--Classe-->
                        <div class="col-5">
                            <label for="ficha_classe" class="form-text px-4">Classe</label>
                            <div class="input-group mb-3">
                                <input type="text" id="ficha_classe" class="form-control" placeholder="Escolha sua classe" disabled>
                                <button id="ficha_classe_config" class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_classe">
                                    <i class="bi bi-gear-fill"></i>
                                </button>
                            </div>
                        </div>
                        <!--Nível-->
                        <div class="col-2 text-center">
                            <label for="ficha_nivel_config" class="form-text">Nível</label>
                            <div class="input-group justify-content-center">
                                <button id="ficha_nivel_config" class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_nivel">
                                    <span class="px-2 font-t20 lh-1" style="font-size: 1.5em;">1</span>
                                </button>
                            </div>
                        </div>
                        <!--Divindade-->
                        <div class="col-5">
                            <label for="ficha_divindade" class="form-text px-4">Divindade</label>
                            <div class="input-group mb-3">
                                <input type="text" id="ficha_divindade" class="form-control" placeholder="Escolha sua divindade" disabled>
                                <button id="ficha_divindade_config" class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_divindade">
                                    <i class="bi bi-gear-fill"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 row align-content-center">
                    <?php echo atributos_html('ficha', 'true'); ?>
                </div>
                <!--Vida e Mana-->
                <div class="col-lg-5">
                    <div class="ficha_vida_mana" style="display: flex;">
                        <div class="text-center font-t20 mt-2" style="width:190px;">
                            <h1 style="height: 30px;">0</h1>
                            <span style="height: 5px;">Vida</span>
                            <h1 style="height: 30px;">0</h1>
                            <span style="height: 5px;">Mana</span>
                        </div>
                        <div class="text-center row" style="width:100%;">
                            <div class="col-4 p-2 mt-2">asd</div>
                            <div class="col-4 p-2 mt-2">asd</div>
                            <div class="col-4 p-2 mt-2">asd</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 p-3">
                    <!--Habilidades-->
                    <div id="ficha_habilidades" class="container">
                        <div class="row justify-content-center ficha_habilidades p-4">
                            <div>
                                <h3 class="font-t20 text-center">Habilidades</h3>
                            </div>
                            <div id="ficha_habilidades_lista" class="row"></div>
                        </div>
                    </div>
                </div>
                <!--Perícias-->
                <div class="col-lg-6 p-3">
                    <table id="tabela_pericias">
                        <thead class="tab_pericias_header">
                            <tr class="font-t20 p-3">
                                <th class="p-4">Perícias</th>
                                <th>Total</th>
                                <th>Atb</th>
                                <th>Treino</th>
                            </tr>
                        </thead>
                        <tbody id="ficha_pericias" class="tab_pericias_body">
                        </tbody>
                        <tfoot class="tab_pericias_footer">
                            <tr>
                                <th colspan="4" class="text-center"><i class="ra ra-cracked-shield ra-lg"></i> = Penalidade de Armadura</th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-center"><i class='ra ra-archery-target ra-lg'></i> = Precisa ser Treinado</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Modais-->

    <!--Modal Ficha-Guiada-->
    <?php echo modais_ficha_guiada_html(); ?>

    <!--Modal Raça-->
    <?php echo modal_raca_html(); ?>

    <!--Modal Atributos (shortcode php)-->
    <?php echo modal_atributos_html(); ?>

    <!--Modal Atributos Comprar-->
    <?php echo modal_atributos_comprar_html(); ?>

    <!--Modal Atributos Rolar-->
    <?php echo modal_atributos_rolar_html(); ?>

    <script>
        (function($) {
            $(document).ready(function() {

                if (!personagemID) {
                    window.location.href = "/personagens";
                    localStorage.setItem(
                        "notificacao",
                        JSON.stringify({
                            tipo: "error",
                            mensagem: `É necessário escolher um personagem para abrir a ficha.`,
                        })
                    );
                }

                const tablela_pericias = jQuery("#tabela_pericias").DataTable();

                function periciasTable() {
                    const periciasDados = JSON.parse(localStorage.getItem("pericias")) || [];
                    tablela_pericias.clear().draw();
                    periciasDados.forEach((pericia) => {
                        let nome = pericia.nome;
                        if (pericia.treinado) {
                            nome += ` <i style="font-size:0.8em" class='ra ra-archery-target ra-lg'></i>`;
                        }
                        if (pericia.penalidade) {
                            nome += ` <i style="font-size:0.8em" class='ra ra-cracked-shield ra-lg'></i>`;
                        }
                        tablela_pericias.row
                            .add([
                                `<span style="margin-left:30px">${nome}</span>` || "N/A",
                                `<span pericia="${
                pericia.nome
              }" class="pericia_total font-t20 fs-4">${totalPericia(pericia.nome)}</span>` || 0,
                                pericia.atributo || "N/A",
                                `<input class="form-check-input" type="checkbox" value="" aria-label="Perícia Treinada" pericia="${pericia.nome}">`,
                            ])
                            .draw();
                    });
                }
                periciasTable();

                const carregarFichaPersonagem = async () => {
                    const personagem = JSON.parse(localStorage.getItem(personagemID));
                    if (personagem) {
                        document.getElementById("ficha_nome").value = personagem.nome;
                        document.getElementById("ficha_jogador").value = personagem.jogador;
                        const atributos = ["for", "des", "con", "int", "sab", "car"];
                        for (const atb of atributos) {
                            document.getElementById(`ficha_${atb}`).value = totalAtributo(atb);
                        }
                        if (personagem.raca?.nome) {
                            document.getElementById("ficha_raca").value = personagem.raca.nome;
                        }
                        exibirHabilidades();
                    }
                };
                carregarFichaPersonagem();

                // Atualizar nome do personagem em tempo real
                document.querySelector("#ficha_nome").addEventListener("input", (event) => {
                    substituirCampoFicha("nome", event.target.value);
                });


                // ATRIBUTOS

                // Alterar Atributo - tag jogador
                $(".ficha_atb_mais").on("click", function() {
                    let atributo = $(this).data("atributo");
                    atualizarAtributo(atributo, "jogador", 1);
                });
                $(".ficha_atb_menos").on("click", function() {
                    let atributo = $(this).data("atributo");
                    atualizarAtributo(atributo, "jogador", -1);
                });

                $(".nao-fechar-modal").click(function(event) {
                    event.preventDefault(); // Previne comportamento padrão

                    // Resgatar o valor do atributo data-modal
                    let modal = $(this).data("modal");

                    // Criar uma nova instância do modal e exibi-lo
                    const modalB = new bootstrap.Modal(document.getElementById(modal));
                    modalB.show();
                });


            });
        })(jQuery);
    </script>

<?php
    return ob_get_clean();
}
add_shortcode('ficha_personagem', 'ficha_personagem_html');
