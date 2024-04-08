<?php 
    //Arquivo de configuração;

    //Define o fuso horário;
    date_default_timezone_set("America/Sao_Paulo");
    define('DB_HOST', 'localhost');
    define('DB_PORT', '3306');
    define('DB_NAME', 'blog');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'Vl102030@');

    define('SITE_NOME', 'VLBlog');
    define('SITE_DESC', 'VLBlog - tecnologia');

    define('URL_PRODUCAO', 'http://vlblog.com.br');
    define('URL_DESENVOLVIMENTO', 'http://localhost/treinamento');

    define('URL_SITE', 'treinamento/');
    define('URL_ADMIN', 'treinamento/admin/');