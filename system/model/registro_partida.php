<?php
class registro_partida extends base{
    var $id;
    var $jogador_vencedor;
    var $jogadas_x;/** */
    var $jogadas_o;/** */
    var $jogada_vencedora;/** */
    var $marcador_vencedor;
    var $quantidade_jogadas;
    var $velha;
    var $dificuldade;
    var $data_cadastro;

    public function salva(){
        if(is_array($this->jogadas_x)) $this->jogadas_x = json_encode($this->jogadas_x);
        if(is_array($this->jogadas_o)) $this->jogadas_o = json_encode($this->jogadas_o);
        if(is_array($this->jogada_vencedora)) $this->jogada_vencedora = json_encode($this->jogada_vencedora);

        return parent::salva();
    }

    public function getJogadasX(){
        if(is_array($this->jogadas_x)) return $this->jogadas_x;
        return json_decode($this->jogadas_x);
    }
    public function getJogadasO(){
        if(is_array($this->jogadas_o)) return $this->jogadas_o;
        return json_decode($this->jogadas_o);
    }
    public function getJogadaVencedora(){
        if(is_array($this->jogada_vencedora)) return $this->jogada_vencedora;
        return json_decode($this->jogada_vencedora);
    }

    public function getDataCadastroFormatada(){
        return formata_datahora_br($this->data_cadastro);
    }
    public function getJogador(){
        if(!$this->jogador_vencedor) return "---";        
        return $this->jogador_vencedor." / ".$this->getMarcador()." ";
    }
    private function getMarcador($m=""){
        if(!$m) $m = $this->marcador_vencedor;
        $marcador = "";
        switch($m){
            case "x" : $marcador = '<i class="fas fa-times"></i>';break;
            case "o" : $marcador = '<i class="far fa-circle"></i>';break;     
        }
        return $marcador;
    }
    public function getJogo(){
        $ret = "";
        $ret .= "<table>";

            $ret .= "<tr>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(11)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(12)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(13)."</td>";
            $ret .= "</tr>";

            $ret .= "<tr>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(21)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(22)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(23)."</td>";
            $ret .= "</tr>";

            $ret .= "<tr>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(31)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(32)."</td>";
                $ret .= "<td style='font-size:8px;'>".$this->getJogada(33)."</td>";
            $ret .= "</tr>";

        $ret .= "</table>";

        return $ret;
    }

    private function getJogada($key){
        if(in_array( $key,$this->getJogadasX() ) ) return $this->getMarcador("x");
        if(in_array( $key,$this->getJogadasO() ) ) return $this->getMarcador("o");
        return "&nbsp;";
    }
}