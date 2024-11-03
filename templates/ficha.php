<?php
function ficha_personagem_html()
{
    ob_start(); ?>

    <style>
        .div-pagina {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/8960a9d3-9d9b-4749-90d7-d8e4b0588a75.jpeg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .div-ficha {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            width: 100%;
            max-width: 1200px;
        }

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

        .ficha_habilidades {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/Ativo-6@4x-8.png');
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }

        .titulo_ficha {
           color: white;
           font-size: 4em;
           /*sombra com animação do mouse*/
              text-shadow: 0 0 10px #f00, 0 0 20px #f35, 0 0 30px #f53, 0 0 40px #fa4787, 0 0 70px #f400de, 0 0 80px #f36874;
                animation: animate 120s linear infinite;
        }

        @keyframes animate {
            0% {
                filter: hue-rotate(0deg);
            }

            100% {
                filter: hue-rotate(360deg);
            }
        }
           
        </style>

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

    <!--Estilos Modal Raça-->
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
                <div class="col-lg-6 col-md-12 order-1 order-lg-3 text-center align-content-center">
                        <h1 class="font-t20 titulo_ficha">Tormenta 20</h1>
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
                                <button id="ficha_raca_modal" class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_raca">
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
                <div>
                    <!--Atributos-->
                    <div id="ficha_atributos" class="row p-0 gap-2 justify-content-center align-items-start">
                        <button id="ficha_atributos_config" type="button" class="btn btn-danger lh-1" style="border-radius:20px; margin-right:-35px;z-index:10; width:30px; padding:6px" data-bs-toggle="modal" data-bs-target="#modal_atributos"><i class="bi bi-gear-fill"></i></button>
                        <!--Template Atributo-->
                        <template id="atributo-template">
                            <div class="pt-2 div_atributo text-center justify-content-center">
                                <h5 class="font-t20 m-0"></h5>
                                <input type="number" class="input_number_ficha font-t20 w-75 lh-1 rolar_atb_ficha" style="font-size: 2.6em;" data-atributo="" value="0" placeholder="Escolha sua classe" readonly>
                                <div>
                                    <button class="ficha_atb_mais lh-1 btn btn-sm btn-danger p-1"><span class="game-icons--upgrade"></span></button>
                                    <button class="ficha_atb_menos lh-1 btn btn-sm btn-danger p-1"><span class="game-icons--upgrade" style="transform: rotate(180deg);"></span></button>
                                </div>
                            </div>
                        </template>
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
    <!--Configurar Jogador-->
    <div class="modal fade" id="modal_jogador" tabindex="-1" aria-labelledby="modalJogadorLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalJogadorLabel">Configurar Jogador</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Em breve...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Configurar Raça-->
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

<?php
    return ob_get_clean();
}
add_shortcode('ficha_personagem', 'ficha_personagem_html');
