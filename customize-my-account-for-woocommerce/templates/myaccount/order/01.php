<?php
/**
 * My Account Orders
 *
 * Shows orders in table view.
 *
 * This template can be overridden by copying it to your theme your-theme/sysbasics-myaccount/orders.php , for better practice create your child theme and copy it to your-child-theme/sysbasics-myaccount/orders.php.
 *
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

			<div class="wcmam-orders-wrapper">

				<div class="wcmam-order-filters">
					<button class="btn btn-primary wcmam-order-filters-button active" data-filter="all" class="active"><?php echo esc_html__('All','customize-my-account-for-woocommerce-pro'); ?></button>
					<?php

					$statuses = wc_get_order_statuses();

					foreach ($statuses as $skey=>$svalue) { 

						$skey = substr($skey, 3);
						?>

						<button class="btn btn-primary wcmam-order-filters-button" data-filter="<?php echo $skey; ?>"><?php echo $svalue; ?></button>

						<?php

					}

					?>
					

				</div>

				<?php

				foreach ( $customer_orders->orders as $customer_order ) {
				    $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				    $item_count = $order->get_item_count() - $order->get_item_count_refunded();
				    ?>

				    <div class="wcmam-order-card" data-status="<?php echo $order->get_status(); ?>">

				    	<div class="wcmam-order-header">
				    		<span class="status <?php echo $order->get_status(); ?>">
				    			<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
				    		</span>

				    		<span class="order-date">
				    			<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
				    		</span>
				    	</div>

				    	<div class="wcmam-order-body">

				    		<?php 

				    		    $thubm_count = 0;

				    			foreach ( $order->get_items() as $item_id => $item ) {

                                    // Get the associated product object
				    				$product = $item->get_product();

				    				if ( $product ) {
                                        // Echo the HTML img tag of the product thumbnail
				    					$image_url = $product->get_image(); 

				    					if (isset($image_url) && ($image_url != "")) {
				    						$thubm_count++;
				    					}
				    				}
				    			}
				    		?>

				    		<div class="product-thumb <?php if ($thubm_count > 1) { echo 'multiple'; } ?>">
				    			<?php 

				    			$thubm_count_loop = 0;

				    			foreach ( $order->get_items() as $item_id => $item ) {

                                    // Get the associated product object
				    				$product = $item->get_product();

				    				if (( $product ) && ($thubm_count_loop < 4)) {
                                        // Echo the HTML img tag of the product thumbnail
				    					echo $product->get_image(); 

				    					$thubm_count_loop++;
				    				}
				    			}
				    			?>
				    		</div>

				    		<div class="order-details">
				    			<h4>
				    				<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo esc_html( _x( '#', 'hash before order number', 'customize-my-account-for-woocommerce-pro' ) . $order->get_order_number() ); ?>
								    </a>
							    </h4>

							    <p class="wcmamtx_order_items_01">
							    	<?php

							    	$items = '';

							    	if ( $order ) {
							    		// 2. Loop through the order items
							    		foreach ( $order->get_items() as $item_id => $item ) {

							    			// 3. Get the order item name
							    			$item_name = $item->get_name();

							    			$item_quantity = $item->get_quantity();

							    			// Output or use the item name
							    			$items .= ''.$item_name.' x '.$item_quantity.',';
							    		}
							    	}

							    	$items = substr($items, 0, -1); 

							    	echo $items;

							    	?>
							    </p>

							    <div class="order-total">
							    	<?php

							    	echo wp_kses_post( sprintf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'customize-my-account-for-woocommerce-pro' ), $order->get_formatted_order_total(), $item_count ) );
							    	?>
							    </div>
				    		</div>

				    		<div class="order-arrow view">
				    			<a title="<?php echo esc_html__( 'View Order', 'customize-my-account-for-woocommerce-pro' ); ?>" href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
				    				<i class="fa fa-eye"></i>
				    			</a>
				    		</div>

				    		<?php
				    		$actions = wc_get_account_orders_actions( $order );

				    		if ( ! empty( $actions ) ) {
				    			unset($actions['order-again']);
				    			foreach ( $actions as $key => $action ) { 


                                    // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				    				if ( empty( $action['aria-label'] ) ) {
                                            // Generate the aria-label based on the action name.
				    					/* translators: %1$s Action name, %2$s Order number. */
				    					$action_aria_label = sprintf( __( '%1$s order number %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
				    				} else {
				    					$action_aria_label = $action['aria-label'];
				    				}

				    				if ($key != "view") {

				    					$icon_html = '';

				    					switch($key) {

				    						case "pay":
				    						$icon_html = '<i class="fa fa-bag-shopping"></i>';
				    						break;

				    						case "cancel":
				    						$icon_html = '<i class="fa fa-times"></i>';
				    						break;


				    					}

				    					?>
				    					<div class="order-arrow <?php echo $key; ?>">
				    						<a title="<?php echo $action_aria_label; ?>" href="<?php echo esc_url( $action['url'] ); ?>">
				    							<?php echo $icon_html; ?>
				    						</a>
				    					</div>
                                        <?php


				    				}
				    				unset( $action_aria_label );
				    			}
				    		}
				    		?>

				    	</div>

				    </div>

				<?php } ?>


			</div>


	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'customize-my-account-for-woocommerce-pro' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button<?php echo esc_attr( $wp_button_class ); ?>" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'customize-my-account-for-woocommerce-pro' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<?php wc_print_notice( esc_html__( 'No order has been made yet.', 'customize-my-account-for-woocommerce-pro' ) . ' <a class="woocommerce-Button button' . esc_attr( $wp_button_class ) . '" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">' . esc_html__( 'Browse products', 'customize-my-account-for-woocommerce-pro' ) . '</a>', 'notice' ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment ?>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>