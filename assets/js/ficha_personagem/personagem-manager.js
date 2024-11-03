(function ($) {
  $(document).ready(function () {
    console.log("Personagem Manager carregado!");

    //INICIAR PAGINA

    // Carregar Personagem
    function getQueryStringParam(param) {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get(param);
    }
    const personagemID = getQueryStringParam("p");

    if (!personagemID) {
      window.location.href = "/personagens";
      localStorage.setItem(
        "notificacao",
        JSON.stringify({
          tipo: "error",
          mensagem: `É necessário escolher um personagem para abrir a ficha.`,
        })
      );
    }

    function atributosTemplate() {
      const atributos = ["FOR", "DES", "CON", "INT", "SAB", "CAR"];
      const ficha_atributos = document.getElementById("ficha_atributos");
      const template = document.getElementById("atributo-template");
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

    const tablela_pericias = jQuery("#tabela_pericias").DataTable();

    function periciasTable() {
      const periciasDados = JSON.parse(localStorage.getItem("pericias")) || [];
      tablela_pericias.clear().draw();
      periciasDados.forEach((pericia) => {
        let nome = pericia.nome;
        if (pericia.treinado) {
          nome += ` <i style="font-size:0.8em" class='ra ra-archery-target ra-lg'></i>`;
        }
        if (pericia.penalidade) {
          nome += ` <i style="font-size:0.8em" class='ra ra-cracked-shield ra-lg'></i>`;
        }
        tablela_pericias.row.add([
            `<span style="margin-left:30px">${nome}</span>` || "N/A",
            `<span pericia="${pericia.nome}" class="pericia_total font-t20 fs-4">${totalPericia(pericia.nome)}</span>` || 0,
            pericia.atributo || "N/A",
            `<input class="form-check-input" type="checkbox" value="" aria-label="Perícia Treinada" pericia="${pericia.nome}">`,
          ])
          .draw();
      });
    }
    periciasTable();

    const carregarFichaPersonagem = async () => {
      const personagem = JSON.parse(localStorage.getItem(personagemID));
      if (personagem) {
        document.getElementById("ficha_nome").value = personagem.nome;
        document.getElementById("ficha_jogador").value = personagem.jogador;
        const atributos = ["for", "des", "con", "int", "sab", "car"];
        for (const atb of atributos) {
          document.getElementById(`ficha_${atb}`).value = totalAtributo(atb);
        }
        if (personagem.raca?.nome) {
          document.getElementById("ficha_raca").value = personagem.raca.nome;
        }
        exibirHabilidades();
        notify(
          "success",
          `O personagem ${personagem.nome} foi carregado com sucesso`
        );
      }
    };
    carregarFichaPersonagem();

    function exibirHabilidades() {
      const personagem = JSON.parse(localStorage.getItem(personagemID));
      const habilidades_raca = personagem.raca.habilidades;
      let ficha_habilidades = $("#ficha_habilidades_lista");
      ficha_habilidades.empty();

      if (habilidades_raca) {
        let cont = 0;
        for (let habilidade in habilidades_raca) {
          let descricao_sem_shortcode = habilidades_raca[habilidade].replace(
            /\[\[(.*?)\]\]/g,
            ""
          );
          ficha_habilidades.append(`
            <div class="col-md-6  g-1">
              <div class="input-group">
                <input type="text" id="" class="form-control" value="${habilidade}" disabled>
                <button  class="btn btn-danger" type="button" habilidade="${habilidade}" data-bs-toggle="collapse" data-bs-target="#collapse${cont}" aria-expanded="false" aria-controls="collapse${cont}">@</button>
              </div>
              <div class="collapse" id="collapse${cont}">
                <div class="card card-body p-1">
                  ${descricao_sem_shortcode}
                </div>
              </div>
            </div>
        `);
          cont += 1;
        }
      }
    }

    //Funções globais

    function substituirCampoFicha(campo, valor) {
      let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};

      if (Array.isArray(campo)) {
        if (campo.length === 1) {
          personagem[campo[0]] = valor;
        } else if (campo.length === 2) {
          personagem[campo[0]][campo[1]] = valor;
        } else if (campo.length === 3) {
          personagem[campo[0]][campo[1]][campo[2]] = valor;
        } else if (campo.length === 4) {
          personagem[campo[0]][campo[1]][campo[2]][campo[3]] = valor;
        } else if (campo.length === 5) {
          personagem[campo[0]][campo[1]][campo[2]][campo[3]][campo[4]] = valor;
        }
      } else {
        personagem[campo] = valor;
      }
      localStorage.setItem(personagemID, JSON.stringify(personagem));
    }

    function totalAtributo(atributo) {
      let soma = 0;
      atributo = atributo.toLowerCase();
      let personagem = JSON.parse(localStorage.getItem(personagemID));
      for (let tag in personagem.atributos[atributo]) {
        soma += Number(personagem.atributos[atributo][tag]) || 0;
      }
      return soma;
    }

    function totalPericia(pericia) {
      let personagem = JSON.parse(localStorage.getItem(personagemID));
      let total = 0;
      let atributo = totalAtributo(personagem.pericias[pericia].atributo);
      let modificadores_total = 0;
      for (let tag in personagem.pericias[pericia].modificadores) {
        if (personagem.pericias[pericia].modificadores[tag]) {
          modificadores_total += personagem.pericias[pericia].modificadores[tag];
        }
      }
      total = atributo + modificadores_total;
      return total;
    }

    //INICIO DA FICHA

    // Atualizar nome do personagem em tempo real
    document.querySelector("#ficha_nome").addEventListener("input", (event) => {
      substituirCampoFicha("nome", event.target.value);
    });

    // ATRIBUTOS

    // Alterar Atributo - tag jogador
    $(".ficha_atb_mais").on("click", function () {
      let atributo = $(this).data("atributo");
      atualizarAtributo(atributo, "jogador", 1);
    });
    $(".ficha_atb_menos").on("click", function () {
      let atributo = $(this).data("atributo");
      atualizarAtributo(atributo, "jogador", -1);
    });

    //Modal ATRIBUTOS
    $("#ficha_atributos_config").click(function () {
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

    //Modal ATRIBUTOS COMPRAR
    $("#ficha_atributos_comprar").click(function () {
      html = modalAtributosComprar();
      $("#modal_atributos_comprar_div").html(html);
    });
    //Alterar Atributo - tag base
    $(document).on("click", ".edit_atb_base_mais", function () {
      let atributo = $(this).data("atributo");
      modalAtributosComprar_alterarValor(atributo, 1);
    });
    $(document).on("click", ".edit_atb_base_menos", function () {
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
      const custoDict = { "-1": -1, 0: 0, 1: 1, 2: 2, 3: 4, 4: 7 };
      let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
      let atributo = personagem.atributos[atributoNome];
      let novoValor = parseInt(atributo.base) + alteracao;

      if (novoValor >= -1 && novoValor <= 4) {
        const custo = custoDict[novoValor] - custoDict[atributo.base];
        if (personagem.pontos_disponiveis - custo >= 0) {
          personagem.pontos_disponiveis -= custo;
          substituirAtributo(atributoNome, "base", novoValor);
          substituirCampoFicha(
            "pontos_disponiveis",
            personagem.pontos_disponiveis
          );
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

    //Modal ATRIBUTOS ROLAR
    $("#ficha_atributos_rolar").click(function () {
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
    document.addEventListener("change", function (event) {
      if (event.target.classList.contains("select-rolar-atributo")) {
        modalAtributosRolar_atualizarSelects();
      }
    });

    // Rolar novamente
    document.addEventListener("click", function (event) {
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
    $("#salvar_atributo_rolados").click(function () {
      let todosSelecionados = true;
      $(".select-rolar-atributo").each(function () {
        if (!$(this).val()) {
          todosSelecionados = false;
        }
      });
      if (todosSelecionados) {
        let personagem = JSON.parse(localStorage.getItem(personagemID));
        let index = 0;
        $(".select-rolar-atributo").each(function () {
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

    //ROLAR ATRIBUTOS
    $(".rolar_atb_ficha").on("click", function () {
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

    // Função para atualizar um atributo específico e salvar no localStorage
    function atualizarAtributo(atributoNome, tag, valor) {
      let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
      if (personagem.atributos[atributoNome][tag]) {
        personagem.atributos[atributoNome][tag] += Number(valor);
      } else {
        personagem.atributos[atributoNome][tag] = Number(valor);
      }
      localStorage.setItem(personagemID, JSON.stringify(personagem));
      let element = document.getElementById(`ficha_${atributoNome}`);
      if (element) {
        element.value = totalAtributo(atributoNome);
      }
    }

    // Função para atualizar um atributo específico e salvar no localStorage
    function substituirAtributo(atributoNome, tag, valor) {
      let personagem = JSON.parse(localStorage.getItem(personagemID)) || {};
      personagem.atributos[atributoNome][tag] = Number(valor);
      localStorage.setItem(personagemID, JSON.stringify(personagem));
      let element = document.getElementById(`ficha_${atributoNome}`);
      if (element) {
        element.value = totalAtributo(atributoNome);
      }
    }

    function rolarAtributos() {
      let resultados = [];

      for (let i = 0; i < 6; i++) {
        let resultados_atb = [];
        let soma = 0;
        let atributo = 0;

        for (let i = 0; i < 4; i++) {
          resultados_atb.push(rolarDado(1, 6).soma);
        }
        resultados_atb.sort((a, b) => a - b);
        resultados_atb.shift();
        for (let i = 0; i < resultados_atb.length; i++) {
          soma += resultados_atb[i];
        }
        if (soma < 7) {
          atributo = -2;
        } else if (soma < 10) {
          atributo = -1;
        } else if (soma < 12) {
          atributo = 0;
        } else if (soma < 14) {
          atributo = 1;
        } else if (soma < 16) {
          atributo = 2;
        } else if (soma < 18) {
          atributo = 3;
        } else {
          atributo = 4;
        }

        resultados.push(atributo);
      }
      //verificar se a soma dos atributos é menor que 6, caso seja rolar novamente
      let soma = resultados.reduce((a, b) => a + b);
      if (soma < 6) {
        resultados = rolarAtributos();
      }
      return resultados;
    }

    // RAÇA

    //Modal RAÇA
    $(document).on("click", "#ficha_raca_modal", function () {
      modalRacaPreencherSelect();
    });

    // Exibir informações da raça ao trocar seleção
    $(document).on("change", "#modal-select-racas", function () {
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
        racas.forEach(function (raca) {
          selectRacas.append(
            `<option value="${raca.nome}">${raca.nome}</option>`
          );
        });
        if (personagem.raca.nome) {
          modalRacaExibirInfo(personagem.raca.nome);
        }
      } else {
        console.log("Nenhuma raça encontrada no localStorage");
      }
    }

    // Função para exibir informações da raça
    function modalRacaExibirInfo(raca_escolhida) {
      const imagemNome = {
        Humano: "humano",
        Anão: "anao",
        Elfo: "elfo",
        Golem: "golem",
        Dahllan: "dahalla",
        Goblin: "goblin",
        Lefou: "lefou",
        Minotauro: "minotauro",
        "Sereia/tritão": "sereia",
        Osteon: "osteon",
        Medusa: "medusa",
        Kliren: "kliren",
        Hynne: "hynne",
        Sílfide: "silfide",
        Suraggel: "suraggel",
        Trog: "trog",
        Qareen: "qareen",
      };

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
          `url('https://arton.felippelucena.com/wp-content/uploads/2024/10/${
            imagemNome[raca_info.nome]
          }.webp')`
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
          let descricao_sem_shortcode = habilidade.descricao.replace(
            /\[\[(.*?)\]\]/g,
            ""
          );
          habilidadesList.append(`
              <div class="habilidade-item accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button habilidade-nome" type="button" data-bs-toggle="collapse" data-bs-target="#${collapse_target[cont]}" aria-expanded="false" aria-controls="${collapse_target[cont]}">${habilidade.nome}</button>
                </h2>
                <div id="${collapse_target[cont]}" class="habilidade-descricao accordion-collapse collapse" data-bs-parent="#raca-habilidades">
                <div class="accordion-body">${descricao_sem_shortcode}</div>
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
    $(document).on("click", "#salvar_raca", function () {
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
        reverterShortCodes(personagem.raca.habilidades[habilidade]);
      }

      // Adicionar atributos e habilidades de raça ao personagem
      let raca_info = racas.find((r) => r.nome === raca_escolhida);

      if (raca_info) {
        let habilidades = {};
        habilidades_racas.forEach(function (habilidade) {
          if (habilidade.raca === raca_info.id) {
            let descricao = iniciarShortCodes(habilidade.descricao);
            habilidades[habilidade.nome] = descricao;
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
      }
    }
    iniciarShortCodes = (descricao) => {
      // Guardar a descrição sem shortcodes:
      let descricaoNova = descricao.replace(/\[\[(.*?)\]\]/g, "");
      // Guardar em uma lista todos os shortcodes encontrados. Shortcodes são strings entre '[[]]'
      let shortcodes = [];
      if (descricao.includes("[[")) {
        let regex = /\[\[(.*?)\]\]/g;
        let match;
        while ((match = regex.exec(descricao))) {
          shortcodes.push(match[1]);
        }
      }
      if (shortcodes.length > 0) {
        // Separar função e argumentos
        for (let shortcode of shortcodes) {
          // se o shortcode já foi resolvido, pular para o próximo
          if (shortcode.startsWith("*")) {
            descricaoNova += `[[${shortcode}]]`;
          } else {
            let [funcao, argumentos] = shortcode.split("|");
            // Executa a função e substitui o shortcode pela descrição
            argumentos = argumentos.split(";");
            argumentos[0] = argumentos[0].split(",");
            if (funcao === "substituir") {
              // Verifica se argumentos[1] é um número em string e converte se for
              if (!isNaN(argumentos[1])) {
                argumentos[1] = Number(argumentos[1]);
              }
              substituirCampoFicha(argumentos[0], argumentos[1]);
              descricaoNova += `[[*${shortcode}]]`;
            } else {
              descricaoNova += `[[${shortcode}]]`;
            }
          }
        }
      }

      return descricaoNova;
    };

    reverterShortCodes = (descricao) => {
      // Guardar em uma lista todos os shortcodes encontrados. Shortcodes são strings entre '[[]]'
      let shortcodes = [];
      if (descricao.includes("[[")) {
        let regex = /\[\[(.*?)\]\]/g;
        let match;
        while ((match = regex.exec(descricao))) {
          shortcodes.push(match[1]);
        }
      }
      if (shortcodes.length > 0) {
        // Separar função e argumentos
        for (let shortcode of shortcodes) {
          if (shortcode.startsWith("*")) {
            // Remove o * do shortcode
            shortcode = shortcode.slice(1);
            let [funcao, argumentos] = shortcode.split("|");
            // Executa a função e substitui o shortcode pela descrição
            argumentos = argumentos.split(";");
            argumentos[0] = argumentos[0].split(",");
            if (funcao === "substituir") {
              let perso = new Personagem();
              console.log(argumentos[0]);
              console.log(perso[argumentos[0]]);
              substituirCampoFicha(argumentos[0], perso[argumentos[0]]);
            }
          }
        }
      }
    };
  });
})(jQuery);
