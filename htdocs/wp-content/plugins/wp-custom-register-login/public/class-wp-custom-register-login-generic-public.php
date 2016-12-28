<?php
/**
 * The generic functionality methods of the plugin.
 *
 * @since      2.0.0
 * @package    Wp_Custom_Register_Login
 * @subpackage Wp_Custom_Register_Login/public
 * @author     Neelkanth Kaushik
 */
class Wp_Custom_Register_Login_Generic_Public
{
    
    public function __construct(){
        
    }
    
    public static function generic_placeholder_replacer($source,$placeholders){
        return strtr($source, $placeholders);
    }
}
