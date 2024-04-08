<?php

namespace system\core;

use system\core\Sessao;

/**
 * Classe para gerenciar mensagens de sucesso e alerta.
 */
class Mensagem
{
    /**
     * @var string O texto da mensagem.
     */
    private $texto;

    /**
     * @var string O estilo CSS da mensagem.
     */
    private $css;

    /**
     * Converte a mensagem em uma string quando utilizada em contexto de string.
     *
     * @return string A representação da mensagem.
     */
    public function __toString()
    {
        return $this->renderizar();
    }

    /**
     * Define a mensagem como uma mensagem de sucesso.
     *
     * @param string $mensagem O texto da mensagem de sucesso.
     * @return Mensagem A instância atual da mensagem.
     */
    public function sucesso(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-success alert-dismissible';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Define a mensagem como uma mensagem de alerta.
     *
     * @param string $mensagem O texto da mensagem de alerta.
     * @return Mensagem A instância atual da mensagem.
     */
    public function alerta(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-warning alert-dismissible';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    public function erro(string $mensagem): Mensagem
    {
        $this->css = 'alert alert-danger alert-dismissible';
        $this->texto = $this->filtrar($mensagem);
        return $this;
    }

    /**
     * Renderiza a mensagem como HTML.
     *
     * @return string A representação HTML da mensagem.
     */
    public function renderizar(): string
    {
        return "<div class='{$this->css}' role='alert'>
                    {$this->texto}
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
    }

    /**
     * Filtra o texto da mensagem para evitar XSS.
     *
     * @param string $mensagem O texto da mensagem a ser filtrado.
     * @return string O texto filtrado.
     */
    private function filtrar(string $mensagem): string
    {
        return filter_var($mensagem, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Armazena a mensagem na sessão para exibição temporária.
     */
    public function flash(): void
    {
        (new Sessao())->criar('flash', $this);
    }
}
