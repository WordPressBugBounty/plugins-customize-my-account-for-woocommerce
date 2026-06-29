<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

$wcmamtx_layout = wcmamtx_get_layout();
?>
<style>
.wcmam-pill-nav {
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 16px;
    padding: 16px 10px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    gap: 2px;
    font-family: inherit;
    width: 27%;
    float: left;
    margin-right: 1%;
}
@media (max-width: 800px) {
    .wcmam-pill-nav { float: none; width: 100%; margin-right: 0; }
}
.wcmam-pill-nav .wcmam-pill-avatar {
    margin-bottom: 12px;
    text-align: center;
}
.wcmam-pill-nav a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-radius: 10px;
    text-decoration: none;
    color: #444;
    font-size: 14px;
    font-weight: 500;
    transition: background .2s, color .2s;
    position: relative;
}
.wcmam-pill-nav a:hover {
    background: #f5f6fa;
    color: #2563eb;
    text-decoration: none;
}
.wcmam-pill-nav a.is-active,
.wcmam-pill-nav a.woocommerce-MyAccount-navigation-link--active {
    background: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}
.wcmam-pill-nav a.is-active::before,
.wcmam-pill-nav a.woocommerce-MyAccount-navigation-link--active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 60%;
    background: #2563eb;
    border-radius: 0 3px 3px 0;
}
.wcmam-pill-nav .wcmam-pill-sep {
    height: 1px;
    background: #f0f0f0;
    margin: 6px 2px;
}
.wcmam-pill-nav .wcmam-pill-heading {
    font-size: 11px;
    font-weight: 700;
    color: #aaa;
    letter-spacing: .8px;
    text-transform: uppercase;
    padding: 4px 14px;
}
@media (max-width: 768px) {
    .wcmam-pill-nav { border-radius: 10px; padding: 10px 6px; }
}
</style>

<nav class="wcmam-pill-nav <?php echo esc_attr( $menu_position_extra_class ); ?>">
    <?php
    $show_avatar     = 'yes';
    $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings' );

    if ( ! isset( $avatar_settings['disable_avatar'] ) ) {
        $avatar_settings['disable_avatar']        = 'yes';
        $avatar_settings['custom_avatar_content'] = 'yes';
    }

    $_fresh_avatar = (array) get_option( 'wcmamtx_avatar_settings' );
    if ( array_key_exists( 'custom_avatar_content', $_fresh_avatar ) ) {
        $avatar_settings['custom_avatar_content'] = $_fresh_avatar['custom_avatar_content'];
    }

    $show_avatar = ( isset( $avatar_settings['disable_avatar'] ) && $avatar_settings['disable_avatar'] === 'yes' ) ? 'yes' : 'no';

    if ( $show_avatar === 'yes' ) {
        echo '<div class="wcmam-pill-avatar">';
        echo ( new wcmamtx_upload_avatar_tab() )->wcmamtx_shortcode();
        echo '</div>';
    }

    if ( isset( $avatar_settings['custom_avatar_content'] ) && $avatar_settings['custom_avatar_content'] === 'yes' && $show_avatar === 'yes' ) {
        $editor_content_avatar = isset( $avatar_settings['content_avatar'] )
            ? $avatar_settings['content_avatar']
            : '<p class="wcmamtx_default_text_below_avatar" style="text-align:center;">Hello <strong>{display_name}</strong> (not <strong>{display_name}</strong>? <a href="{user_logout_link}">Log out</a>)</p>';
        echo '<div id="wcmamtx-avatar-content-output">' . wp_kses_post( wcmamtx_parse_smart_tag_function( $editor_content_avatar ) ) . '</div>';
    }

    foreach ( $wcmamtx_tabs as $key => $value ) {

        $name = ( isset( $value['endpoint_name'] ) && $value['endpoint_name'] !== '' ) ? $value['endpoint_name'] : $value;

        $should_show = 'yes';

        if ( isset( $value['visibleto'] ) && $value['visibleto'] !== 'all' ) {
            $is_visible = wcmamtx_check_role_visibility(
                isset( $value['roles'] ) ? $value['roles'] : '',
                $value['visibleto'],
                isset( $value['users'] ) ? $value['users'] : []
            );
        } else {
            $is_visible = 'yes';
        }

        if ( isset( $value['show'] ) && $value['show'] === 'no' ) {
            $should_show = 'no';
        }

        $extraclass = ( isset( $value['class'] ) && $value['class'] !== '' ) ? str_replace( ',', ' ', $value['class'] ) : '';

        if ( isset( $value['endpoint_key'] ) && $value['endpoint_key'] !== '' ) {
            $key = $value['endpoint_key'];
        }

        $parent      = ( isset( $value['parent'] ) && $value['parent'] !== '' ) ? $value['parent'] : 'none';
        $icon_source = isset( $value['icon_source'] ) ? $value['icon_source'] : 'default';

        if ( isset( $value['hide_in_navigation'] ) && $value['hide_in_navigation'] == '01' ) {
            $should_show = 'no';
        }

        $third_party = isset( $value['third_party'] ) ? $value['third_party'] : null;
        if ( isset( $third_party ) && wcmamtx_third_party_goahead_check( $key ) === 'no' ) {
            $should_show = 'no';
        }

        if ( $should_show !== 'yes' || $is_visible !== 'yes' ) {
            continue;
        }

        $wcmamtx_type = ( isset( $value['wcmamtx_type'] ) && is_array( $value ) ) ? $value['wcmamtx_type'] : 'endpoint';

        if ( $wcmamtx_type === 'group' ) {
            wcmamtx_get_account_menu_group_html( $name, $key, $value, $icon_extra_class, $extraclass, $icon_source );
        } elseif ( $parent === 'none' ) {
            if ( $wcmamtx_type === 'separater' ) {
                echo '<div class="wcmam-pill-sep"></div>';
            } elseif ( $wcmamtx_type === 'heading' ) {
                echo '<div class="wcmam-pill-heading">' . esc_html( $name ) . '</div>';
            } else {
                $active_class = wc_is_current_account_menu_item( $key ) ? 'is-active' : '';
                ?>
                <a class="<?php echo esc_attr( wcmamtx_get_account_menu_item_classes( $key, $value ) ); ?> <?php echo esc_attr( $wcmamtx_type ); ?> <?php echo esc_attr( $extraclass ); ?> <?php echo esc_attr( $active_class ); ?> <?php if ( $icon_source === 'custom' ) echo esc_attr( $icon_extra_class ); ?>"
                   href="<?php echo esc_url( wcmamtx_get_account_endpoint_url( $key ) ); ?>"
                   <?php if ( $wcmamtx_type === 'link' && isset( $value['link_targetblank'] ) && $value['link_targetblank'] == 01 ) echo 'target="_blank"'; ?>>
                    <?php wcmamtx_get_account_menu_li_icon_html( $icon_source, $value, $key ); ?>
                    <span class="wcmamtx_sticky_icon_name"><?php echo esc_html( $name ); ?></span>
                    <?php
                    $hide_sidebar = ( isset( $value['hide_sidebar'] ) && $value['hide_sidebar'] == '01' ) ? 'yes' : 'no';
                    if ( $hide_sidebar === 'no' ) {
                        echo wcmamtx_counter_bubble( $key, $value, 'yes' );
                    }
                    ?>
                </a>
                <?php
            }
        }
    }

    do_action( 'wcmamtx_after_account_navigation' );
    ?>
</nav>
