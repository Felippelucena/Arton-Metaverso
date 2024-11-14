<?php
function modal_atributos_rolar_html()
{
    ob_start(); ?>
    <!--Modal Atributos Rolar-->
    <div class="modal fade" id="modal_atributos_rolar" tabindex="-1" aria-labelledby="modal_atributos_rolar_label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modal_atributos_rolar_label">Rolar Atributos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-body">
                    <div class="container" id="modal_atributos_rolar_div"></div>
                </div>
                <div class="modal-footer">
                    <button id="salvar_atributo_rolados" type="button" class="btn btn-primary" data-bs-dismiss="modal">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                const personagemID = getQueryStringParam("p");

                //Modal ATRIBUTOS ROLAR
                $("#ficha_atributos_rolar").click(function() {
                    let personagem = JSON.parse(localStorage.getItem(personagemID));
                    let valoresRolados = [];
                    if (typeof personagem.pontos_disponiveis !== "object") {
                        let index = 0;
                        let timerInterval;

                        for (let atributo in personagem.atributos) {
                            substituirAtributo(atributo, "base", 0);
                        }
                        valoresRolados = rolarAtributos();
                        substituirCampoFicha("pontos_disponiveis", valoresRolados);

                        // Configurando o SweetAlert
                        Swal.fire({
                            title: "<span class='font-t20'>Rolando os Dados</span>",
                            html: `
              <div id="valores-rolados-container" class="row g-2 justify-content-center">
                ${valoresRolados
                  .map(
                    (valor, i) => `
                  <div class="col-4 col-sm-2">
                    <div class="text-center p-2 border rounded font-t20" style="font-size: 2em;">
                      <span id="valor-${i}" style="opacity: 0;">${valor}</span>
                    </div>
                  </div>
                `
                  )
                  .join("")}
              </div>
            `,
                            timer: 4000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                timerInterval = setInterval(() => {
                                    if (index < valoresRolados.length) {
                                        const spanElement = document.getElementById(`valor-${index}`);
                                        spanElement.style.opacity = "1";
                                        index++;
                                    } else {
                                        clearInterval(timerInterval);
                                    }
                                }, 500);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                                let html = modalAtributosRolar();
                                $("#modal_atributos_rolar_div").html(html);
                                $("#modal_atributos_rolar").modal("show");
                            },
                        });
                    } else {
                        let html = modalAtributosRolar();
                        $("#modal_atributos_rolar_div").html(html);
                    }
                });

                // Adiciona evento de mudança aos selects
                document.addEventListener("change", function(event) {
                    if (event.target.classList.contains("select-rolar-atributo")) {
                        modalAtributosRolar_atualizarSelects();
                    }
                });

                // Rolar novamente
                document.addEventListener("click", function(event) {
                    if (event.target && event.target.id === "btn-rolar-atributos") {
                        substituirCampoFicha("pontos_disponiveis", "rolar");
                        // simular click no botão #ficha_atributos_rolar
                        document.getElementById("ficha_atributos_rolar").click();
                    }
                });

                function modalAtributosRolar() {
                    let personagem = JSON.parse(localStorage.getItem(personagemID));
                    let atributos = ["for", "des", "con", "int", "sab", "car"];
                    valoresRolados = personagem.pontos_disponiveis;

                    let html = `
          <div class="container text-center p-3 bg-body-tertiary">
          <h5>Escolha um atributo para cada valor:</h5>
          <button id="btn-rolar-atributos" class="btn btn-danger" style="margin:10px">Rolar Novamente</button>
          <p style="font-size:1.5em"> Valores Rolados: <strong>${valoresRolados}</strong></p>
          </div>
          <div class="container row g-2 p-2">
          `;
                    valoresRolados.forEach((valor, index) => {
                        html += `
                      <div class="col-sm-6 col-md-4">
                      <div class="font-t20 input-group">
                          <select class="select-rolar-atributo form-select" data-atributo="${index}" id="select-atributo-${index}" style="font-size:1.5em;">
                              <option value="">Escolher</option>`;
                        atributos.forEach((atributo) => {
                            html += `<option value="${atributo}">${atributo.toUpperCase()}</option>`;
                        });
                        html += `</select>
                  <label class="input-group-text">
                      <strong class="col-6" style="font-size:2em;" class="valor-rolado">= ${valor}</strong>
                  </label>
              </div></div>`;
                    });
                    html += `</div>`;
                    return html;
                }

                function modalAtributosRolar_atualizarSelects() {
                    let selects = document.querySelectorAll(".select-rolar-atributo");
                    let selecionados = Array.from(selects)
                        .map((select) => select.value)
                        .filter((value) => value);

                    selects.forEach((select) => {
                        let atributoSelecionado = select.value;
                        select.innerHTML = '<option value="">Escolher</option>';
                        ["for", "des", "con", "int", "sab", "car"].forEach((atributo) => {
                            if (
                                !selecionados.includes(atributo) ||
                                atributo === atributoSelecionado
                            ) {
                                select.innerHTML += `<option value="${atributo}">${atributo.toUpperCase()}</option>`;
                            }
                        });
                        select.value = atributoSelecionado;
                    });
                }

                //Salvar valores rolados
                $("#salvar_atributo_rolados").click(function() {
                    let todosSelecionados = true;
                    $(".select-rolar-atributo").each(function() {
                        if (!$(this).val()) {
                            todosSelecionados = false;
                        }
                    });
                    if (todosSelecionados) {
                        let personagem = JSON.parse(localStorage.getItem(personagemID));
                        let index = 0;
                        $(".select-rolar-atributo").each(function() {
                            let atributo = $(this).val();
                            if (atributo) {
                                let valorRolado = personagem.pontos_disponiveis[index];
                                substituirAtributo(atributo, "base", parseInt(valorRolado));
                            }
                            index += 1;
                        });
                        notify("success", "Atributos salvos com sucesso!");
                    } else {
                        notify(
                            "info",
                            "Por favor, selecione um atributo para cada valor rolado."
                        );
                    }
                });

            });
        })(jQuery);
    </script>
<?php
    return ob_get_clean();
}
