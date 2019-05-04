<?php
/**
 * PHP Version 7.2.17
 * Autor     : Rafael Nazario de Farias
 * Descrição : Jogo da Velha
 * Data      : 29/04/2019
 * Versão    : 1.0 
 */

 
 require "config.php";

 class site{
    public function index(){
        $t = new template("view/base.html"); 
        $t->addFile("MIOLO","view/home.html");
        setVars($t);
        $t->show();
    }

    public function registrar(){
        $data = $_POST["data"];
        $data = json_decode($data,true);

        $registro_partida = new registro_partida();
        $registro_partida->load_by_array($data);

        if($registro_partida->salva()) echo "Registro Realizado com sucesso.";
        else echo "Erro ao tentar realizar o registro";
        die();
    }

    public function historico(){
        $t = new template("view/base.html"); 
        $t->addFile("MIOLO","view/historico.html");

        $query = query("SELECT * FROM registro_partida");
        while($fetch=fetch($query)){
            $registro_partida = new registro_partida();
            $registro_partida->load_by_fetch($fetch);
            $t->registro = $registro_partida;
            $t->block("BLOCK_REGISTROS");
            unset($registro_partida);
        }

        setVars($t);
        $t->show();
    }
 }
 new urlamigavel(new site());