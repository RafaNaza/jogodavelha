/** Variáveis & objetos */
const DEU_VELHA = 'DEU VELHA! <i style="font-size:20px;" class="fas fa-hashtag"></i>';
const VENCEDOR  = "Vencedor : ";
const TIME = 1000;
const MSG_INICIAR_PARTIDA = "<div class='alert alert-warning' style='font-size:12px;'>Precione o botão 'INICIAR NOVA PARTIDA' para setar o nível.</div>";
const MSG_BOA_PARTIDA = "<div class='alert alert-info' style='font-size:12px;'>Boa Partida.</div>";
/** */
let dificuldade = {
    valor : 0
    ,bloquear : 2
    ,getValor : function(){
        return this.valor;
    }
    ,setValor : function(valor){
        this.valor = eval(valor);
    }
    ,getValorAmigavel : function(){
        ret = "";
        switch(this.valor){
            case 0 : ret = "Fácil";break;
            case 1 : ret = "Médio";break;
            case 2 : ret = "Difícil";break;
        }
        return ret;
    }
    ,getBloqueios : function(){
        return this.bloquear*this.valor;
    }
    ,setDificuldade : function(){
        dificuldade.setValor($("#jv_nivel").val());
        $("#jv_nivel_title").html( dificuldade.getValorAmigavel() );
        $("#msg_nivel").html(MSG_BOA_PARTIDA);
    }
};
let jogadas = {
    x:[]
    ,o:[]
    ,todas : [11,12,13,21,22,23,31,32,33]
    ,qtd:0
    ,setJogada : function(jogada,chave){
        switch(jogada){
            case "x" : this.x.push(chave);break;
            case "o" : this.o.push(chave);break;
        };

        this.todas.splice( this.todas.indexOf(chave),1 );
        this.qtd++;
    }
    ,getJogada : function(jogada){
        switch(jogada){
            case "x" : return this.x;break;
            case "o" : return this.o;break;
        }
    }
    ,getJogadaInversa : function(jogada){
        switch(jogada){
            case "x" : return this.o;break;
            case "o" : return this.x;break;
        }
    }
    ,reset : function(){
        this.x = [];
        this.o = [];
        this.todas = [11,12,13,21,22,23,31,32,33];
        this.qtd = 0;
    }
    ,setMovimento : function(el,chave){
        /** marca celula checada */
        el.data("checado",jogadorCorrente.getJogador());
        /** guarda jogada realizada */
        this.setJogada(jogadorCorrente.getJogador(),chave);        
        /** printa na celula X ou O  */
        el.html(jogadorCorrente.getJogadorMarcador());        
        /** Verifica se jogador ganhou */
        finalizacao.verificaJogada( this.getJogada(jogadorCorrente.getJogador()) );
        /** troca turno */
        jogadorCorrente.trocaTurno();
        /** ativa/inativa cpu. Avisa que é a CPU está jogando */
        cpu.autoSet();
    }
    ,registrar : function(){
        console.log("Registrar Jogada!");
        data = {
            jogador_vencedor : (!finalizacao.velha?jogadorCorrente.getJogadorTipo():"")
            ,quantidade_jogadas : this.qtd
            ,jogadas_x : this.x
            ,jogadas_o : this.o
            ,jogada_vencedora : finalizacao.jogadaVencedora
            ,marcador_vencedor : finalizacao.vencedor
            ,velha : (finalizacao.velha?"S":"N")
            ,dificuldade : dificuldade.getValorAmigavel()
        }

        $.ajax({
            url : INDEX+"registrar"
            ,data : {data : JSON.stringify(data) }
            ,method : "POST"
            ,success : function(out){
                console.log(out);
            }
        });
    }
};
let jogadorCorrente = {
    turno : true    
    ,tipo : {x:"", o:""}
    ,trocaTurno : function(){
        this.turno = !this.turno;
    }
    ,getJogador : function() {
        return (this.turno?"x":"o");
    }
    ,getJogadorMarcador : function() {
        switch(this.getJogador()){
            case "x" : return '<i class="fas fa-times"></i>';break;
            case "o" : return '<i class="far fa-circle"></i>';break;     
        }
    }
    ,reset : function(){
        this.turno = true;
        this.tipo = {x:"", o:""};
        return this;
    }
    ,setTipo : function(cpu){
        if(cpu.ativo){
            this.tipo.x = "Computador";
            this.tipo.o = "Jogador";
        }else{
            this.tipo.x = "Jogador";
            this.tipo.o = "Computador";
        }
    }
    ,getJogadorTipo(){
        switch(this.getJogador()){
            case "x" : return this.tipo.x;break;
            case "o" : return this.tipo.o;break;     
            default : return "";
        }
    }
};
let finalizacao = {
    /** vitórias possíveis */
    opcoes : {
         1 : [11,12,13]
        ,2 : [21,22,23]
        ,3 : [31,32,33]    
        ,4 : [11,21,31]
        ,5 : [12,22,32]
        ,6 : [13,23,33]    
        ,7 : [11,22,33]
        ,8 : [13,22,31]
    }
    ,finalizado : false
    ,jogadaVencedora : []
    ,vencedor : ""
    ,velha : false

    ,verificaJogada : function(array){
        if(this.comparacao(array)){
            $("#_msg").removeClass("alert-danger").addClass("alert-success").html(VENCEDOR + jogadorCorrente.getJogadorMarcador());
            this.vencedor = jogadorCorrente.getJogador();
            for(x in this.jogadaVencedora) $("#"+this.jogadaVencedora[x]).addClass("vencedor");
            this.finalizado = true;           
        }else{
            if(jogadas.qtd>=9){
                $("#_msg").removeClass("alert-success").addClass("alert-danger").html(DEU_VELHA);
                $(".jv_cell").addClass("velha");
                this.velha = true;
                this.finalizado = true;
            }
        }

        if(this.finalizado){
            $(".jv_cell").addClass("travado");
            jogadas.registrar();
        }
    }
    ,comparacao : function(array){
        retorno  = false;
        if(array){
            contador = 3;
            for(x in this.opcoes){
                for (var i = 0, l=array.length; i < l; i++) {
                    if(array[i]){
                        if(this.opcoes[x].indexOf(array[i])!==-1) contador--;
                    }
                }
                if(contador<=0){
                    retorno = true;
                    this.jogadaVencedora = this.opcoes[x];
                }
                contador = 3;
            }
        }
        return retorno;
    }
    ,reset : function(){
        this.finalizado = false;
        this.jogadaVencedora = [];
        this.vencedor = "";
        this.velha = false;
        $("#_msg").removeClass("alert-success").removeClass("alert-danger").html("");
        $(".jv_cell").removeClass("travado").removeClass("velha").removeClass("vencedor").html("&nbsp;").removeData("checado");
    }
};
/** CPU */
let cpu = {
    ativo : false
    ,jogadaFoco : []
    ,bloqueios : 0
    ,autoSet : function(){
        this.ativo = !this.ativo
    }
    ,fazerJogada : function(){
        if(!this.ativo) return false;
        
        /** 0 = FACIL;
         *  1 = MEDIO;
         *  2 = DIFICIL; */
        
        switch(dificuldade.valor){
            case 0 : chave = this.processaFacil(); break;
            case 1 : chave = this.processaMedio(); break;
            case 2 : chave = this.processaDificil(); break;
        }

        jogadas.setMovimento($("#"+chave),chave); 

    }
    ,validaJogadaFoco : function(){
        let _j = jogadas.getJogadaInversa( jogadorCorrente.getJogador() ) 
        var arr = this.jogadaFoco.filter( function(elem,index,array){
            return _j.indexOf(elem)>=0;
        });
        return !arr.length;
    }
    ,validarJogada : function(opts){
        let _j = jogadas.getJogadaInversa( jogadorCorrente.getJogador() );
        var arr = opts.filter( function(elem,index,array){
            return _j.indexOf(elem)>=0;
        });
        return !arr.length;
    }
    ,reset : function(){
        this.ativo = false;
        this.jogadaFoco = [];
        this.bloqueios = 0;
        this.iniciaPartida();
    }
    ,bloquear : function(){
        ret = "";
        if(this.bloqueios<dificuldade.getBloqueios()){
            let _j = jogadas.getJogadaInversa( jogadorCorrente.getJogador() );
            for( x in finalizacao.opcoes ){
                opts = finalizacao.opcoes[x].filter(function(elem,index,array){
                    return _j.indexOf(elem) === -1;
                });

                if(opts.length==1){
                    if(jogadas.todas.indexOf(opts[0]) >= 0){
                        this.bloqueios++;
                        ret = opts[0];
                        break;
                    }
                }
            }
        }
        return ret;
    }
    ,processaFacil : function(){
        return jogadas.todas[ Math.floor( (Math.random() * jogadas.todas.length) ) ];
    }
    ,processaMedio : function(){
        _jogadas = jogadas.getJogada( jogadorCorrente.getJogador() );
        var chave    = jogadas.todas[ Math.floor( (Math.random() * jogadas.todas.length) ) ];

        if(k = this.bloquear()) chave = k;        
        else if( this.jogadaFoco.length>0 && this.validaJogadaFoco() ){
            jf = this.jogadaFoco.map( function(elem){ return elem; } );
            for( y in _jogadas ) jf.splice(jf.indexOf(_jogadas[y]),1);
            chave = jf[ Math.floor( (Math.random() * jf.length) ) ]; 

        }else if(_jogadas.length){
            if(k = this.getUmaJogada(_jogadas)) chave = k;
        }

        return chave;
    }
    ,processaDificil : function(){
        _jogadas = jogadas.getJogada( jogadorCorrente.getJogador() );
        var chave    = jogadas.todas[ Math.floor( (Math.random() * jogadas.todas.length) ) ];

        if( Math.floor((Math.random() * 2)) && jogadas.todas.indexOf(22)>=0) chave = 22; /** inicia no centro */

        if(k = this.verificaPosivelVitoria()){
            chave = k;
        }
        else if(k = this.bloquear()){
            chave = k;        
        }
        else if( this.jogadaFoco.length>0 && this.validaJogadaFoco() ){
            jf = this.jogadaFoco.map( function(elem){ return elem; } );
            for( y in _jogadas ) jf.splice(jf.indexOf(_jogadas[y]),1);
            chave = jf[ Math.floor( (Math.random() * jf.length) ) ]; 
        }else if(_jogadas.length){            
            if(k = this.getUmaJogada(_jogadas)){
                chave = k;
            }
        }
        
        return chave;
    }
    ,getUmaJogada : function(_jogadas){
        this.jogadaFoco = [];
        _chaves = [];

        Loop1:
        for( x in finalizacao.opcoes ){
            opt_final = finalizacao.opcoes[x].map( function(elem){ return elem; } );
            _vf = false;
            if(this.validaJogadaFoco()){
                Loop2:
                for( y in _jogadas ){
                    if( opt_final.indexOf(_jogadas[y]) >=0 && this.validarJogada(opt_final) ) opt_final.splice(opt_final.indexOf(_jogadas[y]),1);                            
                }
            }

            if(opt_final.length < 3){
                Loop3:
                for( z in opt_final ){
                    if( jogadas.todas.indexOf(opt_final[z]) >= 0 ) _vf = true;
                }
            }

            if(_vf){
                _chaves = opt_final;
                this.jogadaFoco = finalizacao.opcoes[x].map( function(elem){ return elem; } );
                break Loop1;
            }
        }
        if(_chaves.length) return _chaves[ Math.floor( (Math.random() * _chaves.length) ) ]; 
        
        return false;
    }
    ,verificaPosivelVitoria : function(){
        _jogadas = jogadas.getJogada( jogadorCorrente.getJogador() );
        var ret = "";
        Loop1:
        for( x in finalizacao.opcoes ){
            opt_final = finalizacao.opcoes[x].map( function(elem){ return elem; } );
            Loop2:
            for( y in _jogadas ){
                if( opt_final.indexOf(_jogadas[y]) >=0 && this.validarJogada(opt_final) ) opt_final.splice(opt_final.indexOf(_jogadas[y]),1);                            
            }
            if(opt_final.length == 1){
                ret = opt_final[0];
                break Loop1;
            }
        }
        return ret;
    }
    ,iniciaPartida : function(){
        if( $("input[name='_jogador_set']:checked").val() == "o") this.autoSet();
    }
};


