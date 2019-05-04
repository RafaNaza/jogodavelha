<?php
    function query($sql){
        try{
            switch(dbconection::$conntype){
                case "pdo" : 
                    $sth = dbconection::getInstance()->prepare($sql);
                    $sth->execute();
                    return $sth;
                break;
                case "mysql" :  return dbconection::getInstance()->query($sql); break;
                case "postgresql" :  return pg_query ( dbconection::getInstance() , $sql ); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("QUERY ERROR : ".$e->getMessage());
        }
    }

    function fetch($sth){/* Return Object(s) */
        try{
            switch(dbconection::$conntype){
                case "pdo" : return $sth->fetch(PDO::FETCH_OBJ); break;
                case "mysql" : return $sth->fetch_object(); break;
                case "postgresql" : return pg_fetch_object ( $sth ); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("FETCH ERROR : ".$e->getMessage());
        }
    }
    
    function result($sth){/* Return Array(s)*/
        try{
            switch(dbconection::$conntype){
                case "pdo" : return $sth->fetch(PDO::FETCH_ASSOC); break;
                case "mysql" : return $sth->fetch_assoc(); break;
                case "postgresql" : return pg_fetch_array ( $sth ); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("RESULT ERROR : ".$e->getMessage());
        }
    }
    
    function rows($sth){/* Return Numero de Linhas consultadas */
        try{
            switch(dbconection::$conntype){
                case "pdo" : return $sth->rowCount(); break;
                case "mysql" : return mysql_num_rows($sth); break;
                case "postgresql" : return pg_num_rows ( $sth ); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("ROWS ERROR : ".$e->getMessage());
        }
    }

    function num_fields($sth){/* Return Numero de colunas */
        try{
            switch(dbconection::$conntype){
                case "pdo" : return $sth->columnCount(); break;
                case "mysql" : return mysqli_num_fields($sth); break;
                case "postgresql" : return pg_num_fields($sth); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("NUM FIELDS ERROR : ".$e->getMessage());
        }
    }
   
    function field_name($sth,$index){/* Return Nome da colunas */
        try{
            switch(dbconection::$conntype){
                case "pdo" : return $sth->getColumnMeta($index)["name"]; break;
                case "mysql" : return mysqli_fetch_field_direct($sth,$index)->name; break;
                case "postgresql" : return pg_field_name($sth,$index); break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("NAME FIELDS ERROR : ".$e->getMessage());
        }
    }

    function last_inserted_id($pg_resource=""){/** Retorno o id do ultimo registro adicionado */
        try{
            switch(dbconection::$conntype){
                case "pdo" :
                    return  dbconection::getInstance()->lastInsertId();
                break;
                case "mysql" : return  mysqli_insert_id(dbconection::getInstance()); 
                break;
                case "postgresql" : return  pg_last_oid ( $pg_resource ); 
                break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("QUERY ERROR : ".$e->getMessage());
        }
    }

    function results($sql)
    {
        $return = array();
        $query = query($sql);
        while( $fetch = fetch($query) )
            $return[sizeof($return)] = $fetch;

        return $return;
    }

    function db_quote($string)
    {
        try{
            switch(dbconection::$conntype){
                case "pdo" :
                    return  addslashes($string);
                break;
                case "mysql" : return  mysql_real_escape_string($string); 
                break;
                case "postgresql" : return  pg_escape_string($pg_resource); 
                break;
                default : throw new Exception("Nenhum tipo de conexão definida.");
            }
        }catch(Exception $e){
            printr("QUERY ERROR (Quote) : ".$e->getMessage());
        }
    }