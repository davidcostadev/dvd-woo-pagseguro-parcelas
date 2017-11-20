<?php

/**
 *
 * @since 		1.0.0
 * @author 		David Costa <davidcostadev@gmail.com>
 * @version 	1.0.0
 */

class DVDPagseguroParcelas_admin {

    public $page = 'dvdwoo';

    public $option_group = 'dvdwoo_settings';

    public $option_name = 'dvdwoo_settings';


    public $settings;
    public $default;

    public function __construct() {

        add_action('admin_menu', array($this, 'add_menu'), 100);
        add_action('admin_init', array($this, 'dvdwoo_page_settings'));

        $this->default = [
            'show_products' => 0,
            'show_product' => 0
        ];

        $this->get_settings();
    }

    public function get_settings() {
        $this->settings = get_option($this->option_name);

        if(!empty($this->settings)) {
            $this->settings = array_merge($this->default, $this->settings);

        } else {
            $this->settings = $this->default;
        }
    }

    public function add_menu() {
        add_submenu_page(
            'woocommerce',
            __('DVD Woo Pagseguro Installment',	'dvd-woo-pagseguro-parcelas'),
            __('Pagseguro Installment', 'dvd-woo-pagseguro-parcelas'),
            apply_filters('dvdwoo_page_view_permission', 'manage_options'),
            $this->page,
            array($this, 'dvdwoo_page_callback')
        );
    }

    public function dvdwoo_page_callback() {
        include_once 'html-settings-page.php';
    }

    public function dvdwoo_page_settings() {


        add_settings_field(
            'show_products',
            __('Mostrar na Listagem', 'dvd-woo-pagseguro-parcelas'),
            array($this, 'dvdwoo_checkbox_callback'),
            $this->page,
            'section_general-base',
            array(
                'id'		=>	'show_products',
                'desc'		=>	__('Para mostrar na listagem dos produtos', 'dvd-woo-pagseguro-parcelas'),
                'default'	=> ''
            )
        );
        add_settings_field(
            'show_product',
            __('Mostrar no Produto', 'dvd-woo-pagseguro-parcelas'),
            array($this, 'dvdwoo_checkbox_callback'),
            $this->page,
            'section_general-base',
            array(
                'id'		=>	'show_product',
                'desc'		=>	__('Para mostrar na listagem dos produtos', 'dvd-woo-pagseguro-parcelas'),
                'default'	=> ''
            )
        );


        register_setting(
            $this->option_group,
            $this->option_name,
            array($this, 'dvdwoo_options_sanitize')
        );
    }

    public function dvdwoo_checkbox_callback($args) {
        extract($args);

        $value = isset($this->settings[$id]) ? $this->settings[$id] : 0;

        echo "<input type='checkbox' id='".$id."-0' name='".$this->option_name."[$id]' value='1'".checked('1', $value, false)." />";
        echo isset($desc) ? "<span class='description'> $desc</span>" : '';
    }

    public function dvdwoo_text_callback($args) {
        extract($args);

        $value = isset($this->settings[$id]) ? $this->settings[$id] : $default;

        echo "<input class='$class' type='text' id='$id' name='".$this->option_name."[$id]' value='$value' placeholder='$placeholder' />";
        echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
    }

    public function dvdwoo_number_callback($args) {
        extract($args);

        $value = isset($this->settings[$id]) ? $this->settings[$id] : $default;

        echo "<input class='$class' type='number' id='$id' name='".$this->option_name."[$id]' value='$value' />";
        echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
    }

    public function dvdwoo_select_callback($args) {
        extract($args);

        $value = isset($this->settings[$id]) ? $this->settings[$id] : $default;

        echo "<select name='".$this->option_name."[$id]'>";
        foreach($options as $k => $option) {
            echo "<option value='$k'".selected($k, $value, false).">".$option."</option>";
        }
        echo "</select>";
        echo isset($desc) ? "<br /><span class='description'>$desc</span>" : '';
    }

    public function dvdwoo_options_sanitize($input) {
        foreach($input as $k => $v) {
            if($k == 'installment_qty') {
                // if($v < 2 || empty($v)) {
                // 	$v = 2;
                // }
            } else if($k == 'installment_minimum_value') {
                // if($v < 0 || empty($v)) {
                // 	$v = 0;
                // }
            }

            $newinput[$k] = trim($v);
        }

        return $newinput;
    }
}


new DVDPagseguroParcelas_admin();
