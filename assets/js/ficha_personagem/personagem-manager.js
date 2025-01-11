const personagemID = getQueryStringParam("p");

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
  let atributo = totalAtributo(
    personagem.pericias[pericia].atributo
  );
  let modificadores_total = 0;
  for (let tag in personagem.pericias[pericia].modificadores) {
    if (personagem.pericias[pericia].modificadores[tag]) {
      modificadores_total += personagem.pericias[pericia].modificadores[tag];
    }
  }
  total = atributo + modificadores_total;
  return total;
}

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
  let soma = resultados.reduce((a, b) => a + b);
  if (soma < 6) {
    resultados = rolarAtributos();
  }
  return resultados;
}

function exibirHabilidades() {
  const personagem = JSON.parse(localStorage.getItem(personagemID));
  const habilidades_raca = personagem.raca.habilidades;
  let ficha_habilidades = document.getElementById("ficha_habilidades_lista");
  ficha_habilidades.innerHTML = "";

  if (habilidades_raca) {
    let cont = 0;
    for (let habilidade in habilidades_raca) {
      ficha_habilidades.innerHTML += `
            <div class="g-1">
              <div class="input-group">
                <input type="text" id="" class="form-control" value="${habilidade}" disabled>
                <button  class="btn btn-danger" type="button" habilidade="${habilidade}" data-bs-toggle="collapse" data-bs-target="#collapse${cont}" aria-expanded="false" aria-controls="collapse${cont}">@</button>
              </div>
              <div class="collapse" id="collapse${cont}">
                <div class="card card-body p-1">
                  ${habilidades_raca[habilidade].descricao}
                </div>
              </div>
            </div>
        `;
      cont += 1;
    }
  }
}

// SHORTCODES

iniciarShortCodes = (descricao) => {
  if (!descricao) {
    return;
  }
  let descricaoNova = descricao.replace(/\[\[(.*?)\]\]/g, "");

  let shortcodes = [];
  if (descricao.includes("[[")) {
    let regex = /\[\[(.*?)\]\]/g;
    let match;
    while ((match = regex.exec(descricao))) {
      shortcodes.push(match[1]);
    }
  }

  if (shortcodes.length > 0) {
    for (let shortcode of shortcodes) {
      if (shortcode.startsWith("*")) {
        descricaoNova += `[[${shortcode}]]`;
      } else {
        let [funcao, argumentos] = shortcode.split("|");
        argumentos = argumentos.split(";");
        argumentos[0] = argumentos[0].split(",");
        if (funcao === "substituir") {
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
  if (!descricao) {
    return;
  }
  let shortcodes = [];
  if (descricao.includes("[[")) {
    let regex = /\[\[(.*?)\]\]/g;
    let match;
    while ((match = regex.exec(descricao))) {
      shortcodes.push(match[1]);
    }
  }
  if (shortcodes.length > 0) {
    for (let shortcode of shortcodes) {
      if (shortcode.startsWith("*")) {
        shortcode = shortcode.slice(1);
        let [funcao, argumentos] = shortcode.split("|");
        argumentos = argumentos.split(";");
        argumentos[0] = argumentos[0].split(",");
        if (funcao === "substituir") {
          let perso = new Personagem();
          substituirCampoFicha(
            argumentos[0],
            perso[argumentos[0]]
          );
        }
      }
    }
  }
};
