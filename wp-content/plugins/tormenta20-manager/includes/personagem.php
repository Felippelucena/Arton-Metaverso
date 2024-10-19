<?php

function mercado_atributos_shortcode() {
    ob_start(); ?>
        <button class="elementor-button" onclick="mercado_atributos()">Abrir Mercado de Atributos</button>
        
        <script>
            let pontosDisponiveis = 10;
            const custoDict = {
                '-1': -1,
                '0': 0,
                '1': 1,
                '2': 2,
                '3': 4,
                '4': 7
            };
        
            const atributos = [
                { nome: 'Força', base: 0, racial: 0, total: 0 },
                { nome: 'Destreza', base: 0, racial: 0, total: 0 },
                { nome: 'Constituição', base: 0, racial: 0, total: 0 },
                { nome: 'Inteligência', base: 0, racial: 0, total: 0 }
            ];
        
            function mercado_atributos(){
                Swal.fire({
                    title: "<strong>Comprar Atributos</strong>",
                    html: atualizarTabela(),
                    showCloseButton: true,
                    focusConfirm: false,
                });
            }
        
            function calcularTotal(atributo) {
                return atributo.base + atributo.racial;
            }
        
            function atualizarTabela() {
                let html = `
                    <p><strong>Pontos Disponíveis: ${pontosDisponiveis}</strong></p>
                    <table border="1" style="width:100%">
                        <thead>
                            <tr>
                                <th>Atributo</th>
                                <th>Base</th>
                                <th>Racial</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>`;
        
                atributos.forEach((atributo, index) => {
                    html += `
                        <tr>
                            <td>${atributo.nome}</td>
                            <td>
                                <input type="number" value="${atributo.base}" min="-1" max="4" 
                                       oninput="alterarValor(${index}, this.value)">
                            </td>
                            <td>${atributo.racial}</td>
                            <td>${calcularTotal(atributo)}</td>
                        </tr>`;
                });
        
                html += `</tbody></table>`;
                return html;
            }
        
            function alterarValor(index, novoValor) {
                const atributo = atributos[index];
                novoValor = parseInt(novoValor); // Converter o valor para inteiro
        
                if (novoValor >= -1 && novoValor <= 4) {
                    const custo = custoDict[novoValor] - custoDict[atributo.base];
                    if (pontosDisponiveis - custo >= 0) {
                        pontosDisponiveis -= custo;
                        atributo.base = novoValor;
                        Swal.update({ html: atualizarTabela() });
                    } else {
                        Swal.update({ html: atualizarTabela() });
                    }
                } else {
                    Swal.fire('Valor inválido!', 'O valor deve estar entre -1 e 4.', 'error');
                }
            }
        </script>
    <?php
    return ob_get_clean();
}
add_shortcode('mercado_atributos', 'mercado_atributos_shortcode');

?>