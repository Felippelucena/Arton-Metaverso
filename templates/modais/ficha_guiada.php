<?php
function modais_ficha_guiada_html()
{
    ob_start(); ?>
    <style>
        .balao-dialogo {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            opacity: 0;
            /* Inicialmente invisível */
            transform: translateY(10px);
            /* Pequeno deslocamento para baixo */
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .balao-dialogo.mostrar {
            opacity: 1;
            /* Torna o parágrafo visível */
            transform: translateY(0);
            /* Remove o deslocamento */
        }
    </style>
    <!--Modal Step 0 abre quando a pagina abre-->
    <div class="modal fade" id="modalFichaGuiada0" tabindex="-1" aria-labelledby="modalFichaGuiada0Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada0Label">Bem Vind@ a Arton <strong id="ficha_guiada_nome" class="font-t20 fs-3"></strong></h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-9 balao-dialogo offset-1">
                                Olá aventureir@! Meu nome é <strong>Grog</strong>, sou de Deheon e estou aqui para te ajudar a criar seu personagem.</p>
                            </div>
                            <div class="col-9 balao-dialogo offset-1">
                                <p>Para entrar neste mundo de aventuras, você precisa de uma <strong>ficha de personagem.</strong></p>
                            </div>
                            <div class="col-9 balao-dialogo offset-2">
                                <p>Você pode seguir com minha ajuda <strong>neste guia</strong> clicando em continuar ou fechar e preencher <strong>manualmente</strong>.</p>
                            </div>
                            <div class="col-9 balao-dialogo offset-1">
                                <p>Se fechar pode abrir novamente a qualquer momento no botão <span class="game-icons--cyborg-face"></span>!</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Modal Footer-->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Step 1 abre quando a pagina abre-->
    <div class="modal fade" id="modalFichaGuiada1" tabindex="-1" aria-labelledby="modalFichaGuiada1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada1Label">Definindo os Atributos</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 balao-dialogo offset-2">
                                <p>Ótimo, vamos começar!</p>
                            </div>
                            <div class="col-9 balao-dialogo offset-1">
                                <p>Primeiro, preciso de mais detalhes sobre seus atributos, abaixo você consegue ver cada atributo e como afeta seu personagem em jogo.</p>
                            </div>
                            <div class="balao-dialogo">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="forca-tab" data-bs-toggle="tab" data-bs-target="#forca" type="button" role="tab" aria-controls="forca" aria-selected="true">Força</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="destreza-tab" data-bs-toggle="tab" data-bs-target="#destreza" type="button" role="tab" aria-controls="destreza" aria-selected="false">Destreza</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="constituicao-tab" data-bs-toggle="tab" data-bs-target="#constituicao" type="button" role="tab" aria-controls="constituicao" aria-selected="false">Constituição</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="inteligencia-tab" data-bs-toggle="tab" data-bs-target="#inteligencia" type="button" role="tab" aria-controls="inteligencia" aria-selected="false">Inteligência</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="sabedoria-tab" data-bs-toggle="tab" data-bs-target="#sabedoria" type="button" role="tab" aria-controls="sabedoria" aria-selected="false">Sabedoria</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="carisma-tab" data-bs-toggle="tab" data-bs-target="#carisma" type="button" role="tab" aria-controls="carisma" aria-selected="false">Carisma</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active p-2" id="forca" role="tabpanel" aria-labelledby="forca-tab">

                                        <p>Seu poder muscular. A Força é aplicada em testes de Atletismo e Luta; rolagens de dano corpo a corpo ou com armas de arremesso, e testes de Força para levantar peso e atos similares.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>

                                    </div>
                                    <div class="tab-pane fade p-2" id="destreza" role="tabpanel" aria-labelledby="destreza-tab">
                                        <p>Sua agilidade, reflexos, equilíbrio e coordenação motora. A Destreza é aplicada na Defesa e em testes de Acrobacia, Cavalgar, Furtividade, Iniciativa, Ladinagem, Pilotagem, Pontaria e Reflexos.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                    <div class="tab-pane fade p-2" id="constituicao" role="tabpanel" aria-labelledby="constituicao-tab">
                                        <p>Sua saúde e vigor. A Constituição é aplicada aos pontos de vida iniciais e por nível e em testes de Fortitude. Se a Constituição muda, seus pontos de vida aumentam ou diminuem retroativamente de acordo.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                    <div class="tab-pane fade p-2" id="inteligencia" role="tabpanel" aria-labelledby="inteligencia-tab">
                                        <p>Sua capacidade de raciocínio, memória e educação. A Inteligência é aplicada em testes de Conhecimento, Guerra, Investigação, Misticismo, Nobreza e Ofício. Além disso, se sua Inteligência for positiva, você recebe um número de perícias treinadas igual ao valor dela (não precisam ser da sua classe).</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                    <div class="tab-pane fade p-2" id="sabedoria" role="tabpanel" aria-labelledby="sabedoria-tab">
                                        <p>Sua observação, ponderação e determinação. A Sabedoria é aplicada em testes de Cura, Intuição, Percepção, Religião, Sobrevivência e Vontade.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                    <div class="tab-pane fade p-2" id="carisma" role="tabpanel" aria-labelledby="carisma-tab">
                                        <p>Sua força de personalidade e capacidade de persuasão, além de uma mistura de simpatia e beleza. O Carisma é aplicado em testes de Adestramento, Atuação, Diplomacia, Enganação, Intimidação e Jogatina.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                </div>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                    <!--Modal Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

    <!--Modal Step 2 abre quando a pagina abre-->
    <div class="modal fade" id="modalFichaGuiada2" tabindex="-1" aria-labelledby="modalFichaGuiada2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada2Label">Definindo os Atributos</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 balao-dialogo offset-2">
                                <p>Ótimo, vamos começar!</p>
                            </div>

                        </div>
                    </div>
                    <!--Modal Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    modal.addEventListener('shown.bs.modal', function() {
                        const dialogos = modal.querySelectorAll('.balao-dialogo');
                        dialogos.forEach((dialogo, index) => {
                            setTimeout(() => {
                                dialogo.classList.add('mostrar');
                            }, index * 700);
                        });
                    });
                    modal.addEventListener('hidden.bs.modal', function() {
                        const dialogos = modal.querySelectorAll('.balao-dialogo');
                        dialogos.forEach(dialogo => {
                            dialogo.classList.remove('mostrar');
                        });
                    });
                });
            });
        </script>
    <?php
    return ob_get_clean();
}
