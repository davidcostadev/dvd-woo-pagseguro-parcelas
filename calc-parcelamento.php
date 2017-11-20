<?php

/**
 *
 * @since 		1.0.0
 * @author 		David Costa <davidcostadev@gmail.com>
 * @version 	1.0.0
 */



if(!defined('ABSPATH')){
    exit;
}

if ( ! class_exists( 'CalcParcelamento' ) ) :

final class CalcParcelamento
{
    /**
     * 0 - Mercado Livre
     * 1 - Marcedo password_get_info
     * 2 - Sem Juros
     */
    public $fator = 'pagseguro_livre';
    public $valor_minimo = 10;

    public $pagseguro = [
        '1' => 1,
        '2' => 1.0451,
        '3' => 1.0604,
        '4' => 1.0759,
        '5' => 1.0915,
        '6' => 1.1072,
        '7' => 1.1231,
        '8' => 1.1392,
        '9' => 1.1554,
        '10' => 1.1717,
        '11' => 1.1882,
        '12' => 1.2048,
        // '13' => 1.1231,
        // '14' => 1.1392,
        // '15' => 1.1554,
        // '16' => 1.1717,
        // '17' => 1.1882,
        // '18' => 1.2048,

    ];

    public $parcela_semjuros = 6;

    protected $type = 'pagseguro';

    protected static $_instance  = null;

    // $Calc = self::instance();

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setMinimo($minimo) {
        $this->valor_minimo = $minimo;
        return $this;
    }

    public function calc($valor) {
        if($valor == 0) {
            return '';
        }

        $fators = $this->getFator();

        $last = '';
        foreach ($fators as $divisao => $fator) {
            // $fator_refinado  = ($fator * 0.01) + 1;
            $total_parcelado = $valor * $fator;
            $parcela         = $total_parcelado / $divisao;

            if($parcela < $this->valor_minimo) break;
            if($divisao > 6) break;

            $semJurusText = $fator == 0 ? ' SEM JUROS' : '';

            $last =  'em <strong>'. $divisao .'x de '.$this->floatToReal($parcela).'</strong>'.$semJurusText;
        }

        return $last;
    }

    public function getMetade($valor) {
        $parcelas = [];

        $fators = $this->getFator();

        foreach ($fators as $divisao => $fator) {
            $fator_refinado  = ($fator * 0.01) + 1;
            $total_parcelado = $valor * $fator_refinado;
            $parcela         = $total_parcelado / $divisao;

            if($parcela < $this->valor_minimo) break;

            $parcelas[] = $parcela;
        }


        // echo '<pre>';
        // echo  ceil(count($parcelas) / 2);
        // print_r($parcelas);
        // echo '</pre>';
        return ceil(count($parcelas) / 2);
    }

    public function parcela_table($valor) {

        if($valor == 0) {
            return '';
        }


        $parc = 147.13;
        $total = 1029.90;

        $calc = ($total - 1000) / 1000;

        // echo $calc . '</br>';

        $this->getMetade($valor);

        $fators = $this->getFator();

        $html = '';

        $metade = $this->getMetade($valor);


        $metade_one = [];
        $metade_two = [];

        // echo $metade . '<br>';

        foreach ($fators as $divisao => $fator) {
            $fator_refinado  = $fator;
            $parcela = $valor * $fator_refinado / $divisao;
            // $parcela         = $total_parcelado / $divisao;
            //  echo $divisao .'-'.$fator_refinado.'-'.$parcela . '</br>';

            if($parcela * $divisao < $this->valor_minimo) break;
            $semJurusText = $fator === 1 ? ' <strong>SEM JUROS</strong>' : '';

            if($divisao  <= $metade) {
                $metade_one[] =  '<div class="parcela"><strong>'. $divisao .'x de '.$this->floatToReal($parcela).'</strong>'.$semJurusText.'</div>';
            } else {
                $metade_two[] =  '<div class="parcela"><strong>'. $divisao .'x de '.$this->floatToReal($parcela).'</strong>'.$semJurusText.'</div>';
            }
        }

        $html .= '<div class="col-xs-6">'. implode('', $metade_one) .'</div>';
        $html .= '<div class="col-xs-6">'. implode('', $metade_two) .'</div>';

        $img = '<img src="'.plugins_url('dvd-woo-pagseguro-parcelas/img/bandeiras-parcelamento.gif').'" class="img-responsive">';


        return '<div class="panel panel-default "id="table-parcelado"><div class="panel-heading" style="display: flex;justify-content: space-between">Pagseguro<div class="pull-right">'.$img.'</div></div><div class="panel-body"><div class="row">'.$html.'</div></div></div>';
    }

    private function getFator() {
        switch ($this->type) {
            case 'pagseguro':
                $fators = $this->pagseguro;
                break;
        }

        return $fators;
    }



    private function floatToReal($float) {
        return 'R$ '. number_format($float, 2, ',', '.');
    }

}

// new CalcParcelamento();



endif;
