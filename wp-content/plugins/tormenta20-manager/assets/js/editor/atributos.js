// Renderiza a pagina de compra de atributos
function paginaComprarAtributos() {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    let html = `
        <p><strong>Pontos Disponíveis: ${personagem.pontos_disponiveis}</strong></p>
        <table id="tabela-mercado-atributos" border="1" style="width:100%">
            <thead>
                <tr>
                    <th>Atributo</th>
                    <th>Base</th>
                    <th>Racial</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>`;
    for (let atributo in personagem.atributos) {
        html += `
            <tr>
                <td>${atributo.toUpperCase()}</td>
                <td style="display:flex">
                    <input type="number" value="${personagem.atributos[atributo].base}" min="-1" max="4" style="widht:30px" disabled>
                    <div style="display:flex;flex-wrap:wrap;gap:5px">
                        <button class="edit_atb_base_mais" data-atributo="${atributo}">+</button>
                        <button class="edit_atb_base_menos" data-atributo="${atributo}">-</button>
                    </div>
                </td>
                <td>${personagem.atributos[atributo].raca || 0}</td>
                <td>${totalAtributo(atributo)}</td>
            </tr>`;
    }
    html += `</tbody></table>`;
    return html;
}

// Função que altera o valor do atributo
function alterarValor(atributoNome, alteracao) {
    const custoDict = {'-1': -1,'0': 0,'1': 1,'2': 2,'3': 4,'4': 7};
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    let atributo = personagem.atributos[atributoNome];
    let novoValor = atributo.base + alteracao
    novoValor = parseInt(novoValor);
    if (novoValor >= -1 && novoValor <= 4) {
        const custo = custoDict[novoValor] - custoDict[atributo.base];
        if (personagem.pontos_disponiveis - custo >= 0) {
            personagem.pontos_disponiveis -= custo;
            substituirAtributo(atributoNome, 'base', novoValor);
            salvarFichaEmTempoReal('pontos_disponiveis', personagem.pontos_disponiveis)
            Swal.update({ html: paginaComprarAtributos() });
        } else {
            Swal.update({ html: paginaComprarAtributos() });
        }
    }
}

// Renderiza a página de rolagem de atributos
function paginaRolarAtributos() {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    let valoresRolados = rolarAtributos();  // Gera os 6 valores
    let atributos = ['for', 'des', 'con', 'int', 'sab', 'car'];  // Os 6 atributos

    // Construindo o HTML da página de rolagem
    let html = `
        <p><strong>Distribua os valores rolados entre os atributos:</strong></p>
        <p>Valores rolados: [${valoresRolados.join(', ')}]</p>
        <table id="tabela-rolar-atributos" border="1" style="width:100%">
            <thead>
                <tr>
                    <th>Atributo</th>
                    <th>Valor Rolado</th>
                </tr>
            </thead>
            <tbody>`;

    // Gerar uma linha para cada atributo
    atributos.forEach((atributo, index) => {
        html += 
            `<tr>
                <td>${atributo.toUpperCase()}</td>
                <td>
                    <select class="select-atributo" data-atributo="${atributo}">
                        <option value="">Selecione o valor</option>`;
        
        // Gerar opções com os valores rolados
        valoresRolados.forEach((valor, i) => {
            html += `<option value="${valor}">${valor}</option>`;
        });

        html += `</select>
                </td>
            </tr>`;
    });

    html += `</tbody></table>`;
    return html;
}


function totalAtributo(atributo){
    let soma = 0;
    let personagem = JSON.parse(localStorage.getItem('perso_edicao'));
    for (let tag in personagem.atributos[atributo]){
        soma += Number(personagem.atributos[atributo][tag]) || 0;
    }
    return soma
}

// Função para atualizar um atributo específico e salvar no localStorage
function atualizarAtributo(atributoNome, tag, valor) {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    if (personagem.atributos[atributoNome][tag]){
        personagem.atributos[atributoNome][tag] += Number(valor);
    } else {
        personagem.atributos[atributoNome][tag] = Number(valor);
    }
    localStorage.setItem('perso_edicao', JSON.stringify(personagem));
    let element = document.getElementById(`atb_${atributoNome}_ficha`);
    if (element) {
        element.value = totalAtributo(atributoNome);
    }
    legendaAtributos()
}

// Função para atualizar um atributo específico e salvar no localStorage
function substituirAtributo(atributoNome, tag, valor) {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao')) || {};
    personagem.atributos[atributoNome][tag] = Number(valor);
    localStorage.setItem('perso_edicao', JSON.stringify(personagem));
    let element = document.getElementById(`atb_${atributoNome}_ficha`);
    if (element) {
        element.value = totalAtributo(atributoNome);
    }
    legendaAtributos()
}


function legendaAtributos() {
    let personagem = JSON.parse(localStorage.getItem('perso_edicao'));
    if (personagem && personagem.atributos) {
        for (let atb of ['for', 'des', 'con', 'int', 'sab', 'car']) {
            let titleContent = '';
            for (let tag in personagem.atributos[atb]) {
                titleContent += `${tag}: ${personagem.atributos[atb][tag]}\n`;
            }
            let element = document.getElementById(`atb_${atb}_info`);
            if (element) {
                element.title = titleContent.trim();
            }
        }
    }
}

    
function rolarAtributos() {
    let resultados = [];

    for (let i = 0; i < 6; i++) {
        // rolar 4d6 (usando o metodo rolarDado) e descartar o menor valor e somar os 3 maiores valores
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
        //converter a soma de acordo com o dict a seguir {7=-2, 8-9=-1, 10-11=0, 12-13=1, 14-15=2, 16-17=3, 18=4}
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
        resultados = this.rolarAtributos();
    }
    return resultados;
}