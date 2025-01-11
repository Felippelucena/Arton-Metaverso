<?php
function modais_ficha_guiada_html()
{
    ob_start(); ?>
    <!--Modal Step 0 abre quando a pagina abre-->
    <div class="modal modal-ficha-guiada fade" id="modalFichaGuiada0" tabindex="-1" aria-labelledby="modalFichaGuiada0Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada0Label">Bem Vind@ a Arton <strong id="ficha_guiada_nome" class="font-t20 fs-3"></strong></h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-9 balao-dialogo offset-1">
                                Meu nome é <strong>Grog</strong>, sou de Deheon e estou aqui para te ajudar a criar sua ficha.</p>
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
    <div class="modal modal-ficha-guiada fade" id="modalFichaGuiada1" tabindex="-1" aria-labelledby="modalFichaGuiada1Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada1Label">Definindo os Atributos</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
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
                            <div class="balao-dialogo text-center">
                                <p>Há duas maneiras de definir seus atributos: com pontos ou com rolagens. Escolha a que preferir.</p>
                                <p style='font-size:0.8em;color:red'>Cuidado! Escolher um método reseta as configurações do outro.</p>
                                <ul class="nav nav-tabs" id="definir_atributos" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pontos-tab" data-bs-toggle="tab" data-bs-target="#pontos" type="button" role="tab" aria-controls="pontos" aria-selected="true">Pontos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="rolagens-tab" data-bs-toggle="tab" data-bs-target="#rolagens" type="button" role="tab" aria-controls="rolagens" aria-selected="false">Rolagens</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tabela-tab" data-bs-toggle="tab" data-bs-target="#tabela" type="button" role="tab" aria-controls="tabela" aria-selected="false">Tabela</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="definir_atributosContent">
                                    <div class="tab-pane fade show active p-2 row" id="pontos" role="tabpanel" aria-labelledby="pontos-tab">

                                        <p>Você começa com todos os atributos em 0 e recebe 10 pontos para aumentá-los. O custo para aumentar cada atributo está descrito na tabela abaixo. Você também pode reduzir um atributo para –1 para receber 1 ponto adicional.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                        <br>
                                        <button class="ficha_atributos_comprar btn btn-danger nao-fechar-modal" type="button" data-modal="modal_atributos_comprar">
                                            Comprar
                                        </button>

                                    </div>
                                    <div class="tab-pane fade p-2 row" id="rolagens" role="tabpanel" aria-labelledby="rolagens-tab">
                                        <p>Role 4d6, descarte o menor e some os outros três. Anote o resultado. Repita esse processo cinco vezes, até obter um total de seis números. Então, converta esses números em atributos conforme a tabela abaixo. Por exemplo, se você rolar 13, 8, 15, 18, 10 e 9, seus atributos serão 1, –1, 2, 4, 0 e –1. Distribua esses valores entre os seis atributos como quiser. Caso seus atributos não somem pelo menos 6, role novamente o menor valor. Repita esse processo até seus atributos somarem 6 ou mais.</p>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>

                                        <br>
                                        <button class="ficha_atributos_rolar btn btn-danger nao-fechar-modal" type="button" data-modal="modal_atributos_rolar">
                                            Rolar
                                        </button>
                                    </div>
                                    <div class="tab-pane fade p-2" id="tabela" role="tabpanel" aria-labelledby="tabela-tab">
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <th>Atributo</th>
                                                    <th>Custo</th>
                                                    <th>Rolagem</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>-2</td>
                                                    <td>-</td>
                                                    <td>7 ou menos</td>
                                                </tr>
                                                <tr>
                                                    <td>-1</td>
                                                    <td>–1</td>
                                                    <td>8-9</td>
                                                </tr>
                                                <tr>
                                                    <td>0</td>
                                                    <td>0</td>
                                                    <td>10-11</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>1</td>
                                                    <td>12-13</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>2</td>
                                                    <td>14-15</td>
                                                </tr>
                                                <tr>
                                                    <td>3</td>
                                                    <td>4</td>
                                                    <td>16-17</td>
                                                </tr>
                                                <tr>
                                                    <td>4</td>
                                                    <td>7</td>
                                                    <td>18</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <cite>Fonte: Tormenta 20, O Jogo do Ano, p. 17</cite>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Modal Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary before-step" data-bs-dismiss="modal">Voltar</button>
                        <button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Step 2 abre quando a pagina abre-->
    <div class="modal modal-ficha-guiada fade" id="modalFichaGuiada2" tabindex="-1" aria-labelledby="modalFichaGuiada2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada2Label">Definindo a Raça</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-9 balao-dialogo offset-1">
                                <p>Cada raça tem características próprias que afetam seus atributos e habilidades.</p>
                            </div>
                            <div class="col-9 balao-dialogo offset-2">
                                <p>Algumas Raças possuem habilidades especiais, como visão no escuro, resistência a magia, entre outras.</p>
                            </div>
                            <div class="row col-9 balao-dialogo">
                                <p>No botão abaixo você consegue ver as raças disponiveis e salvar a escolhida.</p>
                                <button class="ficha_raca_modal btn btn-danger nao-fechar-modal" type="button" data-modal="modal_raca">
                                    Escolher Raça
                                </button>
                            </div>
                        </div>
                    </div>
                    <!--Modal Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary before-step" data-bs-dismiss="modal">Voltar</button>
                        <button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Step 3 abre quando a pagina abre-->
    <div class="modal modal-ficha-guiada fade" id="modalFichaGuiada3" tabindex="-1" aria-labelledby="modalFichaGuiada3Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <!--Modal Header-->
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFichaGuiada3Label">Definindo a Classe</h5>
                    <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Fechar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <!--Modal Body-->
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-9 balao-dialogo offset-1">
                                <p>Cada classe tem características próprias que afetam seus atributos e habilidades.</p>
                            </div>
                            <div class="col-9 balao-dialogo offset-2">
                                <p>Algumas Classes possuem habilidades especiais, como conjuração de magias, ataques furtivos, entre outras.</p>
                            </div>
                            <div class="row col-9 balao-dialogo">
                                <p>No botão abaixo você consegue ver as classes disponiveis e salvar a escolhida.</p>
                                <button class="ficha_classe_modal btn btn-danger nao-fechar-modal" type="button" data-modal="modal_classe">
                                    Escolher Classe
                                </button>
                            </div>

                        </div>
                    </div>
                    <!--Modal Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary before-step" data-bs-dismiss="modal">Voltar</button>
                        <!--<button type="button" class="btn btn-danger next-step" data-bs-dismiss="modal">Continuar</button>-->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        jQuery(document).ready(function($) {

            function iniciarFichaGuiada() {
                let personagem = JSON.parse(localStorage.getItem(personagemID));
                if (personagem.step == 0) {
                    $("#modalFichaGuiada0").modal("show");
                    $("#ficha_guiada_nome").text(personagem.nome);
                }
            }
            iniciarFichaGuiada()

            //ouvir se o botao_ficha_guiada foi clicado
            $("#botao_ficha_guiada").click(function() {
                let personagem = JSON.parse(localStorage.getItem(personagemID));
                $(`#modalFichaGuiada${personagem.step}`).modal("show");
            });

            $(".next-step").click(function() {
                let personagem = JSON.parse(localStorage.getItem(personagemID));
                personagem.step++;
                localStorage.setItem(personagemID, JSON.stringify(personagem));
                $(`#modalFichaGuiada${personagem.step}`).modal("show");
            });
            $(".before-step").click(function() {
                let personagem = JSON.parse(localStorage.getItem(personagemID));
                personagem.step--;
                localStorage.setItem(personagemID, JSON.stringify(personagem));
                $(`#modalFichaGuiada${personagem.step}`).modal("show");
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('.modal-ficha-guiada');
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
