<?php
    function printr($value="")
    {
        echo "<pre style='color:#FFF;background-color:#444;padding:10px;text-align:left;font-size:14px;'>";
        print_r($value);
        echo "</pre>";
    }

    function setVars(&$t)
    {
        if($t->exists("path")) $t->path = PATH_SITE;
        if($t->exists("index")) $t->index = INDEX;
        if($t->exists("nocache")) $t->nocache = date("His");
    }

    function bd_now(){
        return date('Y-m-d H:i:s');
    }

    function formata_datahora_br($datetime){
        if( preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/', $datetime, $matches) ){
            list(,$ano,$mes,$dia,$hora,$minuto,$segundo)=$matches;
            
            if($hora == "00" 
            && $minuto == "00" 
            && $segundo == "00" ){
                return $dia.'/'.$mes.'/'.$ano;
            }
            
            return $dia.'/'.$mes.'/'.$ano .' ' .$hora.':'.$minuto.':'.$segundo	;
        }
        return '';
    }
    