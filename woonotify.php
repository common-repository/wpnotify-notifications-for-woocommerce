<?php 
/*
Plugin Name: WPNotify - Notifications for WooCommerce
Plugin URI: mailto:prasadkirpekar@outlook.com
Description: Send WhatsApp notifications to your customer about orders.
Author: WooNotify
Version: 1.0.0
Author URI: https://woonotify.xyz
License: GPL v2
Copyright: Prasad Kirpekar

	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define('WOONOTIFY_MENU','woonotify-admin-menu');

define('WOONOTIFY_DIR',plugin_dir_path(__FILE__));

define('WOONOTIFY_DIR_URL',plugin_dir_url(__FILE__));

define('WOONOTIFY_VERSION','1.0.0');



include_once WOONOTIFY_DIR.'/include/classes/woonotify_notify.php';

function woonotify_front(){

    include_once WOONOTIFY_DIR.'/include/classes/woonotify_frontend.php';
    $plugin_public = new WOONOTIFY_FRONTEND( 'woonotify', '1.0.0' );

    add_action( 'wp_enqueue_scripts', [$plugin_public,'enqueue_styles'] );
    add_action( 'wp_enqueue_scripts', [$plugin_public, 'enqueue_scripts'] );

    add_action( 'woocommerce_after_checkout_form', [$plugin_public, 'ccs_enable_on_woocomerce']);
    add_action( 'woocommerce_after_checkout_validation', [$plugin_public, 'ccs_validate_billing_phone'], 10, 2);
    
		
}
woonotify_front();



if(!function_exists('woonotify_admin_settings')){
    function woonotify_admin_settings(){
        $page=add_menu_page('WPNotify', 'WPNotify', 'manage_options', basename(__FILE__), 'woonotify_home_page', 'dashicons-whatsapp');
        add_action('admin_print_scripts-' . $page, 'woonotify_enqueue_scripts');
        /*
        $settings = add_submenu_page(basename(__FILE__), 'Manage', 'Manage', 'manage_options', basename(__FILE__), 'woonotify_home_page');
	    add_action('admin_print_scripts-' . $settings, 'woonotify_enqueue_scripts');
        
        $settings = add_submenu_page(basename(__FILE__), 'Settings', 'Settings', 'manage_options', 'wpnotify-settings', 'woonotify_setting_page');
	    add_action('admin_print_scripts-' . $settings, 'woonotify_enqueue_scripts');
        
        $settings = add_submenu_page(basename(__FILE__), 'Channels', 'Channels', 'manage_options', 'wpnotify-channels', 'woonotify_setting_page');
        add_action('admin_print_scripts-' . $settings, 'woonotify_enqueue_scripts');
        */
        
    }
}




if(!function_exists('woonotify_home_page')){
    function woonotify_home_page(){
        
        if(!current_user_can( 'manage_options')) return;
        if(isset($_REQUEST['submitted_template'])&&check_admin_referer( 'woonotify_nonce_field_2', 'woonotify_nonce_field_2' )){
            $order_placed_template = sanitize_textarea_field($_POST['order_placed_template']);
            update_option('woonotify_order_placed_template',$order_placed_template);
            $order_status_changed_template = sanitize_textarea_field($_POST['order_status_changed_template']);
            update_option('woonotify_order_status_changed_template',$order_status_changed_template);
            echo "<div class='notice notice-success is-dismissible'>Setting updated!</div>";
        }
        if(isset($_REQUEST['submitted_license'])){
            include_once WOONOTIFY_DIR.'/include/classes/woonotify_license.php';
            $license = new WOONOTIFY_LICENSE;
            if(isset($_POST['woonotify_key'])){
                $key = $_POST['woonotify_key'];
                $status = $license->activateKey($key);
                echo "<div style='font-size:20px;padding:10px' class='notice notice-success is-dismissible'>$status</div>";
            }
        }
        if(isset($_REQUEST['submitted_notification'])){
            $contries = $_POST['selected_countries'];
            for($i=0; $i < count($contries); $i++) {
                $contries[$i] = strtolower($contries[$i]);
                if($i==0){
                    update_option('woonotify_initial_country',$contries[0]); 
                }
            }
            $order_place = $_POST['woonotify_on_order_placed'];
            $order_change = $_POST['woonotify_on_order_changed'];
            
            update_option('woonotify_selected_countries',$contries);
            update_option('woonotify_on_order_placed',$order_place);
            update_option('woonotify_on_order_changed',$order_change);
        }
        $action_url=$_SERVER['REQUEST_URI'];
        include "admin/woonotify-admin.php";
    }
}

