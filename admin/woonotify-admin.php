<div  class="w-full py-8 px-5 bg-white shadow-sm">
    <div class="flex justify-between">
        <div class=" flex">
        <img class="h-10 hidden w-10 mr-2" src="<?php echo plugin_dir_url(__FILE__).'/assets/img/ssl.png'; ?>" />
        <div class="block">
            <h3 class=" m-auto text-2xl mb-2 font-semibold text-gray-700" >WPNotify</h3>
            <p class="text-base text-gray-600">WhatsApp notifications for WooCommerce and more</p>
        </div>
        </div>
        <div class=" text-lg">
            <?php if(get_option('wpnotify_ispremium','0')=='0'){ ?>
                <span class="bg-gray-700 text-gray-200 py-1 px-2 rounded-md">Free</span>
            <?php }else{ ?>
                <span class="bg-yellow-700 text-gray-200 py-1 px-2 rounded-md">Premium</span>
            <?php } ?>
            <span class=" text-gray-700">Version:</span> <span class=" text-gray-800">1.0.0</span>
        </div>
    </div>
</div>
<div  class=" mt-5 flex w-full p-5 -pl-5 flex-row">
    <div class=" w-full mr-2 shadow-sm p-4 rounded-sm bg-white">
    
        <div class="bg-white">
            <nav class="flex flex-col sm:flex-row">
                <button style="display:none" onclick="switchtab('#primarysettings')" id="primarysettingsbtn" class="text-gray-600 text-lg py-4 px-6 block hover:text-green-500 focus:outline-none text-green-500 border-b-2 border-green-500 font-medium ">
                    WooNotify
                </button>
                <button id="morestuffbtn" onclick="switchtab('#morestuff')" class="text-gray-600 text-lg py-4 px-6 block hover:text-green-500 focus:outline-none">
                    Templates
                </button>
                <button onclick="switchtab('#contentsettings')" id="contentsettingsbtn" class="text-gray-600 text-lg py-4 px-6 block hover:text-green-500 focus:outline-none">
                    Notifications
                </button>
                <button onclick="switchtab('#advancedsettings')" id="advancedsettingsbtn" class="text-gray-600 text-lg py-4 px-6 block hover:text-green-500 focus:outline-none">
                    Subscription
                </button>
                
            </nav>
        </div>
        <!-- content -->
        <div class="p-5">
            
            <div style="display:none" id="primarysettings">
                <div class="flex w-full flex-row">

                <div class="p-4 m-2 w-1/2 rounded-md shadow-sm bg-green-700 text-white">
                    <h4 class="text-xl mb-1 text-bold">80</h4>
                    <p class="text-base">Notifications sent</p>
                </div>
                <div class="p-4 m-2 w-1/2 rounded-md shadow-sm bg-yellow-700 text-white">
                    <h4 class="text-xl mb-1 text-bold">50/Month</h4>
                    <p class="text-base">Remaining credits <a class="underline hover:text-green-900" href="#">Get Unlimited</a></p>
                </div>

                </div>
            </div>

            <div  style="display:none" id="contentsettings">
                <form method="post" action="<?php echo $action_url;?>">
					<input type="hidden" name="submitted_notification" value="1" />
					<?php echo wp_nonce_field('woonotify_nonce','woonotify_nonce_field') ?>
                    <label class="mt-4 mb-2 block text-lg text-gray-900">Send on Order placed</label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" name="woonotify_on_order_placed" <?php echo (get_option('woonotify_on_order_placed',"1")=="1")?'checked="checked"':''; ?> value="1">
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio" name="woonotify_on_order_placed"  <?php echo (get_option('woonotify_on_order_placed',"1")=="0")?'checked="checked"':''; ?> value="0">
                        <span class="ml-2">No</span>
                    </label>


                    <label class="mt-6 mb-2 block text-lg text-gray-900">Send on Order status changed</label>
                    <label class="inline-flex items-center">
                        <input type="radio" class="form-radio" <?php echo (get_option('woonotify_on_order_changed',"1")=="1")?'checked="checked"':''; ?>  name="woonotify_on_order_changed" value="1">
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" class="form-radio" <?php echo (get_option('woonotify_on_order_changed',"1")=="0")?'checked="checked"':''; ?>  name="woonotify_on_order_changed" value="0">
                        <span class="ml-2">No</span>
                    </label>
                    <label class="mt-8 block text-lg text-gray-900">Select supported countries</label>
                    <p class="mt-2 mb-2 text-gray-700">Selected countries will show up on checkout for billing details</p>
                        
                    <?php 
                            $countries = json_decode(file_get_contents(WOONOTIFY_DIR.'admin/assets/js/countries.json'));
                            $selected_countries = get_option('woonotify_selected_countries',[]);
                            
                            if(empty($selected_countries)){
                                $selected_countries=[];
                            }
                            for($i=0; $i < count($selected_countries); $i++) {
                                $selected_countries[$i] = strtoupper($selected_countries[$i]);
                            }
                        ?>
                    <select style="height:200px" name="selected_countries[]" class=" p-2 form-multiselect block w-full mt-1" multiple>
                        <?php foreach($countries as $country) { ?>
                            <option name="<?php echo "country_".$country->code; ?>" <?php if(in_array($country->code,$selected_countries)) echo "selected='selected'"; ?>  id="<?php echo $country->code; ?>" value="<?php echo $country->code; ?>">
                                <?php echo $country->name; ?>
                            </option>
                        <?php } ?>
                    </select>
                    
                    <label class="block text-lg text-gray-800 mt-5 mb-2">More Comming soon:</label>
                    <p>We are putting more type of notification for WooCommerce</p>
                    <p class="mt-2">Please mail us what type of notifications you want to send.</p>
                    <p><a href="mailto:prasadkirpekar@outlook.com" target="_blank"><b><u>Mail Us</u></b></a></p>
                    <div class="mt-5 flex">
                        <button class="py-2 mr-2 inline-flex right rounded-md shadow-sm block px-4 bg-gray-800 text-gray-100">Save Changes</button>

                    </div>
                    
                </form>
            </div>

            <div style="display:none" id="advancedsettings">
                
                <div >
                    <form method="post" action="<?php echo $action_url;?>" >
                        <input type="hidden" name="submitted_license" value="1">
                        <div class="flex w-full flex-row">

                            <div class="p-4 m-2 w-full rounded-md shadow-sm <?php echo (get_option('wpnotify_ispremium','0')=='0')?'bg-yellow-700':'bg-green-700'; ?> text-white">
                                <h4 class="text-xl mb-1 text-bold">Subscription type</h4>
                                <?php if(get_option('wpnotify_ispremium','0')=='0'){ ?>
                                <p class="text-base">Free</p>
                                <p class="text-base">Automatic WhatsApp notifications with paid plan <a target="_blank" href="https://woonotify.xyz">get here</a></p>
                                <?php }else{ ?>
                                    <p class="text-base">Premium</p>
                                    <p class="text-base">Automatic WhatsApp notifications are active</p>
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="p-4">
                            <label class="block text-lg text-gray-800 mt-2 mb-2">Subscription Key</label>
                            <input class="w-3/4 mb-2" disabled type="text" name="woonotify_domain" value="<?php echo site_url(); ?>" />
                            
                            <input class="w-3/4" type="text" value="<?php echo get_option('wpnotify_key',null); ?>" placeholder="XXXXX-XXXXX-XXXXX-XXXXX" name="woonotify_key" id="woonotify_key"  />
                            <div class="mt-5 flex">
                                <button id="wpnotify_activate" class="py-2 mr-2 inline-flex right rounded-md shadow-sm block px-4 bg-gray-800 text-gray-100">Activate</button>
                                <a href="https://woonotify.xyz/product/woonotify-license/" target="_blank" class="py-2 mr-2 inline-flex right rounded-md shadow-sm block px-4 bg-blue-700 text-gray-100">Buy New Key</a>
                            
                            </div>
                            <label class="block text-lg text-gray-800 mt-5 mb-2">Use your own business account with WooNotify</label>
                            <p class="text-base text-gray-600">We can host your business account on our servers. It is included with premium plan</p>
                            <p><a href="mailto:prasadkirpekar@outlook.com" target="_blank"><b><u>Mail Us to get it for you</u></b></a></p>
                    
                    
                        </div>
                    </form>
                </div>
                
                <!--- marketing content -->
                
                <!--marketing content end -->

            </div>

            <div style="display:none"  class=" flex justify-center  flex-col" id="morestuff">
                <p class="text-gray-600 text-lg">Here you can customize your message that will be delivered when order is placed, order status is changed etc </p>
                <ul class="mt-4 text-gray-700 text-base">
                    <li>Bold: *your-word*</li>
                    <li>Italic: _your-word_</li>
                    <li>Strikethrough: ~your-word~</li>
                    <li>Monospace: ```your-word```</li>
                </ul>
                <p class="text-base mb-2">To use smiles plesase copy paste them from the internet</p>
                <form method="post" action="<?php echo $action_url;?>" >
                    <?php echo wp_nonce_field('woonotify_nonce_field_2','woonotify_nonce_field_2') ?>
                    <input type="hidden" name="submitted_template" value="1" />
                    <label class="mt-8 block text-lg text-gray-900">Order placed</label>
                    <p class="mt-2 mb-1 text-gray-700">Below format will be used send message when order is placed</p>
                    
                    <textarea name="order_placed_template" id="order_placed_template" placeholder="Template to use when order is placed" class=" w-full block p-2 mt-2 bg-gray-300 text-base text-gray-800 h-24"><?php echo esc_textarea(get_option('woonotify_order_placed_template')); ?></textarea>
                    <div class="mt-1">
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-name')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Product Name</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-date')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Date</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-id')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Product Id</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-price')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Price</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-quantity')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Quantity</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','product-sku')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">SKU</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','billing-phone')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Phone</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','customer-name')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Customer Name</button>
                        <button type="button" onclick="add_to_the_template('order_placed_template','order-status')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Order Status</button>
                    </div>

                    <label class="mt-8 block text-lg text-gray-900">Order status changed</label>
                    <p class="mt-2 mb-1 text-gray-700">Below format will be used to send message when order status is changed</p>
                    
                    <textarea name="order_status_changed_template" id="order_status_changed_template" placeholder="Template to use when order is status is changed" class="text-base w-full block p-2 mt-2 bg-gray-300 text-gray-800 h-24"><?php echo esc_textarea(get_option('woonotify_order_status_changed_template')); ?></textarea>
                    <div class="mt-1">
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-name')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Product Name</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-date')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Date</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-id')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Product Id</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-price')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Price</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-quantity')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Quantity</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','product-sku')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">SKU</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','billing-phone')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Phone</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','customer-name')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Customer Name</button>
                        <button type="button" onclick="add_to_the_template('order_status_changed_template','order-status')" class="text-gray-700 bg-gray-300 p-1 rounded-sm shadow-sm">Order Status</button>
                    </div>

                    <div class="mt-5 flex">
                        <button class="py-2 mr-2 inline-flex right rounded-md shadow-sm block px-4 bg-gray-800 text-gray-100">Save Changes</button>
                        <button class="py-2 right rounded-md shadow-sm block px-4 bg-yellow-800 text-gray-100">Need Help?</button>
                    </div>
                </form>
            </div>

        </div>


    
    </div>

