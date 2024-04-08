<?php

namespace system\core;

use system\core\Mensagem;

/**
 * Classe Sessao
 * @author Victor Lemes <ceoVblog@gmail.com>
 */
class Sessao
{
    public function __construct() {
        if(!session_id()) {
            session_start();
        }
    }
    /**
     * @method mixed criar()
     * @param mixed $name
     * @param string $chave
     * Criando sessão 
     * @return Sessao
     */
    public function criar(string $chave, mixed $valor): Sessao
    {
       $_SESSION[$chave] = (is_array($valor) ? (object) $valor : $valor);
       return $this;
    }

     /**
     * @method mixed limpar()
     * @param string $chave
     * Limpa a sessão 
     * @return Sessao
     */
    public function limpar(string $chave): Sessao 
    {  
        unset($_SESSION[$chave]);
        return $this;
    }
    
     /**
     * @method mixed carregar()
     * @param string $chave
     * Careegar a sessão
     * @return object|null 
     */
    public function carregar(): ?object 
    {
        return (object) $_SESSION;
    }

    
     /**
     * @method mixed checar()
     * @param string $chave
     * Checa se a sessão existe
     * @return bool
     */
    public function checar(string $chave): bool 
    {   
        return isset($_SESSION[$chave]);
    }
    
     /**
     * @method mixed deletar()
     * Destroi uma sessão 
     * @return Sessao
     */
    public function deletar(): Sessao 
    {
        session_destroy();
        return $this;
    }

    public function __get($atributo) {
        if(!empty($_SESSION[$atributo])) {
            return $_SESSION[$atributo];
        }
    }

    public function flash(): ?Mensagem
    {
        if($this->checar('flash')) {
            $flash = $this->flash;
            $this->limpar('flash');
            return $flash;
        }
        return null;
    }
}