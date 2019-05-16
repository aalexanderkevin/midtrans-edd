<?php

if ( ! defined( 'ABSPATH' ) ) exit;

// register midtrans installment gateway
function edd_midtrans_gateway_installment_register($gateways) {
	global $edd_options;
	$checkout_label_installment = 'Credit Card Installment via Midtrans';
	//check checkout label field from backend, then set if not null and not empty string
	if(isset($edd_options['mt_checkout_label_installment']) and $edd_options['mt_checkout_label_installment'] != ''){
		$checkout_label_installment = $edd_options['mt_checkout_label_installment'];
	}
	$gateways['midtrans_installment'] = array(
		'admin_label' => 'Midtrans Online Installment',
		'checkout_label' => __($checkout_label_installment, 'edd-midtrans')
	);
	return $gateways;
}
add_filter('edd_payment_gateways', 'edd_midtrans_gateway_installment_register');

// Section for Midtrans Installment Payment
function edd_midtrans_gateway_installment_settings_section( $sections ) {
	$sections['midtrans_installment'] = __( 'Midtrans Online Installment', 'edd-midtrans' );
	return $sections;
}
add_filter( 'edd_settings_sections_gateways', 'edd_midtrans_gateway_installment_settings_section' );

function edd_midtrans_gateway_installment_cc_form($purchase_data) {
	global $edd_options;	
	// if(isset($edd_options['mt_promo_code']) and $edd_options['mt_promo_code'] != ''){
	// 	$promo = $edd_options['mt_promo_code'];
	// }
	// else {
	// 	$promo = "onlinepromo";
	// }	
	// edd_unset_cart_discount( $promo );
	return;
}
add_action('edd_midtrans_installment_cc_form', 'edd_midtrans_gateway_installment_cc_form');

