<?php

function atributos_html($nome, $edicao)
{
    ob_start(); ?>
    <style>
        .div_atributo {
            width: 100px;
            height: 110px;
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/atributos.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        .input_number_ficha {
            padding: 0 !important;
            background: none;
            border: none;
            text-align: center;
            cursor: pointer;
        }

        .input_number_ficha:focus {
            outline: none;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <!--Atributos-->
    <div id="<?php echo $nome; ?>_atributos" class="row p-0 gap-2 justify-content-center align-items-start">
        <?php if ($edicao == "true") : ?>
            <button id="<?php echo $nome; ?>_atributos_config" type="button" class="btn btn-danger lh-1" style="border-radius:20px; margin-right:-35px;z-index:10; width:30px; padding:6px" data-bs-toggle="modal" data-bs-target="#modal_atributos"><i class="bi bi-gear-fill"></i></button>
        <?php endif; ?>
        <!--Template Atributo-->
        <template id="<?php echo $nome; ?>_atributo-template">
            <div class="pt-2 div_atributo text-center justify-content-center">
                <h5 class="font-t20 m-0"></h5>
                <input type="number" class="input_number_ficha font-t20 w-75 lh-1 rolar_atb_ficha" style="font-size: 2.6em;" data-atributo="" value="0" placeholder="Escolha sua classe" readonly>
                <?php if ($edicao == "true") : ?>
                    <div>
                        <button class="ficha_atb_mais lh-1 btn btn-sm btn-danger p-1"><span class="game-icons--upgrade"></span></button>
                        <button class="ficha_atb_menos lh-1 btn btn-sm btn-danger p-1"><span class="game-icons--upgrade" style="transform: rotate(180deg);"></span></button>
                    </div>
                <?php endif; ?>
            </div>
        </template>
    </div>
    <script>
        function atributosTemplate() {
            const atributos = ["FOR", "DES", "CON", "INT", "SAB", "CAR"];
            const ficha_atributos = document.getElementById("<?php echo $nome; ?>_atributos");
            const template = document.getElementById("<?php echo $nome; ?>_atributo-template");
            atributos.forEach((atributo) => {
                const clone = template.content.cloneNode(true);

                // Definir o nome do atributo e o data-atributo
                clone.querySelector("h5").textContent = atributo;
                clone.querySelector("input").dataset.atributo = atributo.toLowerCase();
                clone.querySelector("input").id = `ficha_${atributo.toLowerCase()}`;
                const buttons = clone.querySelectorAll("button");
                buttons.forEach((button) => {
                    button.dataset.atributo = atributo.toLowerCase();
                });
                ficha_atributos.appendChild(clone);
            });
        }
        atributosTemplate();

        jQuery(document).ready(function($) {
            //ROLAR ATRIBUTOS
            $(".rolar_atb_ficha").on("click", function() {
                let atributo = $(this).data("atributo");
                let valor_atb = totalAtributo(atributo);
                let valor_dado = rolarDado().soma;
                let color = "black";
                if (valor_dado < 10) {
                    color = "red";
                } else if (valor_dado < 16) {
                    color = "#DB9D00";
                } else if (valor_dado < 19) {
                    color = "green";
                } else {
                    color = "blue";
                }
                Swal.fire({
                    title: `Teste de ${atributo.toUpperCase()}`,
                    html: `
                        <style>
                            .swal2-popup {
                                background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/Ativo-12@4x-8.png');
                                background-size: 100% 100%;
                                background-repeat: no-repeat;
                            }
                        </style>
                        <div>
                            <strong style="font-size:3.5em;font-family:Tormenta20;color:${color}">${
                            valor_atb + valor_dado
                        }</strong>
                            <p>
                            <span class="m-2 bg-light p-2">d20=<strong>${valor_dado}</strong></span>
                            <span class="m-2 bg-light p-2">atb=<strong>${valor_atb}</strong></span>
                            </p>
                        </div>
                        `,
                    confirmButtonText: "Fechar",
                });
            });
        });
    </script>
<?php
    return ob_get_clean();
}
