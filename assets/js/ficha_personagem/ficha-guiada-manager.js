jQuery(document).ready(function ($) {
  step = 0;
  if (step == 0) {
    personagem = JSON.parse(localStorage.getItem(personagemID));
    $("#modalFichaGuiada0").modal("show");
    $("#ficha_guiada_nome").text(personagem.nome);
  }

  //ouvir se o botao_ficha_guiada foi clicado
  $("#botao_ficha_guiada").click(function () {
    $(`#modalFichaGuiada${step}`).modal("show");
  });

  $(".next-step").click(function () {
    step++;
    $(`#modalFichaGuiada${step}`).modal("show");
  });
});
