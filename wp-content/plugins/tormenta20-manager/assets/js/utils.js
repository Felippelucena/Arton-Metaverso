// Função de notificação (utilizando SweetAlert)
function notify(icone, mensagem, time = 4000) {
    Swal.fire({
        position: "bottom-start",
        icon: icone,
        title: mensagem,
        showConfirmButton: false,
        timer: time
    });
}

// Função para alternar a visibilidade de duas divs
function toggleDivs(idcss1, idcss2) {
    const div1 = document.getElementById(idcss1);
    const div2 = document.getElementById(idcss2);
    
    if (div1.style.display !== 'none') {
        div1.style.display = 'none';  // Oculta div1
        div2.style.display = 'block';  // Mostra div2
    } else {
        div1.style.display = 'block';  // Mostra div1
        div2.style.display = 'none';   // Oculta div2
    }
}

// Função para salvar as mudanças da ficha no localStorage em tempo real
function salvarFichaEmTempoReal(campo, valor) {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    personagem[campo] = valor;
    localStorage.setItem('perso_edicao', JSON.stringify(personagem));
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

function excluirPersonagemAtual() {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao'));

    if (personagem) {
        localStorage.removeItem('perso_edicao');
        Swal.fire({
            icon: 'success',
            title: 'Personagem excluído!',
            text: 'O personagem atual foi removido com sucesso.',
        }).then(() => {
            location.reload();
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Nenhum personagem encontrado para excluir.',
        });
    }
}


function excluirDadosCache() {
        localStorage.removeItem('atributos');
        localStorage.removeItem('racas');
        localStorage.removeItem('habilidades_raca');
        Swal.fire({
            icon: 'success',
            title: 'Dados excluídos!',
            text: 'Os dados salvos em cache foram apagados.',
        }).then(() => {
            location.reload();
        });

}

function recarregarScripts() {
    Swal.fire({
    title: "Recarregar página?",
    text: "Isso vai forçar a atualização e limpar o cache!",
    showCancelButton: true,
    confirmButtonText: "Recarregar",
    cancelButtonText: "Cancelar"
}).then((result) => {
    if (result.isConfirmed) {
        location.reload(true);
    }
});
}