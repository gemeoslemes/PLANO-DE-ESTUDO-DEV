<?php

namespace system\Model;

use system\core\Conexao;
use system\core\Model;

/**
 *  Classe PostModel
 * @author Victor Lemes  
 */
class PostModel extends Model
{
    const TABELA = 'posts';
   

    public function __construct() {
        parent::__construct('posts');
    }


    public function buscaPorId(int $id): bool|object 
    {
        $query = "SELECT * FROM ".self::TABELA." WHERE id = {$id}";
        $statement = Conexao::getInstancia()->query($query);
        $result = $statement->fetch();

        return $result;
    }

    public function pesquisa(string $texto): array
    {

        $query = "SELECT * FROM ".self::TABELA." WHERE status = 1 AND titulo LIKE '%{$texto}%'";

        $statement = Conexao::getInstancia()->query($query);

        $result = $statement->fetchAll();

        return $result;
    }

  /*   public function armazenar(array $dados):void
    {
        $query = "INSERT INTO ".self::TABELA." ( `titulo`, `texto`, `status`, `fk_id_categoria`)
         VALUES (:titulo, :texto, :status, :fk_id_categoria)";

        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute($dados);
        
    }

    public function atualizar(array $dados, int $id):void 
    {
        $query = "UPDATE posts SET titulo = :titulo, `status` = :status, 
        texto = :texto, fk_id_categoria = :fk_id_categoria WHERE id = {$id} ";

        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute($dados);
    } */

    public function deletar(int $id): void 
    {
        $query = "DELETE FROM posts WHERE id = {$id}";

        $statement = Conexao::getInstancia()->query($query);
        $statement->execute();
    }

    public function total(?string $termo = null):int 
    {
        $termo = ($termo ? "WHERE {$termo}" : '');

        $query = "SELECT * FROM posts {$termo}";
        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }
}
