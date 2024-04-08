<?php

namespace system\core;

use Exception;
use system\core\Sessao;

class Helpers
{
    public static function flash(): ?string 
    {
        $sessao = new Sessao();
        if($flash = $sessao->flash()) {
            echo $flash;
        }
        return null;
    }

    public static function redirecionar(string $url = null): void
    {
        header('HTTP/1.1 302 Found');
        $local = ($url ? self::url($url) : self::url());

        header("Location: {$local} ");
        exit();
    }

    /**
     * Validar um número de CPF
     * @param string $cpf 
     * @return bool
     */
    public static function validarCpf(string $cpf): bool
    {
        $cpf = self::limparNumero($cpf);

        if (mb_strlen($cpf) != 11) {
            throw new Exception('O CPF precisa ter 11 dígitos');
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            throw new Exception('CPF inválido, todos os números iguais');
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += $cpf[$i] * (10 - $i);
        }

        $remainder = $sum % 11;
        $digit1 = ($remainder < 2) ? 0 : 11 - $remainder;
 
        if ($cpf[9] != $digit1) {
            throw new Exception('CPF inválido');
        }

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += $cpf[$i] * (11 - $i);
        }

        $remainder = $sum % 11;
        $digit2 = ($remainder < 2) ? 0 : 11 - $remainder;

        if ($cpf[10] != $digit2) {
            throw new Exception('CPF inválido');
        }

        return true;
    }

    public static function limparNumero(string $numero): string
    {
        return preg_replace('/[^\d]/', '', $numero);
    }


    public static function dataAtual(): string
    {
        $dia = date('d');
        $mes = date('n') - 1;
        $diaDaSemana = date('w');
        $ano = date('n') - 1;

        $nomeDosDias = [
            'domingo', 'segunda-feira', 'terça-feira', 'quarta-feira',
            'quinta-feira', 'sexta-feira', 'sábado'
        ];

        $meses = [
            'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
            'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'desembro'
        ];

        $dataFormatada = $nomeDosDias[$diaDaSemana] . ' ' . $dia . ' de ' . $meses[$mes];

        return $dataFormatada;
    }
    /**
     * <b>Função url():</b> Monta a url de acordo com o ambiente
     * @param string $url - parte da url exemplo: <b>.admin</b>
     * @return string - retorna a url completa
     */
    public static function url(string $url = null): string
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO
            : URL_PRODUCAO);

        if (str_starts_with($url, '/')) {
            return $ambiente . $url;
        }
        return $ambiente . '/' . $url;
    }

    /**
     * Trazendo o localhost e verificando 
     * @return bool - true se o servidor for igual a localhost, caso contrário false
     */
    public static function localhost(): bool
    {
        $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

        if ($servidor == 'localhost') {
            return true;
        }
        return false;
    }

    /**
     * Validação de e-mail
     * @param string $email - passando um email
     * @return bool Caso o email seja válido será retornado true caso contrário false
     */
    public static function validarEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    public static function validarUrl(string $url): bool
    {
        if (mb_strlen($url) < 10) {
            return false;
        } elseif (!str_contains($url, '.')) {
            return false;
        } elseif (str_contains($url, 'http://') or str_contains($url, 'https://')) {
            return true;
        }
        return false;
    }

    /**
     * Função de validar url
     * @param string $url - passando como atributo uma url
     * @return bool caso a url seja válida será retornado true caso contrário false
     */
    public static function validarUrlComFiltro(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * Função que conta o tempo 
     * @param  string $data - passando uma data com ano-mês-dia hora:minutos:segundos
     * @return string data
     */
    public static function contarTempo(string $data)
    {
        $agora = strtotime(date("Y-m-d H:i:s"));
        $tempo = strtotime($data);
        $diferenca = $agora - $tempo;

        $segundos = $diferenca;
        $minutos = round($diferenca / 60);
        $horas = round($diferenca / 3600);
        $dias = round($diferenca / 3600);
        $semanas = round($diferenca / 604800);
        $meses = round($diferenca / 2419200);
        $anos = round($diferenca / 29030400);

        if ($segundos <= 60) {
            return "agora";
        } elseif ($minutos <= 60) {
            return $minutos == 1 ? "há 1 minuto" : "há " . $minutos . " minutos";
        } else if ($horas <= 24) {
            return $horas == 1 ? "há 1 hora" : "há " . $horas . " horas";
        } else if ($dias <= 7) {
            return $dias == 1 ? "há 1 dia" : "há " . $dias . " dias";
        } elseif ($semanas <= 4) {
            return $semanas == 1 ? "há 1 semana" : "há " . $semanas . " semanas";
        } elseif ($meses <= 12) {
            return $meses == 1 ? "há 1 mês" : "há " . $meses . " meses";
        } else {
            return $anos == 1 ? "1 ano" : "há " . $anos . " anos";
        }
    }


    /**
     * Formatar um valor com ponto e virgula.
     * @param float $valor - valor.
     * @return string valor
     */
    public static function formatarValor(float $valor = null): string
    {
        return number_format($valor ? $valor : 0, 2, ",", ".");
    }


    /**
     * Formata número com pontos flutuantes 
     * @param int $numero - atributo que pode ou não ser passado.
     * @return string número 
     */
    public static function formatarNumero(int $numero = null): string
    {
        return "R$ " . number_format($numero ?: 0, 0, ".", ".");
    }

    public static function saudacao(): string
    {

        $hora = date('H');
        $saudacao = "";

        /*  if ($hora >= 0 and $hora <= 5) {
        $saudacao = "Boa madrugada";
    } elseif ($hora >= 6 and $hora <= 12) {
        $saudacao = "boa dia";
    } elseif ($hora >= 13 and $hora <= 18) {
        $saudacao = "boa Tarde";
    } else {
        $saudacao = "Boa Noite";
    } */
        switch ($hora) {
            case $hora >= 0 and $hora <= 5:
                $saudacao = "Boa madrugada";
                break;
            case $hora >= 6 and $hora <= 12:
                $saudacao = "boa dia";
                break;
            case $hora >= 13 and $hora <= 18:
                $saudacao = "boa Tarde";
                break;
            default:
                $saudacao = "Boa Noite";
        }

        /*  $saudacao = match(true) {
        $hora >= 0 && $hora <= 5 => 'Boa madrugada',
        $hora >= 6 && $hora <= 12 => 'Bom dia',
        $hora >= 13 && $hora <= 18 => 'Boa tarde',
        default => 'Boa tarde'
    }; */

        return $saudacao;
    }
    /**
     * public static function de resumir texto
     * @param string $texto - texto para ser resumido
     * @param int $limite - o limite de caraceteres a serem retornados
     * @param string $continue - caso o limite que foi passado como parametro seja menor que
     * o tamanho do texto, sera atribuido com "..."
     * @return string texto resumido 
     */
    public static function resumirTexto(string $texto, int $limite, $continue = "..."): string
    {
        $textoLimpo = trim(strip_tags($texto));
        if (mb_strlen($textoLimpo) <= $limite) {
            return $textoLimpo;
        }
        $resumirTexto = mb_substr(
            $textoLimpo,
            0,
            mb_strrpos(mb_substr($textoLimpo, 0, $limite), '')
        );

        return $resumirTexto . $continue;
    }
    /*  */
}
