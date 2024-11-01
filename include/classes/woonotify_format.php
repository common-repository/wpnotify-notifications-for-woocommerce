<?php 

class WOONOTIFY_FORMAT{



    public static function woonotify_process_template($order,$template){
        $blocks = ['[product-name]','[order-status]'];
        $product_name = "";
        foreach($order->get_items() as $item) {
            $product_name = $item['name'].", ";
        }
        $template = str_replace('[product-name]',$product_name,$template);
        $template = str_replace('[order-status]',$order->get_status(),$template);
        return $template;
        foreach($blocks as $block){
            
        }
    }
}

?>