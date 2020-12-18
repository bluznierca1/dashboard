var Dashboard;


(function($) {
    Dashboard = function() {
        this.initDatePickers();

        this.initChart();

        this.initAjaxOnDatepickersForm();
    }

    Dashboard.prototype.getFormActionUrl = function( formData ) {
        let url = null;
        formData.forEach( function(data) {

            if( data.hasOwnProperty('name') && data.hasOwnProperty('value') && data.name === 'action_url' ) {
                url = data.value;
            }
        });

        return url;
    }

    Dashboard.prototype.initAjaxOnDatepickersForm = function() {
        var self = this,
            form = $('#datepickers-form');

        if( form.length ) {
            form.on('submit', function(e) {
                e.preventDefault();

                const formData = form.serializeArray();
                const url = self.getFormActionUrl(formData);

                if( url !== null ) {

                    return $.ajax({
                        url: url,
                        type: 'POST',
                        cache: false,
                        async: true,
                        data : formData,
                        beforeSend: function(){
                            console.log('before');
                        },
                        success: function(response) {
                            console.log('response', response);
                        },
                        error: function(jqXHR, status, errorThrown) {
                            console.log('error', errorThrown);
                        }

                    });
                }




            })
        }
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