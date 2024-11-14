<?php
function modal_atributos_comprar_html()
{
    ob_start(); ?>
    <!--Modal Atributos Comprar-->
    <div class="modal fade" id="modal_atributos_comprar" tabindex="-1" aria-labelledby="modal_atributos_comprar_label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_atributos_comprar_label">Comprar Atributos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="container" id="modal_atributos_comprar_div"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                const personagemID = getQueryStringParam("p");

                //Modal ATRIBUTOS COMPRAR
                $("#ficha_atributos_comprar").click(function() {
                    html = modalAtributosComprar();
                    $("#modal_atributos_comprar_div").html(html);
                });
                //Alterar Atributo - tag base
                $(document).on("click", ".edit_atb_base_mais", function() {
                    let atributo = $(this).data("atributo");
                    modalAtributosComprar_alterarValor(atributo, 1);
                });
                $(document).on("click", ".edit_atb_base_menos", function() {
                    let atributo = $(this).data("atributo");
                    modalAtributosComprar_alterarValor(atributo, -1);
                });

                function modalAtributosComprar() {
                    let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
                    if (typeof personagem.pontos_disponiveis !== "number") {
                        // Limpar atributos base
                        for (let atributo in personagem.atributos) {
                            substituirAtributo(atributo, "base", 0);
                        }
                        substituirCampoFicha("pontos_disponiveis", 10);
                        personagem = JSON.parse(localStorage.getItem(personagemID));
                    }
                    let html = `<p class="font-t20 text-center" style="font-size:1.4em">Pontos Disponíveis: <strong style="font-size:2.1em">${personagem.pontos_disponiveis}</strong></p>
                    <div class="row">`;
                    for (let atributo in personagem.atributos) {
                        let total = totalAtributo(atributo);
                        html += `
                <div class="col-6 col-lg-4 p-2">
                    <div class="bg-light p-3 g-0 gap-0">
                        <div class="row justify-content-between text-center">
                            <span class="font-t20 col-sm-4" style="font-size:2em;">${atributo.toUpperCase()}</span>
                            <span class="col-6 col-sm-4">Raça: <strong>${
                              personagem.atributos[atributo].raca || 0
                            }</strong></span>
                            <span class="col-6 col-sm-4">Total: <strong>${total}</strong></span>
                        </div>
                        <div id="atributo-detalhes-${atributo}">
                                <div class="row justify-content-center">
                                    <div class="input-group font-t20" >
                                      <button class="edit_atb_base_mais btn btn-sm btn-danger p-1" style="font-size:1.5em" data-atributo="${atributo}"><span class="game-icons--upgrade "></span></button>
                                      <input type="number" value="${
                                        personagem.atributos[atributo].base
                                      }" min="-1" max="4" class="font-t20 text-center form-control form-control-sm p-0" style="font-size:2.5em" disabled>
                                      <button class="edit_atb_base_menos btn btn-sm btn-danger p-1" style="font-size:1.5em" data-atributo="${atributo}"><span class="game-icons--upgrade" style="transform: rotate(180deg);"></span></button>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            `;
                    }
                    html += `</div>`;
                    return html;
                }

                function modalAtributosComprar_alterarValor(atributoNome, alteracao) {
                    const custoDict = {
                        "-1": -1,
                        0: 0,
                        1: 1,
                        2: 2,
                        3: 4,
                        4: 7
                    };
                    let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
                    let atributo = personagem.atributos[atributoNome];
                    let novoValor = parseInt(atributo.base) + alteracao;

                    if (novoValor >= -1 && novoValor <= 4) {
                        const custo = custoDict[novoValor] - custoDict[atributo.base];
                        if (personagem.pontos_disponiveis - custo >= 0) {
                            personagem.pontos_disponiveis -= custo;
                            substituirAtributo(atributoNome, "base", novoValor);
                            substituirCampoFicha("pontos_disponiveis", personagem.pontos_disponiveis);
                            //atualizar o html gerado pela função paginaComprarAtributos (sem utilizar swal, pois agora estou utilizando modal do bootstrap)
                            let html = modalAtributosComprar();
                            document.getElementById("modal_atributos_comprar_div").innerHTML =
                                html;
                        } else {
                            let html = modalAtributosComprar();
                            document.getElementById("modal_atributos_comprar_div").innerHTML =
                                html;
                        }
                    }
                }
            });
        })(jQuery);
    </script>


<?php
    return ob_get_clean();
}
