(function($) {
    class Personagem {
        
        constructor(nome) {
            this.nome = nome;
            this.jogador = ''
            this.raca = {}; //{raca:'definir', descricao:'definir', habilidades:[{nome:'definir', descricao:'definir'},...]}
            this.classe = []; //[{classe_1:'definir', descricao:'definir', habilidades:[{nome:'definir',descricao:'definir'},...]},...]
            this.sexo = 'definir';
            this.descricao = 'definir';
            this.nivel = 1;
            this.pv = {atual:0, temporario:0, maximo:{base:0}, por_nivel:{base:0}};
            this.pm = {atual:0, temporario:0, maximo:{base:0}, por_nivel:{base:0}};
            this.pontos_disponiveis = 10
            this.atributos = {
                for: {base: 0},
                des: {base: 0},
                con: {base: 0},
                int: {base: 0},
                sab: {base: 0},
                car: {base: 0}
            };
        }
    }

    // Expor a função Personagem globalmente
    window.Personagem = Personagem;

})(jQuery);
