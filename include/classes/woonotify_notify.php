<?php

include_once WOONOTIFY_DIR.'/include/classes/woonotify_db.php';
include_once WOONOTIFY_DIR.'/include/classes/woonotify_format.php';

class WOONOTIFY_NOTIFY{

    private $woodb;
    private $service_url = "https://dashboard.woonotify.xyz/index.php/api/";


    function __construct(){
        $this->woodb = new WOONOTIFY_DB;
        
        if(get_option('woonotify_on_order_placed',"1")=="1"){
            add_action('woocommerce_thankyou',array($this ,'action_woocommerce_order_status_placed'), 10, 1);
        }
        if(get_option('woonotify_on_order_changed',"1")=="1"){
            add_action( 'woocommerce_order_status_changed', array($this,'action_woocommerce_order_status_changed'), 10, 4 ); 
        }
        
    }

    function action_woocommerce_order_status_changed( $this_get_id, $this_status_transition_from, $this_status_transition_to, $instance ) { 
        echo $this_status_transition_from." ".$this_status_transition_to;
        if($this_status_transition_from==$this_status_transition_to) return;
        $order = wc_get_order( $this_get_id );
        $phone = str_replace("+","",$order->get_billing_phone());
       
        $str = get_option('woonotify_order_status_changed_template');
        $str = WOONOTIFY_FORMAT::woonotify_process_template($order,$str);
        $customer_name = $order->get_user()->first_name;
        $this->woonotify_send($order_id,$str,$phone,$customer_name); 
    }
             
     
    
    
    

    function action_woocommerce_order_status_placed( $order_id ) {

        if ( ! $order_id )
            return;

        // Getting an instance of the order object
        $order = wc_get_order( $order_id );
        
        //print_r($order);

        if($order->is_paid())
            $paid = 'yes';
        else
            $paid = 'no';

        // iterating through each order items (getting product ID and the product object) 
        // (work for simple and variable products)
        foreach ( $order->get_items() as $item_id => $item ) {

            if( $item['variation_id'] > 0 ){
                $product_id = $item['variation_id']; // variable product
            } else {
                $product_id = $item['product_id']; // simple product
            }

            // Get the product object
            $product = wc_get_product( $product_id );

        }

        // Ouptput some data
        $str = get_option('woonotify_order_placed_template');
        $str = WOONOTIFY_FORMAT::woonotify_process_template($order,$str);
        $phone = str_replace("+","",$order->get_billing_phone());
        $phone = str_replace(" ","",$order->get_billing_phone());
        $customer_name = $order->get_user()->first_name;
        
        $this->woonotify_send($order_id,$str,$phone,$customer_name); 
    }


    function woonotify_send($order_id,$str,$phone,$customer_name){

        $this->woodb->woonotify_add_to_db($order_id,$str,$phone,$customer_name);
        $url  = $this->service_url."msg/send";
        
        $body = array(
            'to' => $phone,
            'content' => $str,
        );
        $token=get_option('wpnotify_token','');
        if($token=='') return;
        $args = array(
            'method'      => 'POST',
            'timeout'     => 45,
            'sslverify'   => false,
            'headers'     => array(
                'Authorization' => 'Bearer '.$token,
                'Content-Type'  => 'application/json',
            ),
            'body'        => json_encode($body),
        );
        $request = wp_remote_post( $url, $args );
    }
}

$notify = new WOONOTIFY_NOTIFY;

?>