<?php
defined('ABSPATH') || exit;

/**
 * Show login/register popup when logged-out menu button is clicked,
 * on all pages except the My Account page.
 */
add_action('wp_footer', function () {
    if (is_user_logged_in()) return;
    if (function_exists('is_account_page') && is_account_page()) return;
    ?>

    <!-- Logged-Out Login Popup -->
    <div id="wcmamtx-login-popup-overlay" style="display:none;position:fixed;inset:0;z-index:99998;background:rgba(0,0,0,0.55);">
        <div id="wcmamtx-login-popup" style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:99999;background:#fff;border-radius:16px;box-shadow:0 20px 60px rgba(0,0,0,0.25);max-width:480px;width:90%;max-height:90vh;overflow-y:auto;">
            <button id="wcmamtx-login-popup-close" aria-label="Close" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:24px;cursor:pointer;line-height:1;color:#555;z-index:1;">&times;</button>
            <?php
                $template = WP_PLUGIN_DIR . '/customize-my-account-for-woocommerce/templates/myaccount/form-login.php';
                if (file_exists($template)) {
                    include $template;
                }
            ?>
        </div>
    </div>

    <script>
    (function() {
        document.addEventListener('DOMContentLoaded', function () {

            var overlay = document.getElementById('wcmamtx-login-popup-overlay');
            var closeBtn = document.getElementById('wcmamtx-login-popup-close');

            function openPopup() {
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closePopup() {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }

            // Intercept the logged-out menu button click
            document.querySelectorAll('li.wcmamtx_menu_logged_out a').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    openPopup();
                });
            });

            // Close on X button
            if (closeBtn) closeBtn.addEventListener('click', closePopup);

            // Close on overlay backdrop click
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) closePopup();
            });

            // Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePopup();
            });

            // Tab switching inside the popup (Login / Register / Lost Password)
            overlay.addEventListener('click', function (e) {
                var btn = e.target.closest('.wc-tab-btn');
                if (!btn) return;
                var tab = btn.getAttribute('data-tab');
                if (!tab) return;

                // Update active tab button
                overlay.querySelectorAll('.wc-tab-btn').forEach(function (b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                // Show correct tab content
                overlay.querySelectorAll('.wc-tab-content').forEach(function (c) {
                    c.classList.remove('active');
                    c.style.display = 'none';
                });
                var target = overlay.querySelector('#' + tab);
                if (target) {
                    target.classList.add('active');
                    target.style.display = 'block';
                }
            });

        });
    })();
    </script>
    <?php
}, 99);
