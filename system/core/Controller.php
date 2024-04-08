<?php 

namespace system\core;

use system\core\Mensagem;
use system\Suport\Template;

class Controller {

    protected Template $template;
    protected Mensagem $mensagem;
    public function __construct(string $diretorio) {
        $this->template = new Template($diretorio);
        $this->mensagem = new Mensagem();
    }

}


