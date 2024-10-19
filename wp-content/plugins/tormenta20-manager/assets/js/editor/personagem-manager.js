(function($) {
    $(document).ready(function() {

        // Inicializa funções de outros arquivos
        carregarFichaPersonagem($);
        
        //Configurações
        $('#configuracoes').on('click', function() {
            Swal.fire({
              title: "Configurações!",
              html: `<p>Excluir personagem aberto?<button onclick="excluirPersonagemAtual()">Sim!</button></p>
                    <p>Excluir dados do jogo em cache?<button onclick="excluirDadosCache()">Sim!</button></p>
                    <p>Recarregar scripts e página?<button onclick="recarregarScripts()">Sim!</button></p>
              `
            });
        });
        
        // Função para carregar a ficha do personagem
        function carregarFichaPersonagem() {
            let personagem = JSON.parse(localStorage.getItem('perso_edicao'));
            if (personagem) {
                toggleDivs('div_iniciar_perso', 'div_construir_perso');
                obterTodosDados($, ['atributos', 'racas', 'habilidades_raca']);
                $('#nome_ficha').val(personagem.nome);
                for (let atb of ['for', 'des', 'con', 'int', 'sab', 'car']) {
                    $(`#atb_${atb}_ficha`).val(totalAtributo(atb));
                }
                legendaAtributos();
                $('#jogador').val(personagem.jogador);
                if (personagem.raca.nome) {
                    $('#raca_ficha').val(personagem.raca.nome);
                }
                notify("success", `Personagem ${personagem.nome} carregado com sucesso`);
            }
        }
        
        $('#criar-personagem').click(function() {
            let nome = $('#nome-personagem-criar').val();
            if (!nome) {
                notify("info", 'Por favor, insira um nome para o personagem.');
                return;
            }
            let personagem = new Personagem(nome);
            personagem.jogador = tmData.userData.display_name;
            localStorage.setItem('perso_edicao', JSON.stringify(personagem));
            $('#nome-personagem-criar').val(''); // Limpa o campo de entrada
            toggleDivs('div_iniciar_perso', 'div_construir_perso');
            $('#nome-ficha').val(personagem.nome);
            location.reload();
        });
        
        
            //INICIO DA FICHA
            
        // Evento que captura as alterações em tempo real
        $('#nome_ficha').on('input', function() {
            salvarFichaEmTempoReal('nome', $(this).val());
        });
        
            //ATRIBUTOS
            
        // Configuração atributos
        $('#atributos_config').click(function() {
           const swalWithBootstrapButtons = Swal.mixin({
              customClass: {
                confirmButton: "btn",
                cancelButton: "btn",
                closeButton:"btn",
              },
              buttonsStyling: false
            });
            swalWithBootstrapButtons.fire({
              title: "Configuração de Atributos Base",
              showCancelButton: true,
              html:`<h5>Como deseja definir seus atributos?</h5>
                    <p style='font-size:0.8em;color:red'>Cuidado! Escolher um metodo reseta as configurações do outro.</p>`,
              confirmButtonText: "<i style='margin:10px'>Rolar</i>",
              cancelButtonText: "Comprar",
              reverseButtons: true
            }).then((result) => {
              if (result.isConfirmed) {
                swalWithBootstrapButtons.fire({
                    title: "<strong>Rolar Atributos</strong>",
                    html: paginaRolarAtributos(),
                    showCloseButton: true,
                    focusConfirm: false,
                });
              } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
              ) {
                swalWithBootstrapButtons.fire({
                    title: "<strong>Comprar Atributos</strong>",
                    html: paginaComprarAtributos(),
                    showCloseButton: true,
                    focusConfirm: false,
                });
              }
            });
        });

        // Captura alteração de atributo no Mercado usando delegação de eventos
        $(document).on('click', '.edit_atb_base_mais', function() {
            let atributo = $(this).data('atributo');
            console.log(atributo);
            alterarValor(atributo, 1);
        });
        $(document).on('click', '.edit_atb_base_menos', function() {
            let atributo = $(this).data('atributo');
            console.log(atributo);
            alterarValor(atributo, -1);
        });

        
        // Captura alteração de atributo na ficha
        $('.edit_atb_jogador_mais').on('click', function() {
            let atributo = $(this).data('atributo');
            atualizarAtributo(atributo, 'jogador', 1);
        });
        $('.edit_atb_jogador_menos').on('click', function() {
            let atributo = $(this).data('atributo');
            atualizarAtributo(atributo, 'jogador', -1);
        });
        
        
        //Rolar Atributo ao clicar no seu valor
        $('.rolar_atb_ficha').on('click', function() {
            let atributo = $(this).data('atributo');
            let valor_atb = totalAtributo(atributo);
            let valor_dado = rolarDado().soma;
            let color = 'black';
            if (valor_dado <10){
                color = 'red';
            } else if (valor_dado <16){
                color = '#DB9D00';
            } else if (valor_dado <19){
                color = 'green';
            } else {
                color = 'blue';
            }
            notify('', `Teste de ${atributo.toUpperCase()}:
            <strong style="font-size:3em;font-family:Tormenta20;color:${color}">${valor_atb+valor_dado}</strong>
            d20=${valor_dado}, atb=${valor_atb}`, 15000);
        });


// RAÇAS

// Configurações
$(document).on('click', '#config_raca', function() {
    Swal.fire({
        title: "Configurações da Raça",
        html: `
            <style>
                .expandable {
                    cursor: pointer;
                    background-color: #f0f0f0;
                    padding: 10px;
                    border: 1px solid #ddd;
                    margin-bottom: 5px;
                }
                
                .hidden {
                    display: none;
                }
                
                .raca-titulo-container,
                #raca-habilidades-container {
                    padding: 10px;
                    border: 1px solid #ddd;
                    margin-top: 5px;
                    background-color: #f9f9f9;
                }
            </style>
            <p style='font-size:0.8em;color:red'>Cuidado! Escolher uma raça deleta as configurações da outra.</p>
            <select id="select-racas">
                <option value="">Escolher...</option>
            </select>
            <div id="raca-info">
                <div class="raca-titulo-container">
                    <h3 id="raca-nome" class="expandable">Nome da Raça</h3>
                    <div id="raca-atributos-descricao" class="hidden">
                        <p id="raca-atributos"></p>
                        <p id="raca-descricao"></p>
                    </div>
                </div>
                <h4 class="expandable">Habilidades</h4>
                <div id="raca-habilidades-container" class="hidden">
                    <ul id="raca-habilidades"></ul>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Escolher',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            let raca_escolhida = $('#select-racas').val();
            if (!raca_escolhida) {
                Swal.showValidationMessage('Por favor, escolha uma raça');
                return false;
            }
            return raca_escolhida;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            let raca_escolhida = result.value;
            atualizarPersonagemComRaca(raca_escolhida);
        }
    });

    preencherSelectRacas();
});
        
// Função para preencher o select com as raças
function preencherSelectRacas() {
    let racas = JSON.parse(localStorage.getItem('racas'));
    let personagem = JSON.parse(localStorage.getItem('perso_edicao'));
    if (racas && Array.isArray(racas)) {
        let selectRacas = $('#select-racas');
        selectRacas.empty();
        selectRacas.append('<option value="">Escolher</option>');
        racas.forEach(function(raca) {
            selectRacas.append(`<option value="${raca.nome}">${raca.nome}</option>`);
        });
        if(personagem.raca.nome){
            exibirInfoRaca(personagem.raca.nome);
        }
    } else {
        console.log('Nenhuma raça encontrada no localStorage');
    }
}
        
// Exibir informações da raça ao trocar seleção
$(document).on('change', '#select-racas', function() {
    let raca_escolhida = $(this).val();
    exibirInfoRaca(raca_escolhida);
});
        
// Função para exibir informações da raça
function exibirInfoRaca(raca_escolhida) {
    let racas = JSON.parse(localStorage.getItem('racas')) || [];
    let habilidades_racas = JSON.parse(localStorage.getItem('habilidades_raca')) || [];

    let raca_info = racas.find(r => r.nome === raca_escolhida);
    
    if (raca_info) {
        // Atualizar nome da raça
        $('#raca-nome').text(raca_info.nome);

        // Atualizar atributos e descrição
        if (raca_info.atributos && raca_info.atributos !== null && raca_info.atributos !== undefined) {
            let atributos = JSON.parse(raca_info.atributos.replace(/\\'/g, '"'));
            let atributosList = $('#raca-atributos');
            atributosList.empty();
            for (let atributo in atributos) {
                atributosList.append(`
                <span>${atributo.toUpperCase()}: <strong>${atributos[atributo]}</strong></span>
                `);
            }
        } else {
            $('#raca-atributos').text('Esta raça não possui atributos adicionais.');
        }
        $('#raca-descricao').text(raca_info.descricao);

        // Atualizar habilidades
        let habilidades = habilidades_racas.filter(h => h.raca === raca_info.id);
        let habilidadesList = $('#raca-habilidades');
        habilidadesList.empty();
        habilidades.forEach(habilidade => {
            habilidadesList.append(`
                <li class="habilidade-item">
                    <div class="habilidade-nome expandable">${habilidade.nome}</div>
                    <div class="habilidade-descricao hidden">${habilidade.descricao}</div>
                </li>
            `);
        });
    } else {
        $('#raca-nome').text('');
        $('#raca-atributos').text('');
        $('#raca-descricao').text('');
        $('#raca-habilidades').empty();
    }

    // Inicializa os comportamentos expansíveis
    initExpandables();
}

        
// Função para inicializar comportamento expansível
function initExpandables() {
    // Remover eventos duplicados antes de adicionar novos
    $('#raca-nome').off('click');
    $('#raca-habilidades').off('click');
    $('h4.expandable').off('click');

    // Expansão para o bloco de raça (atributos + descrição)
    $('#raca-nome').on('click', function() {
        $('#raca-atributos-descricao').toggleClass('hidden');
    });

    // Expansão para habilidades
    $('#raca-habilidades').on('click', '.habilidade-nome', function() {
        $(this).next('.habilidade-descricao').toggleClass('hidden');
    });

    // Expansão para habilidades em bloco
    $('h4.expandable').on('click', function() {
        $('#raca-habilidades-container').toggleClass('hidden');
    });
}

        
// Função para atualizar o personagem com a raça escolhida
function atualizarPersonagemComRaca(raca_escolhida) {
    let racas = JSON.parse(localStorage.getItem('racas')) || [];
    let habilidades_racas = JSON.parse(localStorage.getItem('habilidades_raca')) || [];
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};

    // Resetar atributos da raça anterior
    for (let atributo in personagem.atributos) {
        if (personagem.atributos[atributo].raca) {
            substituirAtributo(atributo, 'raca', 0);
        }
    }

    // Adicionar atributos e habilidades de raça ao personagem
    let raca_info = racas.find(r => r.nome === raca_escolhida);
    
    if (raca_info) {
        let habilidades = {};
        habilidades_racas.forEach(function(habilidade) {
            if (habilidade.raca === raca_info.id) {
                habilidades[habilidade.nome] = habilidade.descricao;
            }
        });

        salvarFichaEmTempoReal('raca', {
            nome: raca_info.nome,
            descricao: raca_info.descricao,
            habilidades: habilidades
        });
        $('#raca_ficha').val(raca_info.nome);

        if (raca_info.atributos !== '' && raca_info.atributos !== undefined && raca_info.atributos !== null) {
            let atributos = JSON.parse(raca_info.atributos.replace(/\\'/g, '"'));
            for (let atributo in atributos) {
                atualizarAtributo(atributo, 'raca', atributos[atributo]);
            }
        }
    }
}

    });
})(jQuery);
