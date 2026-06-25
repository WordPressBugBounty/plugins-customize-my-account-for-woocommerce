<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// ─────────────────────────────────────────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────────────────────────────────────────
define( 'WCMAMTX_WL_OPT', 'wcmamtx_layout' );

function wcmamtx_wl_enabled() {
    $layout = (array) get_option( WCMAMTX_WL_OPT );
    return isset( $layout['whitelabel_override'] ) && $layout['whitelabel_override'] === '01';
}

function wcmamtx_wl_get( $key, $fallback = '' ) {
    $layout = (array) get_option( WCMAMTX_WL_OPT );
    return wcmamtx_wl_enabled() && ! empty( $layout[ $key ] ) ? $layout[ $key ] : $fallback;
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. LAYOUT TAB BUTTON
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'wcmamtx_add_new_layout_settings_tab', function( $html ) {
    $html .= '<button class="wcmamtx_tab_link" data-tab="whitelabel">
        <span class="dashicons dashicons-businessman"></span>
        ' . esc_html__( 'White Label', 'customize-my-account-for-woocommerce' ) . '
    </button>';
    return $html;
} );

// ─────────────────────────────────────────────────────────────────────────────
// 2. LAYOUT TAB CONTENT PANE
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'wcmamtx_add_new_layout_settings_content', function( $html ) {
    $layout   = (array) get_option( WCMAMTX_WL_OPT );
    $override = isset( $layout['whitelabel_override'] ) ? $layout['whitelabel_override'] : '02';
    $is_on    = ( $override === '01' );

    $fields = [
        'whitelabel_plugin_name'   => [ 'label' => __( 'Plugin Name',        'customize-my-account-for-woocommerce' ), 'placeholder' => __( 'e.g. My Account Manager', 'customize-my-account-for-woocommerce' ) ],
        'whitelabel_plugin_desc'   => [ 'label' => __( 'Plugin Description', 'customize-my-account-for-woocommerce' ), 'placeholder' => __( 'Short plugin description…',  'customize-my-account-for-woocommerce' ), 'type' => 'textarea' ],
        'whitelabel_author_name'   => [ 'label' => __( 'Author Name',        'customize-my-account-for-woocommerce' ), 'placeholder' => __( 'Your Company Name',           'customize-my-account-for-woocommerce' ) ],
        'whitelabel_author_url'    => [ 'label' => __( 'Author URL',         'customize-my-account-for-woocommerce' ), 'placeholder' => 'https://yoursite.com' ],
        'whitelabel_plugin_url'    => [ 'label' => __( 'Plugin URL',         'customize-my-account-for-woocommerce' ), 'placeholder' => 'https://yoursite.com/plugin' ],
        'whitelabel_docs_url'      => [ 'label' => __( 'Documentation URL',  'customize-my-account-for-woocommerce' ), 'placeholder' => 'https://yoursite.com/docs' ],
        'whitelabel_support_url'   => [ 'label' => __( 'Support URL',        'customize-my-account-for-woocommerce' ), 'placeholder' => 'https://yoursite.com/support' ],
        'whitelabel_menu_name'     => [ 'label' => __( 'Admin Menu Label',   'customize-my-account-for-woocommerce' ), 'placeholder' => __( 'e.g. My Plugin',             'customize-my-account-for-woocommerce' ) ],
    ];

    ob_start();
    ?>
    <div class="tab-pane" id="whitelabel">
        <div class="wcmamtx-setting-card">

            <div class="wcmamtx-card-header">
                <div>
                    <h2><?php esc_html_e( 'White Label', 'customize-my-account-for-woocommerce' ); ?></h2>
                    <p><?php esc_html_e( 'Replace plugin branding with your own. When enabled, the plugin name, author, description, admin menu label, and support links will reflect your entries below.', 'customize-my-account-for-woocommerce' ); ?></p>

                    <select
                        class="wcmamtx_layout_select_override wcmamtx_layout_order_select_override"
                        name="wcmamtx_layout[whitelabel_override]"
                        id="wcmamtx_whitelabel_select"
                    >
                        <option value="01" <?php selected( $override, '01' ); ?>><?php esc_html_e( 'Enable', 'customize-my-account-for-woocommerce' ); ?></option>
                        <option value="02" <?php selected( $override, '02' ); ?>><?php esc_html_e( 'Disable', 'customize-my-account-for-woocommerce' ); ?></option>
                    </select>
                </div>
            </div>

            <div class="wcmamtx-card-body" style="<?php echo $is_on ? 'display:block;' : 'display:none;'; ?>">

                <style>
                    .wcmamtx-wl-field { margin-bottom: 18px; }
                    .wcmamtx-wl-field label { display:block; font-weight:600; font-size:12px; color:#1d2327; margin-bottom:5px; }
                    .wcmamtx-wl-field input[type=text],
                    .wcmamtx-wl-field input[type=url],
                    .wcmamtx-wl-field textarea { width:100%; max-width:520px; box-sizing:border-box; border:1px solid #8c8f94; border-radius:3px; padding:7px 10px; font-size:13px; }
                    .wcmamtx-wl-field textarea { height:72px; resize:vertical; }
                    .wcmamtx-wl-field input:focus,
                    .wcmamtx-wl-field textarea:focus { border-color:#2271b1; box-shadow:0 0 0 1px #2271b1; outline:none; }
                    .wcmamtx-wl-grid { display:grid; grid-template-columns:1fr 1fr; gap:0 32px; }
                    @media(max-width:900px){ .wcmamtx-wl-grid { grid-template-columns:1fr; } }
                    .wcmamtx-wl-notice { background:#f0f6ff; border-left:4px solid #2271b1; padding:10px 14px; font-size:12px; color:#1d2327; margin-bottom:18px; border-radius:2px; }
                </style>

                <p class="wcmamtx-wl-notice">
                    <?php esc_html_e( 'These values replace visible branding in the WordPress admin. They do not modify plugin files.', 'customize-my-account-for-woocommerce' ); ?>
                </p>

                <div class="wcmamtx-wl-grid">
                <?php foreach ( $fields as $key => $cfg ) :
                    $val  = isset( $layout[ $key ] ) ? esc_attr( $layout[ $key ] ) : '';
                    $type = isset( $cfg['type'] ) ? $cfg['type'] : ( strpos( $key, '_url' ) !== false ? 'url' : 'text' );
                ?>
                    <div class="wcmamtx-wl-field">
                        <label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $cfg['label'] ); ?></label>
                        <?php if ( $type === 'textarea' ) : ?>
                            <textarea
                                id="<?php echo esc_attr( $key ); ?>"
                                name="wcmamtx_layout[<?php echo esc_attr( $key ); ?>]"
                                placeholder="<?php echo esc_attr( $cfg['placeholder'] ); ?>"
                            ><?php echo esc_textarea( isset( $layout[ $key ] ) ? $layout[ $key ] : '' ); ?></textarea>
                        <?php else : ?>
                            <input
                                type="<?php echo esc_attr( $type ); ?>"
                                id="<?php echo esc_attr( $key ); ?>"
                                name="wcmamtx_layout[<?php echo esc_attr( $key ); ?>]"
                                value="<?php echo $val; ?>"
                                placeholder="<?php echo esc_attr( $cfg['placeholder'] ); ?>"
                            />
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
    <?php
    return $html . ob_get_clean();
} );

// ─────────────────────────────────────────────────────────────────────────────
// 3. OVERRIDE ADMIN MENU LABEL (parent SysBasics menu + submenu)
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'add_menu_classes', function( $menu ) {
    if ( ! wcmamtx_wl_enabled() ) return $menu;
    $label = wcmamtx_wl_get( 'whitelabel_menu_name' );
    if ( ! $label ) return $menu;
    foreach ( $menu as &$item ) {
        if ( isset( $item[2] ) && $item[2] === 'sysbasics' ) {
            $item[0] = esc_html( $label );
            $item[7] = sanitize_title( $label ); // icon title
        }
    }
    return $menu;
}, 999 );

// Override submenu label
add_filter( 'add_menu_classes', function( $menu ) { return $menu; } ); // dummy — real override below
global $submenu;
add_action( 'admin_menu', function() {
    if ( ! wcmamtx_wl_enabled() ) return;
    $label = wcmamtx_wl_get( 'whitelabel_menu_name' );
    if ( ! $label ) return;
    global $menu, $submenu;
    // Override parent menu title
    foreach ( $menu as &$item ) {
        if ( isset( $item[2] ) && $item[2] === 'sysbasics' ) {
            $item[0] = esc_html( $label );
            break;
        }
    }
    // Override submenu entry title
    if ( isset( $submenu['sysbasics'] ) ) {
        foreach ( $submenu['sysbasics'] as &$sub ) {
            if ( isset( $sub[2] ) && $sub[2] === 'wcmamtx_advanced_settings' ) {
                $sub[0] = esc_html( $label );
                break;
            }
        }
    }
}, 999 );

// ─────────────────────────────────────────────────────────────────────────────
// 4. OVERRIDE PLUGIN ROW META (Docs + Support links)
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'plugin_row_meta', function( $links, $file ) {
    if ( ! wcmamtx_wl_enabled() ) return $links;
    $basename = 'customize-my-account-for-woocommerce/customize-my-account-for-woocommerce.php';
    if ( $file !== $basename ) return $links;

    $docs_url    = wcmamtx_wl_get( 'whitelabel_docs_url' );
    $support_url = wcmamtx_wl_get( 'whitelabel_support_url' );

    foreach ( $links as $key => $link ) {
        // Remove original Docs link
        if ( strpos( $link, 'sysbasics.com/knowledge-base' ) !== false ) {
            if ( $docs_url ) {
                $links[ $key ] = '<a href="' . esc_url( $docs_url ) . '" target="_blank" style="color:green;">' . esc_html__( 'Docs', 'customize-my-account-for-woocommerce' ) . '</a>';
            } else {
                unset( $links[ $key ] );
            }
        }
        // Remove original Support link
        if ( strpos( $link, 'sysbasics.com/support' ) !== false || strpos( $link, 'free-plugin-support' ) !== false ) {
            if ( $support_url ) {
                $links[ $key ] = '<a href="' . esc_url( $support_url ) . '" target="_blank" style="color:green;">' . esc_html__( 'Support', 'customize-my-account-for-woocommerce' ) . '</a>';
            } else {
                unset( $links[ $key ] );
            }
        }
    }
    return $links;
}, 20, 2 );