// adds the settings to the Midtrans Installment section
function edd_midtrans_gateway_installment_add_settings($settings) {
        $sandbox_key_url = 'https://dashboard.sandbox.midtrans.com/settings/config_info';
        $production_key_url = 'https://dashboard.midtrans.com/settings/config_info';

	$midtrans_installment_settings = array(
		array(
			'id' => 'edd_midtrans_installment_gateway_settings',
			'name' => '<strong>'.__('Midtrans Online Installment Settings', 'edd-midtrans').'</strong>',
			'desc' => __('Configure the gateway settings', 'edd-midtrans'),
			'type' => 'header'
		),
		array(
			'id' => 'mt_checkout_label_installment',
			'name' => __('Checkout Label Installment', 'edd-midtrans'),
			'desc' => __('<br>Payment gateway text label that will be shown as payment options to your customers (Default = "Credit Card Installment via Midtrans")', 'edd-midtrans'),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_merchant_id',
			'name' => __('Merchant ID', 'edd-midtrans'),
			'desc' => sprintf(__('<br>Input your Midtrans Merchant ID (e.g M012345). Get the ID <a href="%s" target="_blank">here</a>', 'edd-midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),			
		array(
			'id' => 'mt_installment_production_server_key',
			'name' => __('Production Server Key', 'edd-midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'edd-midtrans' ),$production_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_production_client_key',
			'name' => __('Production Client Key', 'edd-midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Production Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'edd-midtrans' ),$production_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_installment_sandbox_server_key',
			'name' => __('Sandbox Server Key', 'edd-midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Server Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'edd-midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),
		array(
			'id' => 'mt_installment_sandbox_client_key',
			'name' => __('Sandbox Client Key', 'edd-midtrans'),
			'desc' => sprintf(__('<br>Input your <b>Sandbox Midtrans Client Key</b>. Get the key <a href="%s" target="_blank">here</a>', 'edd-midtrans' ),$sandbox_key_url),
			'type' => 'text',
		),		
		array(
			'id' => 'mt_installment_min_amount',
			'name' => __('Minimal Transaction Amount', 'edd-midtrans'),
			'desc' => __('<br>Minimal transaction amount allowed to be paid with installment. (amount in IDR, without comma or period) example: 500000 </br> if the transaction amount is below this value, customer will be redirected to Credit Card fullpayment page', 'edd-midtrans'),
			'type' => 'text',
		),	
		array(
			'id' => 'mt_installment_3ds',
			'name' => __('Enable 3D Secure', 'edd-midtrans'),
			'desc' => __('You must enable 3D Secure. Please contact us if you wish to disable this feature in the Production environment.', 'edd-midtrans'),
			'type' => 'checkbox',
		),
		array(
			'id' => 'mt_installment_save_card',
			'name' => __('Enable Save Card', 'edd-midtrans'),
			'desc' => __('This will allow your customer to save their card on the payment popup, for faster payment flow on the following purchase', 'edd-midtrans'),
			'type' => 'checkbox',
		),	
		array(
			'id' => 'mt_installment_enable_redirect',
			'name' => __('Enable Payment Page Redirection', 'edd-midtrans'),
			'desc' => __('This will redirect customer to Midtrans hosted payment page instead of popup payment page on your website. <br> <b>Leave it disabled if you are not sure</b>', 'edd-midtrans'),
			'type' => 'checkbox',	
		),
		array(
			'id' => 'mt_enable_mixpanel',
			'name' => __('Allowed midtrans to activate plugin analytic', 'edd-midtrans'),
			'desc' => __('This will enabling midtrans for tracking user on snap.js event (onSuccess, onPending, onError, onClose) internally for analytic purposes', 'edd-midtrans'),
			'type' => 'checkbox',	
		),
		array(
			'id' => 'mt_installment_custom_field',
			'name' => __('Custom fields', 'edd-midtrans'),
			'desc' => __('<br>This will allow you to set custom fields that will be displayed on Midtrans dashboard. <br>Up to 3 fields are available, separate by coma (,) <br> Example:  Order from web, Processed', 'edd-midtrans'),
			'type' => 'text',
		),				
	);
    if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
        $midtrans_installment_settings = array( 'midtrans_installment' => $midtrans_installment_settings );
    }
	return array_merge($settings, $midtrans_installment_settings);	
}
add_filter('edd_settings_gateways', 'edd_midtrans_gateway_installment_add_settings');


function edd_midtrans_gateway_installment_plugin_action_links( $links ) {

    $settings_link = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=download&page=edd-settings&tab=gateways&section=midtrans_installment' ) . '" title="Settings">Settings</a>'
    );

    return array_merge( $settings_link, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'edd_midtrans_gateway_installment_plugin_action_links' );

// installment procces
function edd_midtrans_gateway_installment_payment($purchase_data) {
	global $edd_options;
	require_once plugin_dir_path( __FILE__ ) . '/lib/Veritrans.php';
	/**********************************
	* set transaction mode
	**********************************/
	if(edd_is_test_mode()) {
		// set Sandbox credentials here
		Veritrans_Config::$isProduction = false;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_sandbox_server_key'];
		$client_key = $edd_options['mt_installment_sandbox_client_key'];
		$snap_script_url = "https://app.sandbox.midtrans.com/snap/snap.js";
		$mixpanel_key = "9dcba9b440c831d517e8ff1beff40bd9";		
	} else {
		// set Prouction credentials here
		Veritrans_Config::$isProduction = true;
		Veritrans_Config::$serverKey = $edd_options['mt_installment_production_server_key'];
		$client_key = $edd_options['mt_installment_production_client_key'];
		$snap_script_url = "https://app.midtrans.com/snap/snap.js";
		$mixpanel_key = "17253088ed3a39b1e2bd2cbcfeca939a";
	}
 
	// check for any stored errors
	$errors = edd_get_errors();
	if(!$errors) {
		$purchase_summary = edd_get_purchase_summary($purchase_data);
		/**********************************
		* setup the payment details
		**********************************/
		$payment = array( 
			'price' => $purchase_data['price'], 
			'date' => $purchase_data['date'], 
			'user_email' => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency' => $edd_options['currency'],
			'downloads' => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info' => $purchase_data['user_info'],
			'status' => 'pending'
		);
 
		// record the pending payment
		$payment = edd_insert_payment($payment);
		// create item
		$transaction_details = array();
		foreach($purchase_data['cart_details'] as $item){
			$mt_item = array(
				'id' => $item['id'],
				'price' => intval($item['price']),
				'quantity' => $item['quantity'],
				'name' => $item['name']
			);
			array_push($transaction_details, $mt_item);
		};

		$fees = edd_get_cart_fees();
		if (!empty($fees)) {
			$keys = array_keys($fees);
			for ($i = 0; $i < count($fees); $i++) {
				$id = $keys[$i];
				$mt_fee = array(
					'id' => $id,
					'price' => intval($fees[$id]['amount']),
					'quantity' => 1,
					'name' => $fees[$id]['label']
				);
				array_push($transaction_details, $mt_fee);
			};
		}
		
		$edd_get_base_url = home_url( '/');
		$finish_url = esc_url_raw( add_query_arg( array( 'confirmation_page' => 'midtrans','nonce'  => wp_create_nonce('edd_midtrans_gateway' . $payment) ), home_url( 'index.php' ) ) );
		$mt_params = array(
			'transaction_details' => array(
				'order_id' 			=> $payment,
				'gross_amount' 		=> intval($purchase_data['price'])
				),
			'customer_details' 	=> array(
				'first_name' 		=> $purchase_data['user_info']['first_name'],
				'last_name' 		=> $purchase_data['user_info']['last_name'],
				'email' 			=> $purchase_data['user_info']['email'],
        		'phone'       		=> $purchase_data['post_data']['edd_phone'],
				'billing_address' 	=> array(
					'first_name' 		=> $purchase_data['user_info']['first_name'],
					'last_name' 		=> $purchase_data['user_info']['last_name'],
					),
				),
			'enabled_payments' => ['credit_card'],
			'credit_card' => array(
        		'secure' => $edd_options['mt_installment_3ds'] ? true : false,
    		),
			'callbacks' => array(
				'finish' => $finish_url,
			),
			'item_details' => $transaction_details
		);
        if ($edd_options['mt_installment_save_card'] && is_user_logged_in()){
          $mt_params['user_id'] = crypt( $purchase_data['user_info']['email'].$purchase_data['post_data']['edd_phone'] , Veritrans_Config::$serverKey );
        }
    	if ($edd_options['mt_installment_save_card']){
          $mt_params['credit_card']['save_card'] = true;
      	}          
        if($mt_params['transaction_details']['gross_amount'] >= $edd_options['mt_installment_min_amount'])
        {
          $terms      = array(3,6,9,12,15,18,21,24,27,30,33,36);
          $mt_params['credit_card']['installment']['required'] = true;
          $mt_params['credit_card']['installment']['terms'] = array(
              'bri' => $terms, 
              'maybank' => $terms,
              'bri' => $terms,
              'bni' => $terms, 
              'mandiri' => $terms, 
              'cimb' => $terms,
              'bca' => $terms
            );
        }
        // add custom fields params
        $custom_fields_params = explode(",",$edd_options["mt_installment_custom_field"]);
        if ( !empty($custom_fields_params[0]) ){
          $mt_params['custom_field1'] = $custom_fields_params[0];
          $mt_params['custom_field2'] = !empty($custom_fields_params[1]) ? $custom_fields_params[1] : null;
          $mt_params['custom_field3'] = !empty($custom_fields_params[2]) ? $custom_fields_params[2] : null;
        }                       
   		// get rid of cart contents
		edd_empty_cart();
		// Snap Request Process
			try{          
				$snapResponse = Veritrans_Snap::createTransaction($mt_params);
				$snapRedirectUrl = $snapResponse->redirect_url;
				$snapToken = $snapResponse->token;
			}
			catch(Exception $e) {
  				echo 'Error: ' .$e->getMessage();
  				exit;
			}
		get_header();

		if ($edd_options["mt_installment_enable_redirect"]){
			wp_redirect($snapRedirectUrl);
		}
		else{
		try{
		?>

		<?php if ($edd_options["mt_enable_mixpanel"]){ ?>
		<!-- start Mixpanel -->
		<script type="text/javascript">(function(c,a){if(!a.__SV){var b=window;try{var d,m,j,k=b.location,f=k.hash;d=function(a,b){return(m=a.match(RegExp(b+"=([^&]*)")))?m[1]:null};f&&d(f,"state")&&(j=JSON.parse(decodeURIComponent(d(f,"state"))),"mpeditor"===j.action&&(b.sessionStorage.setItem("_mpcehash",f),history.replaceState(j.desiredHash||"",c.title,k.pathname+k.search)))}catch(n){}var l,h;window.mixpanel=a;a._i=[];a.init=function(b,d,g){function c(b,i){var a=i.split(".");2==a.length&&(b=b[a[0]],i=a[1]);b[i]=function(){b.push([i].concat(Array.prototype.slice.call(arguments,0)))}}var e=a;"undefined"!==typeof g?e=a[g]=[]:g="mixpanel";e.people=e.people||[];e.toString=function(b){var a="mixpanel";"mixpanel"!==g&&(a+="."+g);b||(a+=" (stub)");return a};e.people.toString=function(){return e.toString(1)+".people (stub)"};l="disable time_event track track_pageview track_links track_forms track_with_groups add_group set_group remove_group register register_once alias unregister identify name_tag set_config reset opt_in_tracking opt_out_tracking has_opted_in_tracking has_opted_out_tracking clear_opt_in_out_tracking people.set people.set_once people.unset people.increment people.append people.union people.track_charge people.clear_charges people.delete_user people.remove".split(" ");for(h=0;h<l.length;h++)c(e,l[h]);var f="set set_once union unset remove delete".split(" ");e.get_group=function(){function a(c){b[c]=function(){call2_args=arguments;call2=[c].concat(Array.prototype.slice.call(call2_args,0));e.push([d,call2])}}for(var b={},d=["get_group"].concat(Array.prototype.slice.call(arguments,0)),c=0;c<f.length;c++)a(f[c]);return b};a._i.push([b,d,g])};a.__SV=1.2;b=c.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===c.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";d=c.getElementsByTagName("script")[0];d.parentNode.insertBefore(b,d)}})(document,window.mixpanel||[]);mixpanel.init("<?php echo $mixpanel_key ?>");</script> 
		<!-- end Mixpanel -->
		<?php } ?>

        	<script src="<?php echo $snap_script_url;?>" data-client-key="<?php echo $client_key;?>"></script>
        	<center><p><b><h2 class="alert alert-info">Please complete your payment...</h2></b></p>
        	<p>Continue payment via payment popup window.<br>Or click button below: </p>
	    	<button id="pay-button">Proceed to Payment</button> </center>
        	<script type="text/javascript">
        		<?php if ($edd_options["mt_enable_mixpanel"]){ ?>
        		function MixpanelTrackResult(snap_token, merchant_id, cms_name, cms_version, plugin_name, plugin_version, status, result) {
  					var eventNames = {
    					pay: 'pg-pay',
    					success: 'pg-success',
    					pending: 'pg-pending',
    					error: 'pg-error',
    					close: 'pg-close'
  					};
  					mixpanel.track(
    					eventNames[status], {
      						merchant_id: merchant_id,
      						cms_name: cms_name,
      						cms_version: cms_version,
      						plugin_name: plugin_name,
      						plugin_version: plugin_version,
      						snap_token: snap_token,
      						payment_type: result ? result.payment_type: null,
      						order_id: result ? result.order_id: null,
      						status_code: result ? result.status_code: null,
      						gross_amount: result && result.gross_amount ? Number(result.gross_amount) : null,
    					}
  					);
				}
				<?php } ?>
				var MID_SNAP_TOKEN = "<?=$snapToken?>";
				var MID_MERCHANT_ID = "<?=$edd_options["mt_installment_merchant_id"];?>";
				var MID_CMS_NAME = "easy digital downloads";
				var MID_CMS_VERSION = "<?php echo EDD_VERSION;?>";
				var MID_PLUGIN_NAME = "online installment";
				var MID_PLUGIN_VERSION = "<?php echo EDD_MIDTRANS_PLUGIN_VERSION;?>";
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
        		var retryCount = 0;
        		var snapExecuted = false;
        		var intervalFunction = 0;
      			document.getElementById('pay-button').onclick = function(){
      				popup();
      			}	
      			popup();
      		// Continously retry to execute SNAP popup if fail, with 1000ms delay between retry
      		function popup(){
        		intervalFunction = setInterval(function() {
        			try{
            			snap.pay(MID_SNAP_TOKEN,{
    						onSuccess: function(result){
    						    <?php if ($edd_options["mt_enable_mixpanel"]){ ?>
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'success', result);
								<?php } ?>
      							window.location = result.finish_redirect_url; 
    						},
    						onPending: function(result){
    						    <?php if ($edd_options["mt_enable_mixpanel"]){ ?>
	      						MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pending', result);
	      						<?php } ?>
	      						window.location = result.finish_redirect_url;
    						},
    						onError: function(result){
    						    <?php if ($edd_options["mt_enable_mixpanel"]){ ?>
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'error', result);
	      						<?php } ?>
    						},
    						onClose: function(){
    						    <?php if ($edd_options["mt_enable_mixpanel"]){ ?>
      							MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'close', null);
	      						<?php } ?>
    						}
    					});
            			snapExecuted = true; // if SNAP popup executed, change flag to stop the retry.
         			}
          			catch (e){ 
            			retryCount++;
            			if(retryCount >= 10){
              				location.reload(); 
              				return;
            			}
          				console.log(e);
          				console.log("Snap not ready yet... Retrying in 1000ms!");
          			}
          			finally {
            			if (snapExecuted) {
              			 clearInterval(intervalFunction);
            			 // record 'pay' event to Mixpanel
						 <?php if ($edd_options["mt_enable_mixpanel"]){ ?>
      					 MixpanelTrackResult(MID_SNAP_TOKEN, MID_MERCHANT_ID, MID_CMS_NAME, MID_CMS_VERSION, MID_PLUGIN_NAME, MID_PLUGIN_VERSION, 'pay', null);
						 <?php } ?>
           			}
          			}
        		}, 1000);
        	}
        	</script>        
			<?php          
      	}
      	catch (Exception $e) {
        error_log($e->getMessage());
      	}
      	} 
		get_footer();
	}
	else {
		$fail = true;
		// if errors are present, send the user back to the purchase page so they can be corrected
		edd_send_back_to_checkout('?payment-mode=' . $purchase_data['post_data']['edd-gateway']);
	}
}
add_action('edd_gateway_midtrans_installment', 'edd_midtrans_gateway_installment_payment');
