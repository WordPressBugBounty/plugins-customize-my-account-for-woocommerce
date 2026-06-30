<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =============================================================
   Onboarding Wizard — admin page + AJAX handlers
   ============================================================= */

/* ── Register the hidden admin page ───────────────────────── */
add_action( 'admin_menu', function () {
    global $wcmamtx_onboarding_hook;
    $wcmamtx_onboarding_hook = add_submenu_page(
        null,                        // no parent = hidden from menu
        'Setup Wizard',
        'Setup Wizard',
        'manage_woocommerce',
        'wcmamtx_onboarding',
        'wcmamtx_render_onboarding_page'
    );
}, 100 );

/* ── Enqueue assets only on the onboarding page ───────────── */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    global $wcmamtx_onboarding_hook;
    if ( $hook !== $wcmamtx_onboarding_hook ) return;

    wp_enqueue_style(
        'wcmamtx-onboarding',
        wcmamtx_PLUGIN_URL . 'assets/css/onboarding.css',
        [],
        '1.0.0'
    );

    wp_enqueue_script(
        'wcmamtx-onboarding',
        wcmamtx_PLUGIN_URL . 'assets/js/onboarding.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );

    $myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

    wp_localize_script( 'wcmamtx-onboarding', 'wcmamtx_ob', [
        'ajax_url'       => admin_url( 'admin-ajax.php' ),
        'nonce'          => wp_create_nonce( 'wcmamtx_onboarding_nonce' ),
        'settings_url'   => admin_url( 'admin.php?page=wcmamtx_advanced_settings' ),
        'customizer_url' => admin_url( 'admin.php?page=wcmamtx_frontend_customizer' ),
        'frontend_url'   => $myaccount_url ? $myaccount_url : home_url( '/my-account/' ),
    ] );
} );

/* ── AJAX: save settings collected on each step ───────────── */
add_action( 'wp_ajax_wcmamtx_onboarding_save', 'wcmamtx_onboarding_save_handler' );
function wcmamtx_onboarding_save_handler() {
    check_ajax_referer( 'wcmamtx_onboarding_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }

    $raw  = isset( $_POST['settings'] ) ? wp_unslash( $_POST['settings'] ) : '{}';
    $data = json_decode( $raw, true );
    if ( ! is_array( $data ) ) {
        wp_send_json_error( 'Invalid data' );
    }

    /* --- layout options (wcmamtx_layout) --- */
    $layout = (array) get_option( 'wcmamtx_layout', [] );

    $layout_keys = [
        'nav_style',
        'spending_layout_override',
        'dashlink_layout_override',
        'order_template_override',
        'navigationwidget_layout_override',
        'widget_menu_location',
        'navwidget_disable_username',
    ];
    foreach ( $layout_keys as $k ) {
        if ( isset( $data[ $k ] ) ) {
            $layout[ $k ] = sanitize_text_field( $data[ $k ] );
        }
    }

    /* keep nav_header_widget in sync with navigationwidget_layout_override */
    if ( isset( $layout['navigationwidget_layout_override'] ) ) {
        $layout['nav_header_widget'] = $layout['navigationwidget_layout_override'] === '01' ? 'yes' : 'no';
    }

    update_option( 'wcmamtx_layout', $layout );

    /* --- avatar setting (wcmamtx_avatar_settings) --- */
    if ( isset( $data['avatar_show'] ) ) {
        $val             = $data['avatar_show'] === 'yes' ? 'yes' : 'no';
        $avatar_settings = (array) get_option( 'wcmamtx_avatar_settings', [] );
        $avatar_settings['disable_avatar'] = $val;
        update_option( 'wcmamtx_avatar_settings', $avatar_settings );
    }

    wp_send_json_success( [ 'message' => 'Settings saved.' ] );
}

/* ── AJAX: mark onboarding as complete ────────────────────── */
add_action( 'wp_ajax_wcmamtx_onboarding_complete', 'wcmamtx_onboarding_complete_handler' );
function wcmamtx_onboarding_complete_handler() {
    check_ajax_referer( 'wcmamtx_onboarding_nonce', 'nonce' );
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_send_json_error( 'Unauthorized' );
    }
    update_option( 'wcmamtx_onboarding_completed', '1' );
    wp_send_json_success( [ 'message' => 'Complete.' ] );
}

