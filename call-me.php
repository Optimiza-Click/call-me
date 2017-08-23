<?php
/*
Plugin Name: Call Me Plugin
Description: Plugin que muestra un número de teléfono flotante para versión móvil.
Author: Departamento de Desarrollo - Optimizaclick
Author URI: http://www.optimizaclick.com/
Text Domain: Optimizaclick Migration Plugin
Version: 0.1
Plugin URI: http://www.optimizaclick.com/
*/

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'wp_call_me_plugin' ) ) 
{
	class wp_call_me_plugin 
    {        
        public $cmp_options;

        function __construct() 
        {   
            add_action( 'wp_head', array( $this, 'cmp_load_css') );
            add_action( 'wp_footer', array( $this, 'cmp_show_div') );
            add_action( 'admin_menu', array( $this, 'cmp_admin_menu' )); 
            add_action( 'admin_head', array( $this, 'cmp_load_css_js' )); 
            add_action( 'init', array( $this, 'wop_save_options' )); 

            $this->cmp_options = json_decode(get_option("cmp_options"), true);
        }

        public function cmp_admin_menu() 
        {	
            $menu = add_management_page( 'Call me', 'Call me', 'read',  
                                  'replace-image-name', array( $this,'cmp_form'));
        }    

        public function cmp_form()
        {
            ?>

            <form method="post" id="callmeform" action="<?php echo get_home_url(); ?>/cmp_save_options">

            <p><label for="cmp_phone_number">Teléfono: </label>
            <input type="text" name="cmp_phone_number" id="cmp_phone_number" 
            value="<?php echo $this->cmp_options["cmp_phone_number"] ?>" placeholder="Teléfono" /></p>

            <p><label for="cmp_phone_number">Tamaño de fuente: </label>
            <input type="text" name="cmp_font_size" id="cmp_font_size" 
            value="<?php echo $this->cmp_options["cmp_font_size"] ?>" placeholder="Incluir unidad" /></p>

            <p><label for="cmp_phone_number">Color de fondo: </label>
            <input type="text" name="cmp_background_color" class="jscolor"  value="<?php echo $this->cmp_options["cmp_background_color"] ?>"
            id="cmp_background_color" placeholder="Color de fondo" /></p>

            <p><label for="cmp_phone_number">Color de letra: </label>
            <input type="text" name="cmp_font_color" class="jscolor" value="<?php echo $this->cmp_options["cmp_font_color"] ?>"
              id="cmp_font_color" placeholder="Color de letra" /></p>

            <p><input type="submit" value="Guardar cambios" class="button button-primary" /></p>

            </form>

            <?php
        }

        public function cmp_show_div()
        {
            ?>

            <div class="boton_fijo">
                <p><a href="tel:<?php echo str_replace(" ", "", $this->cmp_options["cmp_phone_number"]); ?>" >
                <i class="fa fa-phone"></i> <?php echo $this->cmp_options["cmp_phone_number"] ?></a></p>
            </div>

            <style>
                .boton_fijo a{
                    color: <?php echo $this->cmp_options["cmp_font_color"] ?> !important;
                    font-size: <?php echo $this->cmp_options["cmp_font_size"] ?> !important; 
                }

                .boton_fijo {background-color: <?php echo $this->cmp_options["cmp_background_color"] ?> !important;}
            </style>
            <?php
        }

        public function cmp_load_css()
        {
            wp_register_style( 'cmp_style_css', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/css/cmp-style.css', false, '1.0.0' );
            wp_enqueue_style( 'cmp_style_css' );

            wp_register_style( 'font_awesome', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/css/font-awesome.min.css', false, '1.0.0' );
            wp_enqueue_style( 'font_awesome' );

        }

        public function cmp_load_css_js()
        {
            wp_register_style( 'cmp_spectrum_css', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/css/spectrum.css', false, '1.0.0' );
            wp_enqueue_style( 'cmp_spectrum_css' );

            wp_register_style( 'cmp_admin_css', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/css/cmp-admin.css', false, '1.0.0' );
            wp_enqueue_style( 'cmp_admin_css' );
    
            wp_enqueue_script( 'cmp_script_spectrum', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/js/spectrum.js', array('jquery') );            
            wp_enqueue_script( 'cmp_script_admin', WP_PLUGIN_URL. '/'.basename( dirname( __FILE__ ) ).'/js/cmp_admin.js', array('jquery') );
        }

        public function wop_save_options() 
        {
			$page_viewed = basename($_SERVER['REQUEST_URI']);
            if( $page_viewed == "cmp_save_options" ) 
            {
                update_option("cmp_options", json_encode($_REQUEST));
                wp_redirect("./wp-admin/tools.php?page=replace-image-name");
                exit();
            }
		}
    }
}

$GLOBALS['wp_call_me_plugin'] = new wp_call_me_plugin();   

?>