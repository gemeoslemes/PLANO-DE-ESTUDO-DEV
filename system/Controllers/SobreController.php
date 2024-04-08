<?php

namespace system\Controllers;
use system\core\Controller;

class SobreController extends Controller
{
    public function __construct() {
        parent::__construct('templates\site\views');
    }
    public function sobre(): void {
        echo $this->template->renderizar('sobre.html', [
            'titulo' => 'Sobre-nós',
            'subtitulo' => 'Teste sobre-nós'
        ]);
    }
}