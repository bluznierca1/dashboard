<?php require_once(TEMPLATES_PATH . 'header.php');
//echo '<pre>'; print_r($chart['customers']); echo '</pre>';
?>

    <main id="main" class="top-30">

        <div class="container chart-data" id="chart-data">
            <div class="row chart-data__row">
                <div class="col-12 chart-data__row-customer-total">Total number of customers: <span class="value"><?php echo $chart['customers']['customersTotal'] ?? 0; ?></span></div>
                <div class="col-12 chart-data__row-orders-total">Total number of orders: <span class="value"><?php echo $chart['orders']['ordersTotal'] ?? 0; ?></span></div>
                <div class="col-12 chart-data__row-revenue-total">Total revenue: <span class="value"><?php echo $chart['orders']['ordersRevenue'] ?? 0; ?></span>$</div>
            </div>
        </div>

        <div class="container top-30" id="chart">

            <input type="hidden" id="chart-orders-data" value="<?php echo json_encode($chart['orders']['ordersByDays']); ?>" />
            <input type="hidden" id="chart-customers-data" value="<?php echo json_encode($chart['customers']['customersByDays']); ?>" />

            <form action="/" method="POST" id="datepickers-form">

                <div class="row datepickers">

                    <div class="col-4 datepicker-col">

                        <div id="datepicker-start" class="input-group date datepicker-col__wrapper" data-date-format="yyyy-mm-dd">
                            <label for="date-start">Start date:</label>
                            <input class="form-control" type="text" name="datepicker_date_start" id="date-start"/>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>

                    </div>

                    <div class="col-4 datepicker-col">

                        <div id="datepicker-end" class="input-group date datepicker-col__wrapper" data-date-format="yyyy-mm-dd">
                            <label for="date-end">End date:</label>
                            <input class="form-control" type="text" name="datepicker_date_end" id="date-end"/>
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                        </div>

                    </div>

                    <div class="col-4 datepicker-col">
                        <div class="datepicker-col__submit">
                            <input type="hidden" name="action" value="getChartDataByRange" />
                            <input type="hidden" name="action_url" value="<?php echo HOME_URL . '/handle-ajax.php'; ?>" />
                            <input type="submit" name="datepicker_submit" value="SUBMIT" class="btn btn-success" />
                        </div>
                    </div>

            </div>

            </form>

        </div>


        <div class="container chart top-30">

            <div class="row chart-loader">
                <div class="col-12">
                    <h2>Loading chart data...</h2>
                </div>
            </div>

            <div class="row chart-row highcharts">
                <div class="col-12">

                    <figure class="chart-figure highcharts-figure" style="display: none;">

                        <div id="container"></div>

                        <p class="highcharts-description">
                            Orders and customers chart.
                        </p>

                    </figure>

                </div>

            </div>
        </div>

    </main>





<?php require_once(TEMPLATES_PATH . 'footer.php'); ?>