/* ── Page renderer ────────────────────────────────────────── */
function wcmamtx_render_onboarding_page() {
    $plugin_url     = wcmamtx_PLUGIN_URL;
    $settings_url   = admin_url( 'admin.php?page=wcmamtx_advanced_settings' );
    $customizer_url = admin_url( 'admin.php?page=wcmamtx_frontend_customizer' );
    $myaccount_url  = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
    if ( ! $myaccount_url ) $myaccount_url = home_url( '/my-account/' );

    $version = function_exists( 'wcmamtx_get_woo_version_number_free' )
        ? wcmamtx_get_woo_version_number_free()
        : '';

    $steps = [
        1 => [ 'Welcome',    'Get started'         ],
        2 => [ 'Navigation', 'Choose menu style'   ],
        3 => [ 'Dashboard',  'Enable features'     ],
        4 => [ 'Nav Widget', 'Sidebar menu widget' ],
        5 => [ 'Complete',   'You\'re all set!'    ],
    ];
    ?>
    <div class="wcmamtx-ob">

        <!-- ════════ SIDEBAR ════════ -->
        <div class="wcmamtx-ob__sidebar">

            <div class="wcmamtx-ob__logo">
                <img src="<?php echo esc_url( $plugin_url . 'assets/images/icon.png' ); ?>" alt="Plugin logo">
                <div class="wcmamtx-ob__logo-text">
                    Customize My Account
                    <small>by SysBasics</small>
                </div>
            </div>

            <div class="wcmamtx-ob__steps">
                <?php foreach ( $steps as $n => [ $title, $sub ] ) : ?>
                <div class="wcmamtx-ob__step" data-step="<?php echo (int) $n; ?>">
                    <div class="wcmamtx-ob__step-num"><?php echo (int) $n; ?></div>
                    <div class="wcmamtx-ob__step-label">
                        <span class="wcmamtx-ob__step-name"><?php echo esc_html( $title ); ?></span>
                        <span class="wcmamtx-ob__step-sub"><?php echo esc_html( $sub ); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="wcmamtx-ob__sidebar-foot">
                <?php if ( $version ) : ?>
                <span class="wcmamtx-ob__ver">v<?php echo esc_html( $version ); ?></span>
                <?php endif; ?>
                <a href="#" class="wcmamtx-ob__skip">Skip setup &rarr;</a>
            </div>
        </div><!-- /sidebar -->

        <!-- ════════ MAIN ════════ -->
        <div class="wcmamtx-ob__main">

            <div class="wcmamtx-ob__bar">
                <div class="wcmamtx-ob__bar-fill"></div>
            </div>

            <div class="wcmamtx-ob__panels">

                <!-- ── STEP 1 : Welcome ─────────────────────────── -->
                <div class="wcmamtx-ob__panel" data-panel="1">
                    <div class="wcmamtx-ob__eyebrow">Welcome</div>
                    <h1>Your account page,<br>beautifully redesigned.</h1>
                    <p class="wcmamtx-ob__desc">
                        Let&rsquo;s set up your WooCommerce My Account page in 3 quick steps.<br>
                        Everything you choose here can be changed again at any time.
                    </p>

                    <div class="wcmamtx-ob__benefits">
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">🎨</div>
                            <h3><?php esc_html_e( 'Live Customizer', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Preview every change in real time — no page refreshes needed.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">🗂️</div>
                            <h3><?php esc_html_e( 'Custom My Account Templates', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Replace default WooCommerce pages with beautifully designed order, download and account templates.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">🎨</div>
                            <h3><?php esc_html_e( 'Custom Navigation Styles', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Choose from multiple account menu designs — clean sidebar, React-based, top bar and more.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">👤</div>
                            <h3><?php esc_html_e( 'Avatar Upload', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Customers upload a profile photo or capture one via webcam.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">🧭</div>
                            <h3><?php esc_html_e( 'My Account Navigation Widget', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Display your account menu inside any sidebar or widget area — no shortcodes needed.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                        <div class="wcmamtx-ob__benefit">
                            <div class="wcmamtx-ob__benefit-ic">🗂️</div>
                            <h3><?php esc_html_e( 'Custom Endpoints', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Add, rename and reorder account tabs without a line of code.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;">
                        <button id="wcmamtx-ob-start" class="wcmamtx-ob__btn wcmamtx-ob__btn--primary wcmamtx-ob__btn--lg">
                            <?php esc_html_e( 'Get Started', 'customize-my-account-for-woocommerce' ); ?> &rarr;
                        </button>
                        <a href="<?php echo esc_url( $customizer_url ); ?>" class="wcmamtx-ob__btn wcmamtx-ob__btn--ghost wcmamtx-ob__btn--lg">
                            🎨 <?php esc_html_e( 'Open Live Customizer', 'customize-my-account-for-woocommerce' ); ?>
                        </a>
                    </div>
                </div><!-- /panel 1 -->

                <!-- ── STEP 2 : Navigation style ────────────────── -->
                <div class="wcmamtx-ob__panel" data-panel="2">
                    <div class="wcmamtx-ob__eyebrow"><?php esc_html_e( 'Step 2 of 4', 'customize-my-account-for-woocommerce' ); ?></div>
                    <h1><?php esc_html_e( 'How should your account menu look?', 'customize-my-account-for-woocommerce' ); ?></h1>
                    <p class="wcmamtx-ob__desc">
                        <?php esc_html_e( 'Pick a navigation style. The Clean style works great with almost every theme. You can change this anytime from the Design tab.', 'customize-my-account-for-woocommerce' ); ?>
                    </p>

                    <div class="wcmamtx-ob__nav-cards">

                        <div class="wcmamtx-ob__nav-card wcmamtx-ob__nav-card--selected" data-value="02">
                            <img src="<?php echo esc_url( $plugin_url . 'assets/images/navigation2.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Clean navigation', 'customize-my-account-for-woocommerce' ); ?>">
                            <h3><?php esc_html_e( 'Clean', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Modern sidebar with icons. Polished look on any theme.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>

                        <div class="wcmamtx-ob__nav-card" data-value="01">
                            <img src="<?php echo esc_url( $plugin_url . 'assets/images/navigation1.png' ); ?>"
                                 alt="<?php esc_attr_e( 'Theme default navigation', 'customize-my-account-for-woocommerce' ); ?>">
                            <h3><?php esc_html_e( 'Theme Default', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Inherits your theme\'s built-in My Account styling unchanged.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>

                        <div class="wcmamtx-ob__nav-card" data-value="05">
                            <?php
                            $nav5_img = wcmamtx_PLUGIN_URL . 'assets/images/navigation5.png';
                            $nav5_path = WP_PLUGIN_DIR . '/customize-my-account-for-woocommerce/assets/images/navigation5.png';
                            if ( file_exists( $nav5_path ) ) : ?>
                            <img src="<?php echo esc_url( $nav5_img ); ?>"
                                 alt="<?php esc_attr_e( 'Minimal Pill navigation', 'customize-my-account-for-woocommerce' ); ?>">
                            <?php else : ?>
                            <div style="height:100px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#1e293b,#0f172a);border-radius:8px;font-size:32px;margin-bottom:10px;">💊</div>
                            <?php endif; ?>
                            <h3><?php esc_html_e( 'Minimal Pill', 'customize-my-account-for-woocommerce' ); ?></h3>
                            <p><?php esc_html_e( 'Clean pill-shaped menu tabs with a minimal, modern look.', 'customize-my-account-for-woocommerce' ); ?></p>
                        </div>

                    </div>

                    <p class="wcmamtx-ob__hint">
                        <?php
                        printf(
                            /* translators: %s = Pro upgrade link */
                            esc_html__( 'The Banking App style (bottom tab bar) is available in %s.', 'customize-my-account-for-woocommerce' ),
                            '<a href="' . esc_url( pro_url ) . '" target="_blank">Pro</a>'
                        );
                        ?>
                    </p>
                </div><!-- /panel 2 -->

                <!-- ── STEP 3 : Dashboard features ──────────────── -->
                <div class="wcmamtx-ob__panel" data-panel="3">
                    <div class="wcmamtx-ob__eyebrow"><?php esc_html_e( 'Step 3 of 4', 'customize-my-account-for-woocommerce' ); ?></div>
                    <h1><?php esc_html_e( 'What should appear on the dashboard?', 'customize-my-account-for-woocommerce' ); ?></h1>
                    <p class="wcmamtx-ob__desc">
                        <?php esc_html_e( 'All features are on by default. Toggle anything you don\'t need — you can adjust this later from the Design tab.', 'customize-my-account-for-woocommerce' ); ?>
                    </p>

                    <div class="wcmamtx-ob__toggles">

                        <div class="wcmamtx-ob__toggle-card wcmamtx-ob__toggle-card--on">
                            <div class="wcmamtx-ob__toggle-ic">📊</div>
                            <div class="wcmamtx-ob__toggle-info">
                                <h3><?php esc_html_e( 'Spending Chart', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( '12-month spending overview with total, order count and average order value.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                            <label class="wcmamtx-ob__sw" title="<?php esc_attr_e( 'Toggle spending chart', 'customize-my-account-for-woocommerce' ); ?>">
                                <input type="checkbox" data-key="spending" checked>
                                <span class="wcmamtx-ob__sw-track"></span>
                            </label>
                        </div>

                        <div class="wcmamtx-ob__toggle-card wcmamtx-ob__toggle-card--on">
                            <div class="wcmamtx-ob__toggle-ic">🔗</div>
                            <div class="wcmamtx-ob__toggle-info">
                                <h3><?php esc_html_e( 'Dashboard Links', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Quick-access icon cards for each account section right on the dashboard.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                            <label class="wcmamtx-ob__sw" title="<?php esc_attr_e( 'Toggle dashboard links', 'customize-my-account-for-woocommerce' ); ?>">
                                <input type="checkbox" data-key="dashlinks" checked>
                                <span class="wcmamtx-ob__sw-track"></span>
                            </label>
                        </div>

                        <div class="wcmamtx-ob__toggle-card wcmamtx-ob__toggle-card--on">
                            <div class="wcmamtx-ob__toggle-ic">👤</div>
                            <div class="wcmamtx-ob__toggle-info">
                                <h3><?php esc_html_e( 'User Avatar', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Show a profile photo customers can upload or capture via webcam.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                            <label class="wcmamtx-ob__sw" title="<?php esc_attr_e( 'Toggle user avatar', 'customize-my-account-for-woocommerce' ); ?>">
                                <input type="checkbox" data-key="avatar" checked>
                                <span class="wcmamtx-ob__sw-track"></span>
                            </label>
                        </div>

                        <div class="wcmamtx-ob__toggle-card wcmamtx-ob__toggle-card--on">
                            <div class="wcmamtx-ob__toggle-ic">📦</div>
                            <div class="wcmamtx-ob__toggle-info">
                                <h3><?php esc_html_e( 'Order Cards', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Replace the default orders table with a modern card-based layout.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                            <label class="wcmamtx-ob__sw" title="<?php esc_attr_e( 'Toggle order cards', 'customize-my-account-for-woocommerce' ); ?>">
                                <input type="checkbox" data-key="orders" checked>
                                <span class="wcmamtx-ob__sw-track"></span>
                            </label>
                        </div>

                    </div>
                </div><!-- /panel 3 -->

                <!-- ── STEP 4 : Navigation Widget ───────────────── -->
                <div class="wcmamtx-ob__panel" data-panel="4">
                    <div class="wcmamtx-ob__eyebrow"><?php esc_html_e( 'Step 4 of 4', 'customize-my-account-for-woocommerce' ); ?></div>
                    <h1><?php esc_html_e( 'Add a My Account button to your site navigation.', 'customize-my-account-for-woocommerce' ); ?></h1>
                    <p class="wcmamtx-ob__desc">
                        <?php esc_html_e( 'The Nav Menu Widget adds a "My Account" link directly to your site\'s header or navigation menu — so customers can reach their account from anywhere on your store.', 'customize-my-account-for-woocommerce' ); ?>
                    </p>

                    <div class="wcmamtx-ob__toggles">

                        <div class="wcmamtx-ob__toggle-card wcmamtx-ob__toggle-card--on" id="wcmamtx-ob-navwidget-main">
                            <div class="wcmamtx-ob__toggle-ic">🧭</div>
                            <div class="wcmamtx-ob__toggle-info">
                                <h3><?php esc_html_e( 'Enable Nav Menu Widget', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Adds a My Account item to your navigation menu. Choose which menu location to use from the Live Customizer.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </div>
                            <label class="wcmamtx-ob__sw" title="<?php esc_attr_e( 'Toggle nav menu widget', 'customize-my-account-for-woocommerce' ); ?>">
                                <input type="checkbox" data-key="nav_widget" checked>
                                <span class="wcmamtx-ob__sw-track"></span>
                            </label>
                        </div>

                        <div id="wcmamtx-ob-navwidget-opts" style="grid-column:1/-1;margin-top:4px;">
                            <?php
                            $registered_menus = get_registered_nav_menus();
                            $saved_location   = '';
                            $ob_layout        = (array) get_option( 'wcmamtx_layout', [] );
                            if ( isset( $ob_layout['widget_menu_location'] ) ) {
                                $saved_location = $ob_layout['widget_menu_location'];
                            }
                            ?>
                            <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px 24px;">
                                <label style="display:block;font-size:13px;font-weight:700;color:#0f172a;margin-bottom:10px;">
                                    📍 <?php esc_html_e( 'Menu Location', 'customize-my-account-for-woocommerce' ); ?>
                                </label>
                                <?php if ( ! empty( $registered_menus ) ) : ?>
                                <select id="wcmamtx-ob-menu-location" style="width:100%;padding:10px 12px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;color:#0f172a;background:#f8fafc;cursor:pointer;outline:none;">
                                    <?php foreach ( $registered_menus as $location => $label ) : ?>
                                    <option value="<?php echo esc_attr( $location ); ?>" <?php selected( $saved_location, $location ); ?>>
                                        <?php echo esc_html( $label ); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <p style="margin:8px 0 0;font-size:11px;color:#94a3b8;"><?php esc_html_e( 'The My Account button will appear in this navigation menu.', 'customize-my-account-for-woocommerce' ); ?></p>
                                <?php else : ?>
                                <p style="margin:0;font-size:13px;color:#94a3b8;"><?php esc_html_e( 'No menu locations found. Visit Appearance → Menus to set up navigation menus first.', 'customize-my-account-for-woocommerce' ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div><!-- /panel 4 -->

                <!-- ── STEP 5 : Done ─────────────────────────────── -->
                <div class="wcmamtx-ob__panel" data-panel="5">
                    <div class="wcmamtx-ob__done">

                        <div class="wcmamtx-ob__done-icon">✓</div>

                        <div class="wcmamtx-ob__eyebrow"><?php esc_html_e( 'Setup Complete', 'customize-my-account-for-woocommerce' ); ?></div>
                        <h1><?php esc_html_e( 'Your account page is ready!', 'customize-my-account-for-woocommerce' ); ?></h1>
                        <p class="wcmamtx-ob__desc">
                            <?php esc_html_e( 'All settings have been saved. Use the quick actions below to keep customising, or just visit your store to see the result.', 'customize-my-account-for-woocommerce' ); ?>
                        </p>

                        <div class="wcmamtx-ob__actions">
                            <a href="<?php echo esc_url( $myaccount_url ); ?>" target="_blank" class="wcmamtx-ob__action">
                                <div class="wcmamtx-ob__action-ic">🌐</div>
                                <h3><?php esc_html_e( 'View My Account', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'See exactly how it looks to your customers right now.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </a>
                            <a href="<?php echo esc_url( $customizer_url ); ?>" class="wcmamtx-ob__action">
                                <div class="wcmamtx-ob__action-ic">🎨</div>
                                <h3><?php esc_html_e( 'Live Customizer', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Fine-tune every detail with a real-time live preview.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </a>
                            <a href="<?php echo esc_url( $settings_url ); ?>" class="wcmamtx-ob__action">
                                <div class="wcmamtx-ob__action-ic">⚙️</div>
                                <h3><?php esc_html_e( 'Manage Endpoints', 'customize-my-account-for-woocommerce' ); ?></h3>
                                <p><?php esc_html_e( 'Add, rename and reorder account navigation tabs.', 'customize-my-account-for-woocommerce' ); ?></p>
                            </a>
                        </div>

                    </div>
                </div><!-- /panel 5 -->

            </div><!-- /panels -->

            <!-- ── Footer navigation ── -->
            <div class="wcmamtx-ob__foot" id="wcmamtx-ob-footer">
                <span class="wcmamtx-ob__foot-label">
                    <?php esc_html_e( 'Step 1 of 5', 'customize-my-account-for-woocommerce' ); ?>
                </span>
                <div class="wcmamtx-ob__btns">
                    <button id="wcmamtx-ob-prev"
                            class="wcmamtx-ob__btn wcmamtx-ob__btn--ghost"
                            style="display:none;"
                            aria-label="<?php esc_attr_e( 'Previous step', 'customize-my-account-for-woocommerce' ); ?>">
                        &larr; <?php esc_html_e( 'Back', 'customize-my-account-for-woocommerce' ); ?>
                    </button>
                    <button id="wcmamtx-ob-next"
                            class="wcmamtx-ob__btn wcmamtx-ob__btn--primary"
                            aria-label="<?php esc_attr_e( 'Next step', 'customize-my-account-for-woocommerce' ); ?>">
                        <?php esc_html_e( 'Next', 'customize-my-account-for-woocommerce' ); ?> &rarr;
                    </button>
                </div>
            </div>

        </div><!-- /main -->

    </div><!-- /ob -->

    <!-- confetti canvas -->
    <div class="wcmamtx-ob__confetti" aria-hidden="true"></div>

    <!-- save toast -->
    <div class="wcmamtx-ob__toast" role="status" aria-live="polite">
        <div class="wcmamtx-ob__spin"></div>
        <span class="wcmamtx-ob__toast-msg">
            <?php esc_html_e( 'Saving…', 'customize-my-account-for-woocommerce' ); ?>
        </span>
    </div>
    <?php
}
