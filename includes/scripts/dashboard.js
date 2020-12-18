var Dashboard;


(function($) {
    Dashboard = function() {
        this.initDatePickers();

        this.initChart();
    }

    Dashboard.prototype.initDatePickers = function() {
        var datepickerStart = $('#datepicker-start'),
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
            let past = new Date();
            past.setMonth( past.getMonth() - 1 );
            date = past;
        } else {
            date = new Date();
        }

        element.datepicker({
            autoclose: true,
            todayHighlight: true
        }).datepicker('update', date);
    }

    Dashboard.prototype.initChart = function() {
        const ordersData = JSON.parse($('#chart-orders-data').val());
        const customersData = JSON.parse($('#chart-customers-data').val());

        let dateStart = $('#date-start').val();
        if( dateStart ) {
            dateStart = dateStart.split('-');
        }

        console.log('dateStart', dateStart);

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

    }

    $(function() {
        window.Dashboard = new Dashboard;
    });

})(jQuery);