/** functions */
function main(){
    mainloop = setInterval( () => {
        if(finalizacao.finalizado){
            clearInterval(mainloop);
            return false;
        }
        cpu.fazerJogada();
    }, TIME );
}

function reset(){
    cpu.reset();
    jogadas.reset();
    finalizacao.reset();
    dificuldade.setDificuldade();
    jogadorCorrente.reset().setTipo(cpu);
    main();
}

/** INIT */
( () => {
    /** procesa jogada do Jogador */
    $(".jv_cell").bind("click", function(e) {
        /** verifica se é a vez da CPU */
        if(cpu.ativo) return false;        
        el  = $(this);
        /** verifica se o jogo já foi finalizado */
        if(finalizacao.finalizado || el.data("checado")) return false;
        /** monta a chave, registro da celula. Linha/Coluna */
        chave = eval(el.data("row")+""+el.data("col"));
        jogadas.setMovimento(el,chave);
    } );

    $("#jv_nivel").bind("change",function(){
        $("#msg_nivel").html(MSG_INICIAR_PARTIDA);
    });
    $("input[name='_jogador_set']").bind("change",function(){ 
        $("#msg_nivel").html(MSG_INICIAR_PARTIDA);
    });

    reset();

    console.log("Jogo da Velha Carregado!");

} )();