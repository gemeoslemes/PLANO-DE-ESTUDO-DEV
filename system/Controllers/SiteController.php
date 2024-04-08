<?php

namespace system\Controllers;

use system\core\Controller;

use system\Model\CategoriaModel;
use system\Model\PostModel;
use system\core\Helpers;

class SiteController extends Controller
{
    public function __construct()
    {
        parent::__construct('templates/site/views/');
    }

    public function index(): void
    {
        $posts = (new PostModel())->busca(null, 'rand()');
        echo $this->template->renderizar('index.html', [
            'posts' => $posts,
            'categorias' => $this->categorias(),

        ]);
    }


    public function post(int $id): void
    {
        $post = (new PostModel())->buscaPorId($id);
        if (!$post) {
            Helpers::redirecionar('404');
        }
        echo $this->template->renderizar('post.html', [
            'post' => $post,
            'categorias' => $this->categorias(),
        ]);
    }

    public function categoria(int $id): void {
        $posts = (new CategoriaModel())->posts($id);
        
        echo $this->template->renderizar('categoria.html', [
            'posts'=> $posts,
            'categorias' => $this->categorias(),
        ]);
    }

    public function buscar(): void 
    {
        $busca = filter_input(INPUT_POST, 'busca', FILTER_DEFAULT);
        if (isset($busca)) {
            $posts = (new PostModel())->pesquisa($busca);
            foreach ($posts as $post) {
                echo "<li class='list-group-item fw-bold'><a class='text-decoration-none text-dark' href=".Helpers::url('post/')
                .$post->id.">$post->titulo</a></li>";
            }
        }
    }
    

    public function categorias()
    {
        return (new CategoriaModel())->busca();
    }

    public function error404(): void
    {
        echo $this->template->renderizar('error404.html', [
            'titulo' => 'Página não encontrada'
        ]);
    }
}
