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
?>

<div class="wrap dvd-woo-pagseguro-parcelas">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form action="options.php" method="post">
        <?php settings_fields($this->option_group); ?>

        <div class="tabs">
            <div class="section">
                <h3><?php echo __('Opções para parcelamento', 'dvd-woo-pagseguro-parcelas'); ?></h3>
                <table class="form-table">
                    <?php do_settings_fields($this->page, 'section_general-base'); ?>
                </table>
            </div>
        </div>

        <?php submit_button(); ?>
    </form>

    <div class="section">
        <?php echo '<a class="button-secondary" href="https://github.com/davidcostadev/dvd-woo-pagseguro-parcelas/issues" target="_blank">' . __('Bugs and Suggestions', 'dvd-woo-pagseguro-parcelas') . '</a>'; ?>
        <?php echo '<a class="button-secondary" href="https://github.com/davidcostadev/dvd-woo-pagseguro-parcelas" target="_blank">' . __('Github', 'dvd-woo-pagseguro-parcelas') . '</a>'; ?>
    </div>
</div>
