<?php 

class WOONOTIFY_FRONTEND{


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.2
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		if ( ! function_exists( 'is_plugin_active' ) ){
		    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.2
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, WOONOTIFY_DIR_URL . '/admin/assets/css/countrycode.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.2
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, WOONOTIFY_DIR_URL . '/admin/assets/js/countrycode.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the public-facing side of the woocommerce checkout page.
	 *
	 * @since    1.2
	 */
	public function ccs_enable_on_woocomerce() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(is_plugin_active('woocommerce/woocommerce.php') && is_checkout()){
	?>
		<script>
			var selection = document.querySelector("#billing_phone") !== null;
		    if(selection){
	            var input = document.querySelector("#billing_phone");
	            var iti = window.intlTelInput(input, {
	                hiddenInput: "billing_phone",
	                separateDialCode: true,
	                formatOnDisplay: true,
	                <?php
				    if(!empty(get_option('woonotify_initial_country'))){
				   	?>
	                initialCountry: "<?php echo get_option('woonotify_initial_country'); ?>",
				    <?php
				    }
				    
				   	?>
				   	onlyCountries: <?php echo json_encode(get_option('woonotify_selected_countries',[])); ?>,
				    <?php
					
				    ?>
	                utilsScript: "<?php echo esc_url( WOONOTIFY_DIR_URL.'admin/assets/js/wc-utils.js' );?>",
	            });
	        }
        </script>
        
    	<script>
    		var input = document.querySelector("#billing_phone");

			var errorMap = [
				"<?php _e( 'Invalid number', 'country-code-selector' ); ?>", 
				"<?php _e( 'Invalid country code', 'country-code-selector' ); ?>", 
				"<?php _e( 'Too short', 'country-code-selector' ); ?>", 
				"<?php _e( 'Too long', 'country-code-selector' ); ?>"
			];
			
			var preventAlert = true;

			var reset = function() {
			  preventAlert = true;
			};

			input.addEventListener('blur', function() {
			  if (input.value.trim()) {
			    if (iti.isValidNumber()) {
			      document.getElementById("place_order").disabled = false;
			    } else {
			      var errorCode = iti.getValidationError();

			      if(preventAlert){
			      	document.getElementById("place_order").disabled = true;
			      	alert(errorMap[errorCode]);
			      	preventAlert = false;
			      }
			    }
			  }
			});

			// on keyup / change flag: reset
			input.addEventListener('change', reset);
			input.addEventListener('keyup', reset);

			jQuery('select#billing_country').on( 'change', function (){
	            var billingCountry = jQuery('#billing_country :selected').val();
	            iti.setCountry(billingCountry.toLowerCase());
	        });

			input.addEventListener("countrychange", function() {
	          var country_data = iti.getSelectedCountryData();
	          // alert(country_data.name+'-'+country_data.iso2+'-'+country_data.dialCode);
	          jQuery('input#billing_phone').val('');
	          jQuery('select#billing_country').val(country_data.iso2.toUpperCase()).trigger('change');
	      	});
    	</script>
 <?php
		}
	}

	public function ccs_validate_billing_phone( $fields, $errors ){ 
	    if ( strlen($fields[ 'billing_phone' ]) < 6 ){
	        $errors->add( 'validation', __('Your phone field has an invalid input', 'country-code-selector') );
	    }
	}

