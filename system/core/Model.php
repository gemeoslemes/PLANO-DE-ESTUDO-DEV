<?php

namespace system\core;

use PDOException;
use system\core\Conexao;
use system\core\Mensagem;

use function PHPSTORM_META\type;

class Model
{
    protected $dados;
    protected $query;
    protected $erro;
    protected $parametros;
    protected $tabela;
    protected $ordem;
    protected $limite;
    protected $offSet;
    protected $mensagem;

    public function __construct(string $tabela)
    {
        $this->tabela = $tabela;
        $this->mensagem = new Mensagem();
    }


    public function ordem(string $ordem)
    {
        $this->ordem = " ORDER BY {$ordem}";
        return $this;
    }

    public function limite(string $limite)
    {
        $this->limite = " LIMIT {$limite}";
        return $this;
    }

    public function offSet(string $offSet)
    {
        $this->offSet = " OFFSET {$offSet}";
        return $this;
    }

    public function erro()
    {
        return $this->erro;
    }

    public function mensagem()
    {
        return $this->mensagem;
    }

    public function __set($nome, $valor)
    {
        if (empty($this->dados)) {
            $this->dados = new \stdClass();
        }

        $this->dados->$nome = $valor;
    }

    public function busca(
        ?string $termos = null,
        ?string $parametros = null,
        string $colunas = '*'
    ) {
        if ($termos) {
            $this->query = "SELECT {$colunas} FROM " . $this->tabela
                . " WHERE {$termos} ";
            parse_str($parametros, $this->parametros);
            return $this;
        }
        $this->query = "SELECT {$colunas} FROM " . $this->tabela;
        return $this;
    }

    public function resultado(bool $todos = false)
    {
        try {

            $statement = Conexao::getInstancia()->prepare(
                $this->query . $this->ordem . $this->limite . $this->offSet
            );
            $statement->execute($this->parametros);

            if (!$statement->rowCount()) {
                return null;
            }

            if ($todos) {
                return $statement->fetchAll();
            }

            return $statement->fetchObject();
        } catch (PDOException $ex) {
            echo $this->erro = $ex;
            return null;
        }
    }

    protected function cadastrar(array $dados)
    {
        try {

            $colunas = implode(',', array_keys($dados));
            $valores = implode(',:', array_keys($dados));
            $valores = ':' . $valores;


            $query = "INSERT INTO " . $this->tabela . " ({$colunas}) VALUES({$valores})";
            $statement = Conexao::getInstancia()->prepare($query);
            $statement->execute($dados);

            return Conexao::getInstancia()->lastInsertId();
        } catch (PDOException $ex) {
            echo $this->erro = $ex;
            var_dump($this->erro);
            return null;
        }
    }

    public function atualizar(array $dados, string $termos)
    {
        try {
            $set = [];

            foreach ($dados as $chave => $valor) {
                $set[] = "{$chave} = :{$chave}";
            }
            $setString = implode(",", $set);

            $query = "UPDATE " . $this->tabela . " SET {$setString} WHERE {$termos}";
            $statement = Conexao::getInstancia()->prepare($query);
            // Bind dos valores dos dados
            foreach ($dados as $chave => $valor) {
                $statement->bindValue(":{$chave}", $valor);
            }

            $statement->execute($this->filtro($dados));


            return $statement->rowCount();
        } catch (PDOException $ex) {
            echo $this->erro = $ex;
            return null;
        }
    }

    private function filtro(array $dados)
    {
        $filtro = [];
        foreach ($dados as $chave => $valor) {
            $filtro[$chave] = (is_null($valor) ? null : filter_var($valor, FILTER_DEFAULT));
        }
        return $filtro;
    }

    protected function armazenar()
    {
        $dados = (array) $this->dados;

        return $dados;
    }

    public function salvar()
    {
        if (!empty($this->dados)) {
            $this->cadastrar($this->armazenar());

            if ($this->erro()) {
                $this->mensagem->erro('Erro de sistema ao tentar cadastrar os dados!');
                return false;
            }
        }

        return true;
    }
}
