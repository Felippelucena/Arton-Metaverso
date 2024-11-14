(function ($) {

  $(document).ready(function () {
    function consultarLivro(tabelas) {
      let dadosCarregados = {};
      let faltamDados = false;
      tabelas.forEach((tabela) => {
        const dado = localStorage.getItem(tabela);
        if (dado) {
          dadosCarregados[tabela] = JSON.parse(dado);
        } else {
          faltamDados = true;
        }
      });
      if (!faltamDados) {
        return dadosCarregados;
      }
      $.ajax({
        url: tmData.ajaxurl,
        method: "POST",
        data: {
          action: "tm_consultar_livro",
          tabelas: tabelas,
        },
        success: function (response) {
          tabelas.forEach((tabela) => {
            if (response[tabela]) {
              localStorage.setItem(tabela, JSON.stringify(response[tabela]));
            }
          });
        }
      });
    }
    consultarLivro(["racas", "habilidades_raca","pericias"]);
  });
})(jQuery);
