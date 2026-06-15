<?php
if (!function_exists('wcmamtx_third_party_goahead_check')) {

	function wcmamtx_third_party_goahead_check($key) {
		$third_party_go_ahead = 'yes';

		$third_party_plug_array = array(

	// Wallets
			'my-wallet'                 => array('woo-wallet/woo-wallet.php'),
			'woo-wallet'                 => array('woo-wallet/woo-wallet.php'),
			'wallet'                     => array('wallet-system-for-woocommerce/wallet-system-for-woocommerce.php'),

	// Smart Coupons
			'wt-smart-coupon'            => array('wt-smart-coupons-for-woocommerce/wt-smart-coupon.php'),
			'my-coupons'                 => array('wt-smart-coupons-for-woocommerce/wt-smart-coupon.php'),

	// Subscriptions
			'subscriptions'              => array('woocommerce-subscriptions/woocommerce-subscriptions.php'),
			'my-subscription'            => array(
				'yith-woocommerce-subscription-premium/init.php',
				'yith-woocommerce-subscription/init.php'
			),
			'wps_subscriptions'          => array('subscriptions-for-woocommerce/subscriptions-for-woocommerce.php'),

	// Memberships
			'memberships'                => array('woocommerce-memberships/woocommerce-memberships.php'),
			'membership-account'         => array('paid-memberships-pro/paid-memberships-pro.php'),

	// Wishlist
			'wishlist'                   => array(
				'yith-woocommerce-wishlist/init.php',
				'yith-woocommerce-wishlist-premium/init.php',
				'ti-woocommerce-wishlist/ti-woocommerce-wishlist.php'
			),

	// Affiliates
			'affiliate-dashboard'        => array(
				'yith-woocommerce-affiliates-premium/init.php',
				'yith-woocommerce-affiliates/init.php'
			),
			'affiliates'                 => array(
				'affiliate-wp/affiliate-wp.php',
				'yith-woocommerce-affiliates/init.php'
			),
			'affiliate-area'             => array('slicewp/index.php'),
			'rtwalwm_affiliate_menu'     => array('affiliaa-affiliate-program-with-mlm/wp-wc-affiliate-program.php'),

	// Points & Rewards
			'points'                     => array(
				'woocommerce-points-and-rewards/woocommerce-points-and-rewards.php',
				'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php'
			),
			'rewards'                    => array('wployalty/wployalty.php'),
			'my-points'                  => array('mycred/mycred.php'),

	// Auctions
			'my-auction-setting'         => array('ultimate-woocommerce-auction/ultimate-woocommerce-auction.php'),
			'my-auction'                 => array('ultimate-woocommerce-auction/ultimate-woocommerce-auction.php'),
			'my-auction-watchlist'       => array('ultimate-woocommerce-auction/ultimate-woocommerce-auction.php'),

	// Vendors / Marketplace
			'vendor-dashboard'           => array(
				'dokan-lite/dokan.php',
				'wc-vendors/class-wc-vendors.php'
			),
			'store-manager'              => array('wc-multivendor-marketplace/wc-multivendor-marketplace.php'),
			'dashboard'                  => array('wc-frontend-manager/wc_frontend_manager.php'),

	// Bookings
			'bookings'                   => array('woocommerce-bookings/woocommerce-bookings.php'),
			'appointments'              => array(
				'bookly-responsive-appointment-booking-tool/main.php',
				'woocommerce-appointments/woocommerce-appointments.php'
			),

	// LMS
			'enrolled-courses'           => array('tutor/tutor.php'),
			'courses'                    => array(
				'sfwd-lms/sfwd_lms.php',
				'lifterlms/lifterlms.php'
			),

	// Support
			'tickets'                    => array('awesome-support/awesome-support.php'),
			'support'                    => array(
				'supportcandy/supportcandy.php',
				'fluent-support/fluent-support.php'
			),

	// License Managers
			'licenses'                   => array(
				'license-manager-for-woocommerce/license-manager-for-woocommerce.php',
				'woocommerce-software-add-on/software-add-on.php'
			),

	// PDF Invoices
			'downloads-invoices'         => array(
				'woocommerce-pdf-invoices-packing-slips/woocommerce-pdf-invoices-packingslips.php'
			),

	// Store Credit
			'store-credit'               => array(
				'pw-woocommerce-gift-cards/pw-gift-cards.php',
				'woocommerce-account-funds/woocommerce-account-funds.php'
			),

	// RMA
			'returns'                    => array(
				'woocommerce-rma-for-return-refund-and-exchange/woocommerce-rma-for-return-refund-and-exchange.php'
			),

	// Deposits
			'deposits'                   => array(
				'woocommerce-deposits/woocommerce-deposits.php'
			),

	// Product Questions
			'questions'                  => array(
				'yith-woocommerce-questions-and-answers/init.php'
			),

	// Waitlists
			'waitlists'                  => array(
				'woocommerce-waitlist/woocommerce-waitlist.php'
			),

	// Product Vendors
			'vendor-orders'              => array(
				'woocommerce-product-vendors/woocommerce-product-vendors.php'
			),

	// Support Tickets for Customers
			'customer-tickets'           => array(
				'awesome-support/awesome-support.php'
			),

	        // Referral Systems
			'referrals'                  => array(
				'referral-system-for-woocommerce/referral-system-for-woocommerce.php'
			),

	        // Gift Cards
			'gift-cards'                 => array(
				'woocommerce-gift-cards/woocommerce-gift-cards.php'
			),

		);

		

		if (isset($third_party_plug_array[$key])) {
			$third_party_plug_slugs = $third_party_plug_array[$key];
		} else {
			return 'no';
		}

		$match_index = 0;


		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		foreach ($third_party_plug_slugs as $ptvalue) {

			if ( is_plugin_active( $ptvalue )) {
                $match_index++;
		    }

		}

		if ( $match_index == 0) {
            $third_party_go_ahead = 'no';
		}

		return $third_party_go_ahead;
	}

}
?>