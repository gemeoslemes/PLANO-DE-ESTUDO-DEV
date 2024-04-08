<?php

namespace system\Model;

use system\core\Conexao;


/**
 * Classe CategoriaModel
 */
class CategoriaModel
{
    
    public function busca(?string $termo = null, string $ordem = null): array
    {   
        $termo = ($termo ? "WHERE {$termo}": '');
        $ordem = ($ordem ? "ORDER BY {$ordem}": '');

        $query = "SELECT * FROM  categorias {$termo} {$ordem}";

        $statement = Conexao::getInstancia()->query($query);

        $result = $statement->fetchAll();

        return $result;
    }

    public function buscaPorId(int $id): bool|object 
    {
        $query = "SELECT * FROM categorias WHERE id = {$id}";
        $statement = Conexao::getInstancia()->query($query);
        $result = $statement->fetch();

        return $result;
    }

    public function posts(int $id): array 
    {
        $query = "SELECT * FROM posts WHERE fk_id_categoria = {$id} AND 
        status = 1 ORDER BY id DESC";

        $statement = Conexao::getInstancia()->query($query);

        $result = $statement->fetchAll();
        
        return $result;
    }

    public function armazenar(array $dados): void 
    {
        $query = "INSERT INTO categorias(`titulo`, `status`) VALUES(?, ?)";
        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute([$dados['titulo'], $dados['status']]);
    }
    
   public function atualizar(array $dados, int $id): void 
   {
        $query = "UPDATE categorias SET titulo = :titulo, `status` = :status
        WHERE id = {$id}";
        
        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute($dados);
   }

   public function deletar(int $id): void 
   {
        $query = "DELETE FROM categorias WHERE id = {$id}";

        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute();
   }

   public function total(?string $termo = null):int 
    {
        $termo = ($termo ? "WHERE {$termo}": '');

        $query = "SELECT * FROM categorias {$termo}";
        $statement = Conexao::getInstancia()->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }
}