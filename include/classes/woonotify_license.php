<?php

class WOONOTIFY_LICENSE{

    private $service_url = "https://dashboard.woonotify.xyz/index.php/api/";

    function init(){
        add_action( 'wp_ajax_wpnotify_activate', array( $this, 'activateKey' ) ); 
    }

    function activateKey($key){
        
        $url  = $this->service_url."license/activate/".$key;
        $domain = get_site_url();
        $domain = parse_url($domain);
        $domain = str_replace('www.','',$domain['host']);
        global $current_user;
        wp_get_current_user();
        $name = $current_user->display_name;
        $email = get_option('admin_email');
        $body = array(
            'domain' => $domain,
            'name' => $name,
            'email' => $email,
        );

        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                //'Authorization' => 'Bearer {token goes here}',
                'Content-Type'  => 'application/json',
            ),
            'body'        => json_encode($body),
        );
        $request = wp_remote_post( $url, $args );
        $parsed = json_decode($request['body']);

        if($parsed->status){
            update_option('wpnotify_ispremium','1');
            update_option('wpnotify_key',$key);
            update_option('wpnotify_token',$parsed->token);
        }
        
        return $parsed->msg;

    }
    function checkLicense(){

    }
    function deactivateKey(){

    }
}