<?php

class urlamigavel {

    public $target = 'index';// Nome do metodo que sera chamado na classe de controle
    public $arg1;
    public $arg2;
    public $arg3;
    public $arg4;
    public $arg5;// Parametros que serao passados
    public $exceptions_path = ['index.php'];

    public function __construct($controle){
        // Resgata o PATH_INFO
        $this->set_target_request($this->request_url());

        $target = $this->target;
        $controle->$target($this->arg1, $this->arg2, $this->arg3, $this->arg4, $this->arg5);
      
    }

   private function request_url(){
		if(array_key_exists('REQUEST_URI', $_SERVER) ){
			$uri = trim($_SERVER['REQUEST_URI']);
			if(!empty($uri)){
				$uri = '/' . ltrim($uri, '/');
				return $uri;
			}
		}
		if(array_key_exists('PATH_INFO', $_SERVER) ){
			$uri = trim($_SERVER['PATH_INFO']);
			if(!empty($uri)){
				$uri = '/' . ltrim($uri, '/');
				return $uri;
			}
		}
		if(array_key_exists('ORIG_PATH_INFO', $_SERVER) ){
			$uri = trim($_SERVER['ORIG_PATH_INFO']);
			if(!empty($uri)){
				$uri = '/' . ltrim($uri, '/');
				return $uri;
			}
		}
		
		$uri = trim($_SERVER['SCRIPT_NAME']);
		if(!empty($uri)){
			$uri = '/' . ltrim($uri, '/');
			return $uri;
		}
		
		return "";
	}

    private function set_target_request($request_url){        
        try{
            $request_url = str_replace(PATH_SITE,"/",$request_url);

            $pos = 0;$len=0;
            foreach($this->exceptions_path as $key=>$value){
                $pos = strrpos($request_url,$value);
                if($pos){
                    $len=strlen($value);
                    break;
                }
            }
            $sub = substr($request_url, $pos, $len);

            $params = array();            
            $path_info = explode('/', str_replace($sub,"",$request_url));
            foreach($path_info as $path){
                if(!in_array($path,$this->exceptions_path)){
                    if(trim($path)) $params[] = $path;
                }
            }
            
            if(array_key_exists(0, $params)){
                $this->target = $params[0];
            }
            for($i=1;$i<=5;$i++){
                if(array_key_exists($i, $params)){
                    $var = "arg{$i}";
                    $this->$var = $params[$i];
                }
            }
        }catch(Exception $e){
            printr($e->getMessage());
        }
    }
}
