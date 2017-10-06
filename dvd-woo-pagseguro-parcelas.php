<?php
/**
 * Plugin Name: DVD Woo Pagseguro Installment
 * Plugin URI: https://github.com/davidcostadev/dvd-woo-pagseguro-parcelas
 * Description: Payment of the products according to the factors of the pagseguro.
 * Author: David Costa
 * Author URI: http://davidcosta.com.br
 * Version: 1.0.0
 * License: GPLv3 or later
 * License URI: //www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: dvd-woo-pagseguro-parcelas
 * Domain Path: /languages
 */


if(!defined('ABSPATH')){
	exit;
}

if ( ! class_exists( 'DVDPagseguroParcelas' ) ) :


if ( !defined('DVD_PATH') )
    define('DVD_PATH', dirname(__FILE__) . '/');


final class DVDPagseguroParcelas
{
    protected static $_instance = null;

    public $option_name = 'dvdwoo_settings';
    public $default;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {

        $this->default = [
            'show_products' => 0,
            'show_product' => 0
        ];

        $this->get_settings();

        $this->incluir();


		require DVD_PATH . 'admin.php';
		require DVD_PATH . 'calc-parcelamento.php';
    }

	public function incluir() {

        if($this->settings['show_products']) {
            add_action('woocommerce_get_price_html', [$this, 'dvd_wrap_price_html'], 50);
        }

        if($this->settings['show_product']) {
            add_action('woocommerce_single_product_summary', [$this, 'add_table_parcelamento'], 40);
        }

	}

	public function price_loop_item() {
        echo '<div class="teste" style="color: blue; font-size: 18px; ">teste</div>';
	}

	public function dvd_wrap_price_html( $html ) {
		$html = $this->filtrar($html);

		return apply_filters( 'dvd_price_html', $html );
	}

	public function get_settings() {
        $this->settings = get_option($this->option_name);

        if(!empty($this->settings)) {
            $this->settings = array_merge($this->default, $this->settings);

        } else {
            $this->settings = $this->default;
        }
	}

	private function filtrar($html) {
		global $product;
		//
		// echo '<pre>';
		// print_r($product);
		// echo '</pre>';


		if(!empty($product->sale_price)) {
			$de  = $product->regular_price;
			$por = $product->price;
		} else {
			$de  = null;
			$por = $product->price;
		}
		// $html = str_replace('<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">&#82;&#36;', '', $html);
		// $html = str_replace('</span>', '', $html);
		// $html = str_replace('&nbsp;', '', $html);
		// $antes = $html;
		// $html = str_replace('.', '', trim($html));
		// $valor = (float) str_replace(',', '.', $html);

		$valor =  (float) $product->price;


		$desconto = $valor * 0.93;

		$inicio = '<span class="dvd-woo-merc-parcelas-price woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">';
		$fim    = '</span></span>';



		$textParcelado = CalcParcelamento::instance()
			->calc($valor);

		if($de) {
			$priceText = '<u>De ' . $this->floatToReal($de) . ' Por</u> <br> <strong>' . $this->floatToReal($por) .'</strong>';//.' <small>À Vista</small></strong>' ;
		} else {
			$priceText = $this->floatToReal($por) ;//.' <small>À Vista</small>' ;
		}

		if($textParcelado) {
			// $textParcelado .= '<br>';
		}


		return $inicio .
			'<span class="valor">'.$priceText.'</span> '.
			// $this->floatToReal($valor).
            '<span class="parcelado">'.$textParcelado.'</span>'.

			$fim
		;
	}

	private function floatToReal($float) {
        if(is_numeric($float)) {
		    return 'R$ '. number_format($float, 2, ',', '.');
        } else {
            return '';
        }
	}

	public function add_table_parcelamento() {
		global $product;

		// echo '<pre>';
		// print_r($product);
		// echo '</pre>';



		$tableParcelado = CalcParcelamento::instance()
			->parcela_table($product->price);

		// echo plugins_url('img/pagseguro-logo.png');
		echo $tableParcelado;
	}



}

DVDPagseguroParcelas::instance();



endif;
