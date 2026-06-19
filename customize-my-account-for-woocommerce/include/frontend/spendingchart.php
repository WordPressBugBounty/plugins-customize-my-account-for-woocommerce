        <div class="sb-spending-chart-card wcmamtx_spending_chart">

            <div class="sb-chart-header">
                <h3><?php echo esc_html__('Spending Overview','customize-my-account-for-woocommerce'); ?></h3>
                <span><?php echo esc_html__('Last 12 Months','customize-my-account-for-woocommerce'); ?></span>
            </div>

            <canvas id="sbCustomerSpendingChart"></canvas>

        </div>

        <script>
            window.sbChartData = <?php echo wp_json_encode( $data ); ?>;
        </script>