// ─────────────────────────────────────────────────────────────────────────────
// 5. OVERRIDE PLUGIN NAME + AUTHOR + DESCRIPTION in plugins list table
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'all_plugins', function( $plugins ) {
    if ( ! wcmamtx_wl_enabled() ) return $plugins;
    $key = 'customize-my-account-for-woocommerce/customize-my-account-for-woocommerce.php';
    if ( ! isset( $plugins[ $key ] ) ) return $plugins;

    $name    = wcmamtx_wl_get( 'whitelabel_plugin_name' );
    $desc    = wcmamtx_wl_get( 'whitelabel_plugin_desc' );
    $author  = wcmamtx_wl_get( 'whitelabel_author_name' );
    $a_url   = wcmamtx_wl_get( 'whitelabel_author_url' );
    $p_url   = wcmamtx_wl_get( 'whitelabel_plugin_url' );

    if ( $name )   $plugins[ $key ]['Name']        = $name;
    if ( $name )   $plugins[ $key ]['Title']       = $name;
    if ( $desc )   $plugins[ $key ]['Description'] = $desc;
    if ( $author ) $plugins[ $key ]['Author']      = $author;
    if ( $author ) $plugins[ $key ]['AuthorName']  = $author;
    if ( $a_url )  $plugins[ $key ]['AuthorURI']   = $a_url;
    if ( $p_url )  $plugins[ $key ]['PluginURI']   = $p_url;

    return $plugins;
}, 20 );

