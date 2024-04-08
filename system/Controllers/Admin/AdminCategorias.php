<?php

namespace system\Controllers\Admin;

use system\Model\CategoriaModel;
use system\core\Helpers;

class AdminCategorias extends AdminController
{
    public function listar(): void
    {
        $categoria = new CategoriaModel();

        echo $this->template->renderizar('categorias/listar.html', [
            'categorias' => ($categoria)->busca(null, "status ASC, id DESC"),
            'total' => [
                'total' => $categoria->total(),
                'ativo' => $categoria->total('status = 1'),
                'inativo' => $categoria->total('status = 0')
            ]
        ]);
    }
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new CategoriaModel())->armazenar($dados);
            $this->mensagem->sucesso("Categoria cadastrado com sucesso!")->renderizar();
            $this->mensagem->flash();
            Helpers::redirecionar('admin/categorias/listar');
        }
        echo $this->template->renderizar('categorias/formulario.html', []);
    }

    public function editar(int $id): void
    {
        $categoria = (new CategoriaModel())->buscaPorId($id);
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            (new CategoriaModel())->atualizar($dados, $id);
            $this->mensagem->sucesso("Categoria editada com sucesso!")->renderizar();
            $this->mensagem->flash();
            Helpers::redirecionar('admin/categorias/listar');
        }

        echo $this->template->renderizar('categorias/formulario.html', [
            'categoria' => $categoria
        ]);
    }
    public function deletar(int $id): void
    {
        (new CategoriaModel())->deletar($id);
        $this->mensagem->sucesso("Categoria deletada com sucesso!")->renderizar();
        $this->mensagem->flash();
        Helpers::redirecionar('admin/categorias/listar');
    }
}
