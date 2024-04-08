<?php

namespace system\Controllers\Admin;

use system\core\Helpers;
use system\Model\PostModel;
use system\Model\CategoriaModel;

class AdminPosts extends AdminController
{
    public function listar(): void
    {
        $post = new PostModel();
        echo $this->template->renderizar('posts/listar.html', [
            'posts' => $post->busca()->ordem('status ASC, id DESC')->resultado(true),
            'total' => [
                'total' => $post->total(),
                'ativo' => $post->total('status = 1'),
                'inativo' => $post->total('status = 0')
            ]
        ]);
    }
    public function cadastrar(): void
    {
        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($dados)) {
            $post = new PostModel();

            $post->titulo = $dados['titulo'];
            $post->texto = $dados['texto'];
            $post->status = $dados['status'];
            $post->fk_id_categoria = $dados['fk_id_categoria'];

            if ($post->salvar()) {
                $this->mensagem->sucesso('Post Cadastrado com sucesso!')->renderizar();
                $this->mensagem->flash();
                Helpers::redirecionar('admin/posts/listar');
            }
        }
        echo $this->template->renderizar('posts/formulario.html', [
            'categorias' => (new CategoriaModel())->busca()
        ]);
    }
    

    public function editar(int $id): void
    {
        $post = (new PostModel())->buscaPorId($id);

        $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (isset($dados)) {
            $condicao = "id = {$id}";
            (new PostModel())->atualizar($dados, $condicao);
            $this->mensagem->sucesso("Post editado com sucesso!")->renderizar();
            $this->mensagem->flash();
            Helpers::redirecionar('admin/posts/listar');
        }

        echo $this->template->renderizar('posts/formulario.html', [
            'post' => $post,
            'categorias' => (new CategoriaModel())->busca()
        ]);
    }

    public function deletar(int $id): void
    {
        (new PostModel())->deletar($id);
        $this->mensagem->sucesso("Post deletado com sucesso!")->renderizar();
        $this->mensagem->flash();
        Helpers::redirecionar('admin/posts/listar');
    }
}
