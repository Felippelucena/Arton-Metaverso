(function ($) {
    $(document).ready(function () {

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
          tablela_pericias.row
            .add([
              `<span style="margin-left:30px">${nome}</span>` || "N/A",
              `<span pericia="${
                pericia.nome
              }" class="pericia_total font-t20 fs-4">${totalPericia(pericia.nome)}</span>` || 0,
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
        }
      };
      carregarFichaPersonagem();
  
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

    });
  })(jQuery);