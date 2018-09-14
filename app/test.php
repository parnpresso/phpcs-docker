
<?php

/*
Add user meta TG type to boday class
*/
$user_id = get_current_user_id();
$user_org = get_user_meta( $user_id,'Organization', true );

if ( $user_org == 'Thai Airways' ) {
    add_filter( 'body_class', function( $classes ) {
	    return array_merge( $classes, array( 'tg-user' ) );
	} );
}


/*
Change title of order received page
*/
add_filter( 'the_title', 'woo_title_order_received', 10, 2 );
function woo_title_order_received( $title, $id ) {
	if ( function_exists( 'is_order_received_page' ) &&
	     is_order_received_page() && get_the_ID() === $id ) {
		$title = "Booking Success";
	}
	return $title;
}

/*
Add user meta TG type to boday class
*/
// add_filter('body_class','add_meta_body');
// function add_meta_body($classes, $class) {
// 	if ( get_user_meta( $user_id,'Organization', true ) == "Thai Airways" ) {
// 		$classes = 'tg-user';
// 	}
// 	return $classes;
// }


/*
Change Place Order button text on checkout page in woocommerce
*/
add_filter('woocommerce_order_button_text','bacpact_order_button_text',1);
function bacpact_order_button_text($order_button_text) {
    $order_button_text = 'Book Now';
    return $order_button_text;
}

/**
 * Remove payment method inside woocommerce email
 */
add_filter( 'woocommerce_get_order_item_totals', 'bacpact_woocommerce_get_order_item_totals' );
function bacpact_woocommerce_get_order_item_totals( $totals ) {
  unset( $totals['payment_method'] );
  return $totals;
}


add_filter( 'woocommerce_product_single_add_to_cart_text', 'bacpact_custom_product_add_to_cart_text' );
add_filter( 'woocommerce_product_add_to_cart_text', 'bacpact_custom_product_add_to_cart_text' );  // 2.1 +
function bacpact_custom_product_add_to_cart_text() {

    return __( 'Book', 'woocommerce' );

}


/*** Reidrect add to cart to checkout ***/
add_filter ('add_to_cart_redirect', 'bacpact_redirect_to_checkout');

function bacpact_redirect_to_checkout() {
    global $woocommerce;
    $checkout_url = $woocommerce->cart->get_checkout_url();
    return $checkout_url;
}

add_action( 'show_user_profile', 'bacpact_my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'bacpact_my_show_extra_profile_fields' );

function bacpact_my_show_extra_profile_fields( $user ) { ?>

	<h3>Extra profile information</h3>

	<table class="form-table">

		<tr>
			<th><label for="organization">Organization</label></th>

			<td>
				<input type="text" name="organization" id="organization" value="<?php echo esc_attr( get_user_meta( $user->ID,'Organization', true ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your organization.</span>
			</td>
		</tr>
		<tr>
			<th><label for="company-name">Company Name</label></th>

			<td>
				<input type="text" name="company-name" id="company-name" value="<?php echo esc_attr( get_user_meta( $user->ID,'CompanyName', true ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your company name.</span>
			</td>
		</tr>
		<tr>
			<th><label for="license-category">License Category</label></th>

			<td>
				<input type="text" name="license-category" id="license-category" value="<?php echo esc_attr( get_user_meta( $user->ID,'LicenseCategory', true ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your license category.</span>
			</td>
		</tr>
		<tr>
			<th><label for="license-no">License No.</label></th>

			<td>
				<input type="text" name="license-no" id="license-no" value="<?php echo esc_attr( get_user_meta( $user->ID,'LicenseNo', true ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your license no.</span>
			</td>
		</tr>
		<tr>
			<th><label for="score">Score</label></th>

			<td>
				<input type="text" name="score" id="score" value="<?php echo esc_attr( get_user_meta( $user->ID,'Score', true ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your score.</span>
			</td>
		</tr>

	</table>
<?php }

add_action( 'personal_options_update', 'bacpact_my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'bacpact_my_save_extra_profile_fields' );
add_action( 'edit_user_profile', 'bacpact_my_save_extra_profile_fields' );


function bacpact_my_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'Organization', $_POST['organization'] );
	update_usermeta( $user_id, 'CompanyName', $_POST['company-name'] );
	update_usermeta( $user_id, 'LicenseCategory', $_POST['license-category'] );
	update_usermeta( $user_id, 'LicenseNo', $_POST['license-no'] );
	update_usermeta( $user_id, 'Score', $_POST['score'] );
}
