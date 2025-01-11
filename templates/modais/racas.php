<?php
function modal_raca_html()
{
    ob_start(); ?>
        <style>
        .raca-descricao-container {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/4bdb9dd7-d6e1-4c24-af73-bec31efd103f.jpeg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
    </style>
    <div class="modal fade" id="modal_raca" tabindex="-1" aria-labelledby="modal_raca_label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_raca_label">Configurar Raça</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="container col-md-5">
                            <div class="raca-descricao-container p-0 justify-content-end">
                                <div class="p-2 font-t20 fs-5" style="background-color:#000a; color:white">
                                    <span id="raca-nome" class="fs-1">Nome da Raça</span><span id="raca-atributos"></span>
                                </div>
                            </div>
                            <div class="input-group my-3">
                                <span class="input-group-text">Procurar Raça:</span>
                                <select id="modal-select-racas" class="form-select" aria-label="Default select example">
                                    <option value="">Escolher...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div>
                                <div class="accordion" id="raca-descricao-accordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Descrição</button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#raca-descricao-accordion">
                                            <div id="raca-descricao" class="p-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-t20 text-center mt-3">Habilidades</h4>
                                <div class="accordion" id="raca-habilidades"></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <p style='font-size:0.8em;color:red'>Cuidado! Escolher uma raça deleta as configurações da outra!</p>
                    <button type="button" class="btn btn-lg btn-danger" id="salvar_raca" data-bs-dismiss="modal">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                const personagemID = getQueryStringParam("p");

                $(document).on("click", ".ficha_raca_modal", function() {
                    modalRacaPreencherSelect();
                });

                // Exibir informações da raça ao trocar seleção
                $(document).on("change", "#modal-select-racas", function() {
                    let raca_escolhida = $(this).val();
                    modalRacaExibirInfo(raca_escolhida);
                });

                // Função para preencher o select com as raças
                function modalRacaPreencherSelect() {
                    let racas = JSON.parse(localStorage.getItem("racas"));
                    let personagem = JSON.parse(localStorage.getItem(personagemID));
                    if (racas && Array.isArray(racas)) {
                        let selectRacas = $("#modal-select-racas");
                        selectRacas.empty();
                        selectRacas.append('<option value="">Escolher</option>');
                        racas.forEach(function(raca) {
                            selectRacas.append(
                                `<option value="${raca.nome}">${raca.nome}</option>`
                            );
                        });
                        if (personagem.raca.nome) {
                            modalRacaExibirInfo(personagem.raca.nome);
                        }
                    }
                }

                // Função para exibir informações da raça
                function modalRacaExibirInfo(raca_escolhida) {
                    let racas = JSON.parse(localStorage.getItem("racas")) || [];
                    let habilidades_racas =
                        JSON.parse(localStorage.getItem("habilidades_raca")) || [];

                    let raca_info = racas.find((r) => r.nome === raca_escolhida);

                    if (raca_info) {
                        // Atualizar nome da raça
                        $("#raca-nome").text(raca_info.nome);

                        // Atualizar atributos e descrição
                        if (
                            raca_info.atributos &&
                            raca_info.atributos !== null &&
                            raca_info.atributos !== undefined
                        ) {
                            let atributos = JSON.parse(raca_info.atributos.replace(/\\'/g, '"'));
                            let atributosList = $("#raca-atributos");
                            atributosList.empty();
                            for (let atributo in atributos) {
                                atributosList.append(`
                  <span>${atributo.toUpperCase()}: <strong>${
                atributos[atributo]
              }</strong></span>
                  `);
                            }
                        } else {
                            $("#raca-atributos").text(
                                "Esta raça não possui atributos pré-definidos!"
                            );
                        }
                        $("#raca-descricao").text(raca_info.descricao);

                        $(".raca-descricao-container").css(
                            "background-image",
                            `url('https://arton.felippelucena.com/wp-content/uploads/2024/10/${raca_info.imagem}.webp')`
                        );

                        // Atualizar habilidades
                        let habilidades = habilidades_racas.filter(
                            (h) => h.raca === raca_info.id
                        );
                        let habilidadesList = $("#raca-habilidades");
                        const collapse_target = [
                            "collapseTwo",
                            "collapseThree",
                            "collapseFour",
                            "collapseFive",
                            "collapseSix",
                            "collapseSeven",
                            "collapseEight",
                            "collapseNine",
                            "collapseTen",
                        ];
                        let cont = 0;
                        habilidadesList.empty();
                        habilidades.forEach((habilidade) => {
                            habilidadesList.append(`
                <div class="habilidade-item accordion-item">
                  <h2 class="accordion-header">
                    <button class="accordion-button habilidade-nome" type="button" data-bs-toggle="collapse" data-bs-target="#${collapse_target[cont]}" aria-expanded="false" aria-controls="${collapse_target[cont]}">${habilidade.nome}</button>
                  </h2>
                  <div id="${collapse_target[cont]}" class="habilidade-descricao accordion-collapse collapse" data-bs-parent="#raca-habilidades">
                  <div class="accordion-body">${habilidade.descricao}</div>
                  </div>
                </div>
              `);
                            cont += 1;
                        });
                    } else {
                        $("#raca-nome").text("");
                        $("#raca-atributos").text("");
                        $("#raca-descricao").text("");
                        $("#raca-habilidades").empty();
                    }
                }
                $(document).on("click", "#salvar_raca", function() {
                    let raca_escolhida = $("#modal-select-racas").val();
                    if (!raca_escolhida) {
                        notify("error", "Por favor, escolha uma raça");
                        return false;
                    }
                    modalRacaAtualizarPersonagem(raca_escolhida);
                });

                // Função para atualizar o personagem com a raça escolhida
                function modalRacaAtualizarPersonagem(raca_escolhida) {
                    let racas = JSON.parse(localStorage.getItem("racas")) || [];
                    let habilidades_racas =
                        JSON.parse(localStorage.getItem("habilidades_raca")) || [];
                    let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};

                    // Resetar atributos da raça anterior
                    for (let atributo in personagem.atributos) {
                        if (personagem.atributos[atributo].raca) {
                            substituirAtributo(atributo, "raca", 0);
                        }
                    }

                    //Reverter shortcodes da raça anterior
                    for (let habilidade in personagem.raca.habilidades) {
                        reverterShortCodes(personagemID, personagem.raca.habilidades[habilidade].shortcode);
                    }

                    // Adicionar atributos e habilidades de raça ao personagem
                    let raca_info = racas.find((r) => r.nome === raca_escolhida);

                    if (raca_info) {
                        let habilidades = {};
                        habilidades_racas.forEach(function(habilidade) {
                            if (habilidade.raca === raca_info.id) {
                                let shortcode = iniciarShortCodes(personagemID, habilidade.shortcode);
                                habilidades[habilidade.nome] = {
                                    descricao: habilidade.descricao,
                                    shortcode: shortcode,
                                };
                            }
                        });

                        substituirCampoFicha("raca", {
                            nome: raca_info.nome,
                            descricao: raca_info.descricao,
                            habilidades: habilidades,
                        });
                        $("#ficha_raca").val(raca_info.nome);
                        exibirHabilidades();
                        if (
                            raca_info.atributos !== "" &&
                            raca_info.atributos !== undefined &&
                            raca_info.atributos !== null
                        ) {
                            let atributos = JSON.parse(raca_info.atributos.replace(/\\'/g, '"'));
                            for (let atributo in atributos) {
                                substituirAtributo(atributo, "raca", atributos[atributo]);
                            }
                        }
                        notify("success", "Raça atualizada com sucesso!");
                    }
                }
            });
        })(jQuery);
    </script>
<?php
    return ob_get_clean();
}
