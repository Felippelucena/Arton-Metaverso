<?php
function ficha_personagem_html()
{
    ob_start(); ?>

    <style>
        .div-pagina {
            background: url('https://arton.felippelucena.com/wp-content/uploads/2024/10/929a8137-0318-4200-a619-8efcfa64a826.jpeg');
            background-size: cover;
            background-attachment: fixed;
            background-position: bottom;
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
                <div class="col-lg-6 col-md-12 order-1 order-lg-3 text-center align-content-center" style="display:flex">
                    <h1 class="font-t20 titulo_ficha" style="display:flex;">Tormenta 20</h1>
                    <button id="botao_ficha_guiada" class="btn">
                            <span class="game-icons--cyborg-face fs-3"></span>
                        </button>
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
                    <?php echo atributos_html('ficha', 'true'); ?>
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

<?php
    return ob_get_clean();
}
add_shortcode('ficha_personagem', 'ficha_personagem_html');
