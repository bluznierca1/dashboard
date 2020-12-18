var Dashboard;


(function($) {
    Dashboard = function() {
        this.initDatePickers();

        this.initChart();

        this.initAjaxOnDatepickersForm();
    }

    Dashboard.prototype.getValueFromForm = function( formData, propertyName ) {
        let value = null;

        formData.forEach( function(data) {

            if( data.hasOwnProperty('name') && data.hasOwnProperty('value') && data.name === propertyName ) {
                value = data.value;
            }

        });

        return value;
    }

    Dashboard.prototype.initAjaxOnDatepickersForm = function() {
        var self = this,
            form = $('#datepickers-form');

        if( form.length ) {
            form.on('submit', function(e) {
                e.preventDefault();

                const formData = form.serializeArray();
                const url = self.getValueFromForm(formData, 'action_url');

                // Check if dateTo > dateFrom
                const areDatesCorrect = function( formData ) {
                    let dateFrom = self.getValueFromForm(formData, 'datepicker_date_start');
                    let dateTo = self.getValueFromForm(formData, 'datepicker_date_end');

                        if( dateFrom && dateTo ) {
                            return new Date(dateTo) > new Date(dateFrom);
                        }

                       return false;
                }(formData);

                console.log('areDatesCorrect', areDatesCorrect);

                if( areDatesCorrect && url !== null ) {

                    return $.ajax({
                        url: url,
                        type: 'POST',
                        cache: false,
                        async: true,
                        data : formData,
                        beforeSend: function(){
                            $('.highcharts-figure').hide();
                            $('.chart-loader').show();

                        },
                        success: function(response) {
                            console.log('response', JSON.parse(response));
                            let parsedData = JSON.parse(response);
                            self.parseResponseDataForChart(parsedData);
                        },
                        error: function(jqXHR, status, errorThrown) {
                            console.log('error', errorThrown);
                        }

                    });

                }

            });

        }

    }

    Dashboard.prototype.parseResponseDataForChart = function( parsedData ) {

        if( parsedData.hasOwnProperty('chart') ) {

            if( parsedData.chart.hasOwnProperty('customers') ) {

                if( parsedData.chart.customers.hasOwnProperty('customersByDays') ) {
                    $('#chart-customers-data').val(JSON.stringify(parsedData.chart.customers.customersByDays));
                }

                if( parsedData.chart.customers.hasOwnProperty('customersTotal') ) {
                    $('.chart-data__row-customer-total .value').html(parsedData.chart.customers.customersTotal);
                }

            }

            if( parsedData.chart.hasOwnProperty('orders') ) {

                if( parsedData.chart.orders.hasOwnProperty('ordersByDays') ) {
                    $('#chart-orders-data').val(JSON.stringify(parsedData.chart.orders.ordersByDays));
                }

                if( parsedData.chart.orders.hasOwnProperty('ordersTotal') ) {
                    $('.chart-data__row-orders-total .value').html(parsedData.chart.orders.ordersTotal);
                }

                if( parsedData.chart.orders.hasOwnProperty('ordersRevenue') ) {
                    $('.chart-data__row-revenue-total .value').html(parsedData.chart.orders.ordersRevenue);
                }

            }

        }

        this.initChart();
    }

    Dashboard.prototype.initDatePickers = function() {
        let datepickerStart = $('#datepicker-start'),
            datepickerEnd = $('#datepicker-end');

        if( datepickerStart.length && datepickerEnd.length ) {

            this.initDatePicker(datepickerStart, true);
            this.initDatePicker(datepickerEnd, false);

        }

    }

    Dashboard.prototype.initDatePicker = function( element, past ) {
        if( typeof past == 'undefined' ) {
            past = false;
        }

        let date;
        if( past ) {
            let pastDate = new Date();
            pastDate.setMonth( pastDate.getMonth() - 1 );
            date = pastDate;
        } else {
            date = new Date();
        }

        element.datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', date);
    }

    Dashboard.prototype.prepareDataForChart = function() {

        let ordersDataInput = $('#chart-orders-data');
        let customersDataInput = $('#chart-customers-data');
        let dateStartInput = $('#date-start');

        const ordersData = ordersDataInput.length ? JSON.parse(ordersDataInput.val()) : [];
        const customersData = customersDataInput.length ? JSON.parse(customersDataInput.val()) : [];
        const dateStart = dateStartInput.length ? dateStartInput.val() : '';

        return {
            ordersData: ordersData ? ordersData : [],
            customersData: customersData ? customersData : [],
            dateStart: dateStart
        }

    }

    Dashboard.prototype.initChart = function() {

        const chartData = this.prepareDataForChart();

        const ordersData    = chartData.ordersData;
        const customersData = chartData.customersData;
        let dateStart     = chartData.dateStart;

        if( dateStart ) {
            dateStart = dateStart.split('-');
        }

        const chart = Highcharts.chart('container', {
            chart: {
                scrollablePlotArea: {
                    minWidth: 700
                }
            },
            title: {
                text: 'Customers & Orders'
            },

            xAxis: {
                type: 'datetime',
                labels: {
                    format: '{value:%Y-%m-%d}',
                    rotation: 45,
                    align: 'left'
                }
            },

            series: [
                {
                    name: 'Orders',
                    data: [...ordersData],
                    pointStart: Date.UTC(dateStart[0], dateStart[1] - 1, dateStart[2]),
                    pointInterval: 24 * 36e5
                },
                {
                    name: 'Customers',
                    data: [...customersData],
                    pointStart: Date.UTC(dateStart[0], dateStart[1] - 1, dateStart[2]),
                    pointInterval: 24 * 36e5
                }
            ],
        });

        $('.highcharts-figure').show();
        $('.chart-loader').hide();

    }

    $(function() {
        window.Dashboard = new Dashboard;
    });

})(jQuery);