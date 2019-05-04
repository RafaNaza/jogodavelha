<?php
     function sys_autoload($class){
        $paths = [
            CLASS_PATH . $class .".php"
            ,MODEL . $class .".php"
        ];

        $has = false;
        foreach($paths as $file){
            if(file_exists($file)){
                require_once($file) ;
                $has = true;
            }
        }

        if(!$has) printr('Não encontrado:<Br>'.join('<br>', $paths), true);
    }
    spl_autoload_register('sys_autoload');