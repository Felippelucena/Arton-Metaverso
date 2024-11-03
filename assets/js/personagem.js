(function() {
    class Personagem {
        
        constructor(nome) {
            this.nome = nome;
            this.jogador = ''
            this.raca = 'definir'; //{raca:'definir', descricao:'definir', habilidades:[{nome:'definir', descricao:'definir'},...]}
            this.classe = 'definir'; //[{classe_1:'definir', nivel:1, descricao:'definir', habilidades:[{nome:'definir',descricao:'definir'},...]},...]
            this.origem = 'definir';
            this.divindade = 'definir';
            this.descricao = 'definir';
            this.tamanho = 'Médio';
            this.deslocamento = 9;
            this.idade = 'definir';
            this.alinhamento = 'definir';
            this.nivel = 1;
            this.pv = {dano:0, temporario:0, maximo:{base:0}, por_nivel:{base:0}};
            this.pm = {dano:0, temporario:0, maximo:{base:0}, por_nivel:{base:0}};
            this.pontos_disponiveis = 10;
            this.atributos = {
                for: {base: 0},
                des: {base: 0},
                con: {base: 0},
                int: {base: 0},
                sab: {base: 0},
                car: {base: 0}
            };
            this.pericias = {};
        }
    }

    // Expor a função Personagem globalmente
    window.Personagem = Personagem;

})(jQuery);