</div>
<script>
    var tab_list = ['#primarysettings','#advancedsettings','#morestuff','#contentsettings']
    
    function switchtab(tab){
        for (let i = 0; i < tab_list.length; i++) {
            const element = tab_list[i];
            if(tab==element){
                jQuery(tab).show()
                jQuery(tab+'btn').addClass('border-b-2')
                jQuery(tab+'btn').addClass('border-green-500')  
                jQuery(tab+'btn').addClass('text-green-500')  
                jQuery(tab+'btn').addClass('font-medium')  
                
            }
            else{
                jQuery(element).hide()
                jQuery(element+'btn').removeClass('border-b-2')
                jQuery(element+'btn').removeClass('border-green-500') 
                jQuery(element+'btn').removeClass('text-green-500')  
                jQuery(element+'btn').removeClass('font-medium')   
            }
        }
    }

    function add_to_the_template(template,block){

        var data = jQuery('#'+template).val()
        jQuery('#'+template).val(data+"["+block+"]")
    }

    jQuery('document').ready(function(){
        jQuery('#pluginmodelclose').on('click',function(){
            jQuery('#pluginmodel').hide();
            
        })
        jQuery('#wpnotify_activate').on('click',function(){
            //activate()
        })
        
        
        switchtab('#morestuff')
    });

    function activate(){
        jQuery('#checkpanelbtn').html("Checking...")
        var key = jQuery('woonotify_key').val();
        jQuery.ajax({
            type : "post",
            url : ajax_url.ajaxurl,
            data : {action: "wpnotify_activate",'key':key},
            success: function(response) {
                console.log(response);
            },
            error:function(err){
            console.log(err)
            alert("Not able to connect to server")
            }
        });
    }

</script>