if(!function_exists('woonotify_setting_page')){
    function woonotify_setting_page(){
        
        if(!current_user_can( 'manage_options')) return;
        if(isset($_REQUEST['submitted_template'])&&check_admin_referer( 'woonotify_nonce_field_2', 'woonotify_nonce_field_2' )){
            $order_placed_template = sanitize_textarea_field($_POST['order_placed_template']);
            update_option('woonotify_order_placed_template',$order_placed_template);
            $order_status_changed_template = sanitize_textarea_field($_POST['order_status_changed_template']);
            update_option('woonotify_order_status_changed_template',$order_status_changed_template);
            echo "<div class='notice notice-success is-dismissible'>Setting updated!</div>";
        }
        if(isset($_REQUEST['submitted_license'])){
            include_once WOONOTIFY_DIR.'/include/classes/woonotify_license.php';
            $license = new WOONOTIFY_LICENSE;
            if(isset($_POST['woonotify_key'])){
                $key = $_POST['woonotify_key'];
                $status = $license->activateKey($key);
                echo "<div style='font-size:20px;padding:10px' class='notice notice-success is-dismissible'>$status</div>";
            }
        }
        
        
        $action_url=$_SERVER['REQUEST_URI'];
        include "admin/pages/settings.php";
    }
}


if(!function_exists('woonotify_enqueue_scripts')){
    function woonotify_enqueue_scripts(){
        wp_enqueue_style('woonotify_tailwind',plugin_dir_url(__FILE__).'/admin/assets/css/tailwind.min.css');
        wp_enqueue_style('woonotify_appcss',plugin_dir_url(__FILE__).'/admin/assets/css/app.css');
        wp_register_script( "woonotify_plugin_js",plugin_dir_url(__FILE__).'/admin/assets/js/app.js', array('jquery') );
        wp_localize_script( 'jquery', 'ajax_url', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'woonotify_plugin_js' );
        wp_enqueue_script( 'woonotify_ajax_url' );
    }
}

add_action('admin_menu','woonotify_admin_settings');




if(!function_exists('woonotify_admin_bar_menu')){
    function woonotify_admin_bar_menu() {
        include_once WOONOTIFY_DIR.'/include/classes/woonotify_db.php';
        $woo = new WOONOTIFY_DB;
        $pending = $woo->woonotify_get_pending();

        if ( current_user_can( 'manage_options' ) ) {
            global $wp_admin_bar;

            $wp_admin_bar->add_menu( array(
                'id'    => WOONOTIFY_MENU, //Change this to yours
                'title' => '<span class="ab-icon dashicons dashicons-whatsapp"></span><span class="ab-label"> WooNotify Pending '.count($pending).'</span>',
                'href'  => "#",
            ) );
            
            $pending = $woo->woonotify_get_pending();
            foreach($pending as $n){
                $wp_admin_bar->add_node([
                    'id'        => 'woonotify_admin_'.$n->id,
                    'title' => "Order norification to ".$n->customer_name,
                    'href' => "https://api.whatsapp.com/send?phone=$n->phone&text=$n->notification",
                    'parent' => WOONOTIFY_MENU
                ]);
            }
            

        }
    }
    add_action( 'admin_bar_menu', 'woonotify_admin_bar_menu' , 500 );
}




if(!function_exists('woonotify_enqueue_scripts')){
    function woonotify_activate() {
        include_once WOONOTIFY_DIR.'/include/classes/woonotify_db.php';
        $woodb = new WOONOTIFY_DB;
        $woodb->init_db();
        add_option('woonotify_order_placed_template','Your order of [product-name] has been placed successfully. Your current order status is [order-status] Thank you for shopping with us 🙂');
        add_option('woonotify_order_status_changed_template','Great news! Status of your order [product-name] has been changed to [order-status]. Thank you for shopping with us 🙂');
    }
}

register_activation_hook(__FILE__, 'woonotify_activate');