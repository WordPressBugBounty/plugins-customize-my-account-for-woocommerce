        <div class="sb-stats-grid wcmamtx_spending_chart_dash">

            <div class="sb-stat-box wcmamtx_total_spent">
                <span><?php echo esc_html__('Total Spent','customize-my-account-for-woocommerce'); ?></span>
                <strong><?php echo wc_price(wc_get_customer_total_spent( get_current_user_id() )); ?></strong>
            </div>

            <div class="sb-stat-box wcmamtx_total_orders">
                <span><?php echo esc_html__('Total Orders','customize-my-account-for-woocommerce'); ?></span>
                <strong><?php echo wc_get_customer_order_count( get_current_user_id() ); ?></strong>
            </div>

            <div class="sb-stat-box wcmamtx_total_average_order">
                <span><?php echo esc_html__('Average Order','customize-my-account-for-woocommerce'); ?></span>
                <strong><?php echo wc_price(wcmamtx_my_get_customer_average_order_value( get_current_user_id() )); ?></strong>
            </div>

        </div>