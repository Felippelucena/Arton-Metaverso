<?php
function modal_atributos_html()
{
    ob_start(); ?>
    <!--Modal Atributos-->
    <div class="modal fade" id="modal_atributos" tabindex="-1" aria-labelledby="modal_atributos_label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_atributos_label">Configurar Atributos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="container text-center p-5 bg-body-tertiary">
                        <h5>Como deseja definir seus atributos?</h5>
                        <p style='font-size:0.8em;color:red'>Cuidado! Escolher um método reseta as configurações do outro.</p>
                        <div class="row justify-content-center gap-3">
                            <button id="ficha_atributos_comprar" class="col-4 btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_atributos_comprar">
                                Comprar
                            </button>
                            <button id="ficha_atributos_rolar" class="col-4 btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_atributos_rolar">
                                Rolar
                            </button>
                        </div>
                    </div>
                    <div class="text-center p-3">
                        <h4>Atributos Atuais:</h4>
                        <div class="container row justify-content-center p-3" id="modal_atributos_div"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                const personagemID = getQueryStringParam("p");
                //Modal ATRIBUTOS
                $("#ficha_atributos_config").click(function() {
                    html = modalAtributos();
                    $("#modal_atributos_div").html(html);
                });

                function modalAtributos() {
                    let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
                    let html_atributos_atuais = "";
                    if (personagem.atributos && typeof personagem.atributos === "object") {
                        for (let atributo in personagem.atributos) {
                            if (personagem.atributos.hasOwnProperty(atributo)) {
                                let total = totalAtributo(atributo);
                                html_atributos_atuais += `
                      <div class="col-6 col-sm-4 col-lg-2 ">
                          <div class="bg-body-secondary m-1">
                              <div class="text-center" style="border-bottom:2px solid white">
                                  <span style="font-family:Tormenta20;font-size:1.3em;">${atributo.toUpperCase()}: </span>
                                  <span><strong>${total}</strong></span>
                              </div>
                              <div id="atributo-detalhes-${atributo}" class="atributo-detalhes p-2">
                  `;
                                for (let tag in personagem.atributos[atributo]) {
                                    if (personagem.atributos[atributo].hasOwnProperty(tag)) {
                                        html_atributos_atuais += `
                              <span> ${tag}: ${personagem.atributos[atributo][tag]}</span><br>
                          `;
                                    }
                                }
                                html_atributos_atuais += `</div></div></div>`;
                            }
                        }
                    } else {
                        html_atributos_atuais = "<p>Personagem sem atributos definidos.</p>";
                    }
                    return html_atributos_atuais;
                }
            });
        })(jQuery);
    </script>
<?php
    return ob_get_clean();
}