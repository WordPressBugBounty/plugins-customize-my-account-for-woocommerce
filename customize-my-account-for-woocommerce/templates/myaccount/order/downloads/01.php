<?php
/**
 * Order Downloads.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wcmamtx_custom_download_design woocommerce-MyAccount-downloads">
    <?php if ( ! empty( $downloads ) ) : ?>
        <div class="wc-downloads-grid">
            <?php foreach ( $downloads as $download ) : ?>
                <div class="wc-download-card">
                    <div class="wc-download-icon">
                        <i class="fa fa-download"></i>
                    </div>
                    
                    <div class="wc-download-details">
                        <h3><?php echo esc_html( $download['product_name'] ); ?></h3>
                        <p class="download-file-name"><?php echo esc_html( $download['download_name'] ); ?></p>
                        
                        <?php if ( ! empty( $download['downloads_remaining'] ) ) : ?>
                            <span class="wc-download-remaining">
                                <?php echo esc_html( $download['downloads_remaining'] ); ?> <?php esc_html_e( 'left', 'customize-my-account-for-woocommerce-pro'  ); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $download['access_expires'] ) ) : ?>
                            <span class="wc-download-expires">
                                Expires: <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="wc-download-action">
                        <a href="<?php echo esc_url( $download['download_url'] ); ?>" class="woocommerce-button button download-button">
                            <?php esc_html_e( 'Download Now', 'customize-my-account-for-woocommerce-pro'  ); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="woocommerce-message woocommerce-message--info woocommerce-InfoMessage">
            <?php esc_html_e( 'No downloads available yet.', 'customize-my-account-for-woocommerce-pro'  ); ?>
        </div>
    <?php endif; ?>
</div>