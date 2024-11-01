<?php 


class WOONOTIFY_DB{

    private $table;
    private $wpdb;
    function __construct(){
        global $wpdb;
        $this->wpdb=$wpdb;
        $this->table = $wpdb->prefix . 'woonotify_msg';
    }

    function init_db(){

        
        $charset = $this->wpdb->get_charset_collate();
        $charset_collate = $this->wpdb->get_charset_collate();
        $sql = "CREATE TABLE ".$this->table." (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                order_id mediumint(9) NOT NULL,
                time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                notification text NOT NULL,
                phone text NOT NULL,
                customer_name text NOT NULL,
                issent boolean DEFAULT false NOT NULL,
                PRIMARY KEY  (id)
                ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    function remove_db(){

    }

    function woonotify_add_to_db($order_id,$notification,$phone,$customer_name){
        global $wpdb;
        $table = $wpdb->prefix . 'woonotify_msg';
        $data         = array( 'order_id'  => $order_id, 'notification' =>$notification,'phone'=>$phone,'customer_name'=>$customer_name);
        $data_format  = array( '%d', '%s' ,'%s','%s');
        $wpdb->insert( $table, $data, $data_format );
    }

    function woonotify_update_db(){

        $data         = array( 'issent'  => true, 'notification' => "Test Notification" );
        $data_format  = array( '%d', '%s' );
        $updated = $wpdb->update( $this->table, $data, $data_format, $where, $where_format );

    }

    function woonotify_get_pending(){
        $result = $this->wpdb->get_results ( "SELECT * FROM $this->table WHERE `issent` = 0");
        return $result;
    }

    function woonotify_mark_sent($id){
        $data         = array( 'issent'  => 1);
        $data_format = ['%d'];
        $where = array('id'=>$id);
        $where_format = array( '%d');
        $updated = $this->wpdb->update( $this->table, $data, $where);
    }



}




?>