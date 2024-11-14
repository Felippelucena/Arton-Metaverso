// Função de notificação (utilizando SweetAlert)
function notify(icone, mensagem, time = 4000) {
    Swal.fire({
        position: "top-start",
        icon: icone,
        title: mensagem,
        showConfirmButton: false,
        timer: time,
        customClass: {
            popup: 'swal-wide'  // Classe global customizada
        }
    });
}

window.addEventListener('load', () => {
  const notificacao = JSON.parse(localStorage.getItem('notificacao'));
  
  if (notificacao) {
      // Exibe a notificação armazenada
      notify(notificacao.tipo, notificacao.mensagem)
      // Remove a notificação do localStorage após exibi-la
      localStorage.removeItem('notificacao');
  }
});

// Função para obter a hora atual formatada (opcional)
function getCurrentTime() {
  const now = new Date();
  return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

// Método para rolar um ou mais dados
function rolarDado(dados = 1, lados = 20) {
    if (dados < 1 || dados > 10 || lados < 2 || lados > 100) {
        console.log('Valores inválidos');
        return;
    }

    let soma = 0;
    let resultados = [];
    for (let i = 0; i < dados; i++) {
        let rolagem = Math.floor(Math.random() * lados) + 1;
        resultados.push(rolagem);
        soma += rolagem;
    }
    return { soma, resultados };
}


function getQueryStringParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
  }