	/**
	 * Register the JavaScript for the public-facing side of the shopp checkout page.
	 *
	 * @since    1.2
	 */
	public function ccs_enable_on_shopp() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(is_plugin_active('shopp/Shopp.php') && is_shopp_checkout_page()){
	?>
		<script>
			var selection = document.querySelector("#phone") !== null;
		    if(selection){
	            var input = document.querySelector("#phone");
		        var iti = window.intlTelInput(input, {
	                hiddenInput: "phone",
	                separateDialCode: true,
	                formatOnDisplay: true,
	                <?php
				    if(!empty(get_option('initial_country'))){
				   	?>
	                initialCountry: "<?php echo get_option('initial_country'); ?>",
				    <?php
				    }
				    
				   	?>
				   	onlyCountries: <?php echo json_encode(get_option('selected_countries')); ?>,
				    <?php
					
				    ?>
	                utilsScript: "<?php echo esc_url( plugin_dir_url( __FILE__ ) .'js/wc-utils.js' );?>",
	            });
	        }
        </script>

    	<script>
    		var input = document.querySelector("#phone");

			var errorMap = [
				"<?php _e( 'Invalid number', 'country-code-selector' ); ?>", 
				"<?php _e( 'Invalid country code', 'country-code-selector' ); ?>", 
				"<?php _e( 'Too short', 'country-code-selector' ); ?>", 
				"<?php _e( 'Too long', 'country-code-selector' ); ?>"
				];
			
			var preventAlert = true;

			var reset = function() {
			  preventAlert = true;
			};

			input.addEventListener('blur', function() {
			  if (input.value.trim()) {
			    if (iti.isValidNumber()) {
			      document.getElementById("checkout-button").disabled = false;
			    } else {
			      var errorCode = iti.getValidationError();

			      if(preventAlert){
			      	document.getElementById("checkout-button").disabled = true;
			      	alert(errorMap[errorCode]);
			      	preventAlert = false;
			      }
			    }
			  }
			});

	        // on keyup / change flag: reset
			input.addEventListener('change', reset);
			input.addEventListener('keyup', reset);

			jQuery('select#billing-country').on( 'change', function (){
	            var billingCountry = jQuery('#billing-country :selected').val();
	            iti.setCountry(billingCountry.toLowerCase());
	        });

			input.addEventListener("countrychange", function() {
	          var country_data = iti.getSelectedCountryData();
	          // alert(country_data.name+'-'+country_data.iso2+'-'+country_data.dialCode);
	          jQuery('input#phone').val('');
	          jQuery('select#billing-country').val(country_data.iso2.toUpperCase()).trigger('change');
	      	});
    	</script>
 <?php
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the woocommerce checkout page.
	 *
	 * @since    1.2
	 */
	public function ccs_enable_on_gravity_form( $form, $field_values ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(is_plugin_active('gravityforms/gravityforms.php')){
			// echo "<pre>"; print_r($form); print_r($field_values); echo "</pre>";
			$field_id = 'input_'.$form['id'].'_'.get_option('gform_phone_field_id');
		    $script = '';
		    // $script = '(function($){';
		    $script .= 'var selection = document.querySelector("#'.$field_id.'") !== null;';
		    $script .= 'if(selection){';
		    $script .= 'var input = document.querySelector("#'.$field_id.'");';
		    $script .= 'input.style.width = "100%";';
		    $script .= 'var iti = window.intlTelInput(input, {';
            $script .= 'hiddenInput: input.getAttribute("name"),';
            $script .= 'separateDialCode: true,';
            $script .= 'formatOnDisplay: true,';
            
            if(!empty(get_option('initial_country'))){
            $script .= 'initialCountry: "'.get_option('initial_country').'",';
			}
			
			if(!empty(get_option('selected_countries'))){
			$script .= 'onlyCountries: '.json_encode(get_option('selected_countries')).',';
			}

            $script .= 'utilsScript: "' . esc_url( plugin_dir_url( __FILE__ ) .'js/wc-utils.js' ).'",';
            $script .= '});';

            // if(get_option('js_validation') == 'on') { 
            // $script .= 'var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];';

            $script .= 'var errorMap = ["'.__( 'Invalid number', 'country-code-selector' ).'", "'.__( 'Invalid country code', 'country-code-selector' ).'", "'.__( 'Too short', 'country-code-selector' ).'", "'.__( 'Too long', 'country-code-selector' ).'"];';

			$script .= 'input.addEventListener("blur", function() {';
			$script .= '  if (input.value.trim()) {';
			$script .= '    if (iti.isValidNumber()) {';
			$script .= '        document.getElementById("gform_submit_button_1").disabled = false;';
			$script .= '    } else {';
			$script .= '        document.getElementById("gform_submit_button_1").disabled = true;';
			$script .= '        var errorCode = iti.getValidationError();';
			$script .= '        alert(errorMap[errorCode]);';
			$script .= '    }';
			$script .= '  }';
			$script .= '});';
            // }
            $script .= '}';
            
$script .= '(function($){
	jQuery("#gform_submit_button_'.$form['id'].'").on("click", function (e) {
		$ccs_phone = jQuery("input#'.$field_id.'");
		if($ccs_phone.attr("aria-required") == "true" && $ccs_phone.val().length < 6){
			alert("'.__('Invalid number','country-code-selector').'");	
			e.preventDefault();
		}else{
			jQuery("#gform_'.$form['id'].'").submit();
		}
	});
})(jQuery);';
		 	
		    GFFormDisplay::add_init_script( $form['id'], 'country_code_selector', GFFormDisplay::ON_PAGE_RENDER, $script );
		}
	}


	/**
	 * Register the JavaScript for the public-facing side of the woocommerce checkout page.
	 *
	 * @since    1.2
	 */
	public function ccs_enable_on_contact_form7() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Country_Code_Selector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Country_Code_Selector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(is_plugin_active('contact-form-7/wp-contact-form-7.php')){
			$selected_cform7 = get_option('selected_cform7');
			$cform7_field_id = get_option('cform7_phone_field_id');

			// Detect if the selected contact form shortcode exist in this page content
			$content = get_the_content();
			$shortcode = '[contact-form-7 id="'.$selected_cform7.'"';

			$check = strpos($content,$shortcode);
			if($check=== false) {
			  // echo 'short code does not exist';
			} else {
?>
			    <script type="text/javascript">
			    	var selection = document.querySelector("#<?php echo $cform7_field_id?>") !== null;
			    	if(selection){
			    		var input = document.querySelector("#<?php echo $cform7_field_id;?>");
			            var iti = window.intlTelInput(input, {
			                hiddenInput: input.getAttribute("name"),
			                separateDialCode: true,
			                formatOnDisplay: true,
			                <?php
						    if(!empty(get_option('initial_country'))){
						   	?>
			                initialCountry: "<?php echo get_option('initial_country'); ?>",
						    <?php
						    }
						    if(!empty(get_option('selected_countries'))){
						   	?>
						   	onlyCountries: <?php echo json_encode(get_option('selected_countries')); ?>,
						    <?php
							}
						    ?>
			                utilsScript: "<?php echo esc_url( plugin_dir_url( __FILE__ ) .'js/wc-utils.js' );?>",
			            });
			    	}
	        	</script>
				
	        	<script>
	        		var input = document.querySelector("#<?php echo $cform7_field_id?>");

					var errorMap = [
						"<?php _e( 'Invalid number', 'country-code-selector' ); ?>", 
						"<?php _e( 'Invalid country code', 'country-code-selector' ); ?>", 
						"<?php _e( 'Too short', 'country-code-selector' ); ?>", 
						"<?php _e( 'Too long', 'country-code-selector' ); ?>"
					];

					input.addEventListener('blur', function() {
					  if (input.value.trim()) {
					    if (iti.isValidNumber()) {
					      document.querySelector(".wpcf7-submit").disabled = false;
					    } else {
					      var errorCode = iti.getValidationError();
				      	  document.querySelector(".wpcf7-submit").disabled = true;
				      	  alert(errorMap[errorCode]);
					    }
					  }
					});

					jQuery(document).ready(function(){
						jQuery('.wpcf7-submit').on('click', function (e) {
						    $ccs_phone = jQuery('input#<?php echo $cform7_field_id?>');
						    if($ccs_phone.attr('aria-required') == "true" && $ccs_phone.val().length < 6){
						    	alert('Invalid number');	
						    	e.preventDefault();
						    }
						});
					});
	        	</script>
	 <?php
			}
		}
	}
}

?>