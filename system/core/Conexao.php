<?php

namespace system\core;

use PDO;
use PDOException;
/**
 * Classe de Conexao - Padrão Singleton: retorna uma instância única da classe. 
 * 
 * @author victor <victorlemes@gmail.com>
 */

class Conexao
{
    private static $instancia;

    public static function getInstancia(): PDO
    {
        if (empty(self::$instancia)) {

            try {
                self::$instancia = new PDO('mysql:
host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ]);
            } catch (PDOException $ex) {
                die('Erro de conexão: ' . $ex->getMessage());
            }
            
        }
        return self::$instancia;
    }

    protected function __construct()
    {

    }

    private function __clone(): void 
    {

    }
}
