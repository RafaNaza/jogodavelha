<?php
    header('Content-Type: text/html; charset=utf-8');
    date_default_timezone_set('America/Sao_Paulo');
    error_reporting( 0 );

    define("PATH_SITE","/jogodavelha/");
    define("INDEX",PATH_SITE."index.php/");

    /** */
    define("SYSTEM","system/");
    define("CLASS_PATH",SYSTEM."class/");
    define("MODEL",SYSTEM."model/");
    define("TOOLS",SYSTEM."tools/");

    /**data base */
    define("DB_HOST","localhost");
    define("DB_USER","user");
    define("DB_PASS","Dev1_pw1");
    define("DB_NAME","jogodavelha");
    /** */

    include TOOLS."functions.php";
    include TOOLS."autoload.php";
    include TOOLS."dbfunction.php";
    include TOOLS."conexao.php";