// ─────────────────────────────────────────────────────────────────────────────
// 6. OVERRIDE DOCS + SUPPORT buttons inside the admin settings page
// ─────────────────────────────────────────────────────────────────────────────
add_action( 'wcmamtx_add_author_links', function() {
    if ( ! wcmamtx_wl_enabled() ) return;
    // Prevent original buttons (they are echo-ed in the same action — but this
    // plugin doesn't use add_action for them, they're direct echo in template).
    // We hook at priority 1 and buffer+clear via output buffering workaround is
    // not feasible cleanly, so instead we hide them via CSS and re-add ours.
    $docs_url    = wcmamtx_wl_get( 'whitelabel_docs_url' );
    $support_url = wcmamtx_wl_get( 'whitelabel_support_url' );
    ?>
    <style>
        /* Hide original hardcoded Docs/Support buttons when whitelabel is on */
        .wcmamtx_docs_buton, .wcmamtx_support_buton { display:none !important; }
    </style>
    <?php if ( $docs_url ) : ?>
        <a target="_blank" class="btn wcmamtx_docs_buton_wl btn-success"
           href="<?php echo esc_url( $docs_url ); ?>"
           style="display:inline-flex;">
            <span class="wcmamtx_docs_icon dashicons dashicons-welcome-learn-more"></span>
            <?php esc_html_e( 'Documentation', 'customize-my-account-for-woocommerce' ); ?>
        </a>
    <?php endif;
    if ( $support_url ) : ?>
        <a target="_blank" class="btn wcmamtx_support_buton_wl btn-warning"
           href="<?php echo esc_url( $support_url ); ?>"
           style="display:inline-flex;">
            <span class="wcmamtx_docs_icon dashicons dashicons-admin-generic"></span>
            <?php esc_html_e( 'Support', 'customize-my-account-for-woocommerce' ); ?>
        </a>
    <?php endif;
}, 5 );
// ─────────────────────────────────────────────────────────────────────────────
// 7. HIDE "Upgrade to premium" action link in plugins list when whitelabeling
// ─────────────────────────────────────────────────────────────────────────────
add_filter( 'plugin_action_links_customize-my-account-for-woocommerce/customize-my-account-for-woocommerce.php', function( $links ) {
    if ( ! wcmamtx_wl_enabled() ) return $links;
    foreach ( $links as $key => $link ) {
        if ( strpos( $link, 'sysbasics.com/go/customize' ) !== false || strpos( $link, 'Upgrade to premium' ) !== false ) {
            unset( $links[ $key ] );
        }
    }
    return $links;
}, 20 );