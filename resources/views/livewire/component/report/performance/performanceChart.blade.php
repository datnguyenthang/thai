<script type="module">
    let delayed;
    const total = {!! json_encode($chartDataOriginal) !!};

    const labels_total = total.map(item => item.data);
    const data_order = total.map(item => item.totalOrder);
    const data_revenue = total.map(item => item.revenue);

    const chartOrderCtx = document.getElementById('orderChart').getContext('2d');
    const chartRevenueCtx = document.getElementById('revenueChart').getContext('2d');

    const chartOrder = new Chart(chartOrderCtx, {
        type: 'line',
        data: {
            labels: labels_total,
            datasets: [
                {
                    label: 'Order',
                    data: data_order,
                    backgroundColor: 'rgba(144, 238, 144, 0.5)',
                    borderColor: 'rgba(144, 238, 144, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Order Chart'
                }
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 20 + context.datasetIndex * 20;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: false,
                    group: 'group' ,
                }
            }
        }
    });

////////////////////////////////////////////////////////////////////////////////////

    const chartRevenue = new Chart(chartRevenueCtx, {
        type: 'line',
        data: {
            labels: labels_total,
            datasets: [
                {
                    label: 'Revenue',
                    data: data_revenue,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',  // Use a different color
                    borderColor: 'rgba(255, 99, 132, 1)',       // Use a different border color
                    borderWidth: 1
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Revenue Chart'
                }
            },
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 20 + context.datasetIndex * 20;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: false,
                    group: 'group' ,
                },
            }
        }
    });

    function resetChart() {
         // Clear existing data
        chartOrder.data.labels = [];
        chartOrder.data.datasets[0].data = [];
        if (chartOrder.data.datasets.length > 1) chartOrder.data.datasets[1].data = [];

        chartRevenue.data.labels = [];
        chartRevenue.data.datasets[0].data = [];
        if (chartRevenue.data.datasets.length > 1) chartRevenue.data.datasets[1].data = [];
    };

    Livewire.on('updateDateRangeEvent', (data) => {
        $('#comparedaterange span').html('Compare: No compare!');

        const performanceAll = JSON.parse(data.chartDataOriginal);

        resetChart();

        if (performanceAll.length == 0) return false;
        
        const labels_all_total = performanceAll.map(item => item.data);
        const data_all_order = performanceAll.map(item => item.totalOrder);
        const data_all_revenue = performanceAll.map(item => item.revenue);

        // Add new data
        chartOrder.data.labels = labels_all_total;
        chartOrder.data.datasets[0].data = data_all_order;

        chartRevenue.data.labels = labels_all_total;
        chartRevenue.data.datasets[0].data = data_all_revenue;

        chartOrder.update();
        chartRevenue.update();
    });

    Livewire.on('selectedUserEvent', (data) => {
        let chartDataOriginal = JSON.parse(data.chartDataOriginal);
        let chartDataCompare = JSON.parse(data.chartDataCompare);

        resetChart();

        if (chartDataOriginal.length == 0) return false;
        
        let labels_all_total = chartDataOriginal.map(item => item.data);
        let data_all_order = chartDataOriginal.map(item => item.totalOrder);
        let data_all_revenue = chartDataOriginal.map(item => item.revenue);

        // Add new data
        chartOrder.data.labels = labels_all_total;
        chartOrder.data.datasets[0].data = data_all_order;

        chartRevenue.data.labels = labels_all_total;
        chartRevenue.data.datasets[0].data = data_all_revenue;

        chartOrder.update();
        chartRevenue.update();

        if (chartDataCompare.length == 0) return false;

        labels_all_total = chartDataCompare.map(item => item.data);
        data_all_order = chartDataCompare.map(item => item.totalOrder);
        data_all_revenue = chartDataCompare.map(item => item.revenue);

        chartOrder.data.datasets.push({
            label: 'Compare Order',
            data: data_all_order,
            backgroundColor: 'rgba(144, 238, 144, 0.5)',
            borderColor: 'rgba(144, 238, 144, 1)',
            borderDash: [5, 5],
        });
        chartOrder.options.plugins.tooltip.callbacks.label = function (context) {
            let label = context.dataset.label || '';
            if (label === 'Compare Order') {
                label = 'Compare Order: ' + data_all_order[context.dataIndex];
                label += ' (' + labels_all_total[context.dataIndex] + ')';
            } else {
                label += ': ' + context.parsed.y;
            }
            return label;
        };

        chartRevenue.data.datasets.push({
            label: 'Compare Reveneu',
            data: data_all_revenue,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)', 
            borderDash: [5, 5],
        });
        chartRevenue.options.plugins.tooltip.callbacks.label = function (context) {
            let label = context.dataset.label || '';
            if (label === 'Compare Reveneu') {
                label = 'Compare Reveneu: ' + Math.round(data_all_revenue[context.dataIndex]);
                label += ' (' + labels_all_total[context.dataIndex] + ')';
            } else {
                label += ': ' + context.parsed.y;
            }
            return label;
        };

        chartOrder.update();
        chartRevenue.update();
    });

    Livewire.on('updateCompareDateRangeEvent', (data) => {
        resetChart();

        let chartDataOriginal = JSON.parse(data.chartDataOriginal);
        let chartDataCompare = JSON.parse(data.chartDataCompare);

        let labels_all_total = chartDataOriginal.map(item => item.data);
        let data_all_order = chartDataOriginal.map(item => item.totalOrder);
        let data_all_revenue = chartDataOriginal.map(item => item.revenue);

        chartOrder.data.labels = labels_all_total;
        chartOrder.data.datasets[0].data = data_all_order;

        chartRevenue.data.labels = labels_all_total;
        chartRevenue.data.datasets[0].data = data_all_revenue;

        chartOrder.update();
        chartRevenue.update();

        if (chartDataCompare.length == 0) return false;

        labels_all_total = chartDataCompare.map(item => item.data);
        data_all_order = chartDataCompare.map(item => item.totalOrder);
        data_all_revenue = chartDataCompare.map(item => item.revenue);

        chartOrder.data.datasets.push({
            label: 'Compare Order',
            data: data_all_order,
            backgroundColor: 'rgba(144, 238, 144, 0.5)',
            borderColor: 'rgba(144, 238, 144, 1)',
            borderDash: [5, 5],
        });
        chartOrder.options.plugins.tooltip.callbacks.label = function (context) {
            let label = context.dataset.label || '';
            if (label === 'Compare Order') {
                label = 'Compare Order: ' + data_all_order[context.dataIndex];
                label += ' (' + labels_all_total[context.dataIndex] + ')';
            } else {
                label += ': ' + context.parsed.y;
            }
            return label;
        };

        chartRevenue.data.datasets.push({
            label: 'Compare Reveneu',
            data: data_all_revenue,
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            borderColor: 'rgba(255, 99, 132, 1)', 
            borderDash: [5, 5],
        });
        chartRevenue.options.plugins.tooltip.callbacks.label = function (context) {
            let label = context.dataset.label || '';
            if (label === 'Compare Reveneu') {
                label = 'Compare Reveneu: ' + Math.round(data_all_revenue[context.dataIndex]);
                label += ' (' + labels_all_total[context.dataIndex] + ')';
            } else {
                label += ': ' + context.parsed.y;
            }
            return label;
        };

        chartOrder.update();
        chartRevenue.update();
    });
</script>

<script>
    var start = moment().startOf('month');
    var end = moment();
    var startCp = moment().startOf('month');
    var endCp = moment();

    $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    $('#daterange').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        alwaysShowCalendars: true,
        startDate: moment().startOf('month'),
        endDate: moment(),
        maxDate: new Date(),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, (start, end, label) => {
        $('#daterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        Livewire.emit('updateDate', start, end);
        //@this.set('fromDate', start);
        //@this.set('toDate', end);
    });

    $('#comparedaterange span').html('Compare: No compare!');

    $('#comparedaterange').daterangepicker({
        opens: 'left',
        autoUpdateInput: false,
        alwaysShowCalendars: true,
        maxDate: new Date(),
        ranges: {
            'No compare': [moment(), moment()],
            'Previous 1 month': [moment("{{ $fromDate }}").subtract(1, 'month'), moment("{{ $toDate }}").subtract(1, 'month')],
            'Previous 2 month': [moment("{{ $fromDate }}").subtract(2, 'month'), moment("{{ $toDate }}").subtract(2, 'month')],
            'Previous 3 month': [moment("{{ $fromDate }}").subtract(3, 'month'), moment("{{ $toDate }}").subtract(3, 'month')],
            'Previous Year': [moment("{{ $fromDate }}").subtract(1, 'year'), moment("{{ $toDate }}").subtract(1, 'year')],
        }
    }, (start, end, label) => {
        let distance;
        switch (label) {
            case 'Previous 1 month':
                distance = 1;
                break;
            case 'Previous 2 months':
                distance = 2;
                break;
            case 'Previous 3 months':
                distance = 3;
                break;
            case 'Previous Year':
                distance = 12;
                break;
            default:
                distance = 0;
                break;
        }

        $('#comparedaterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        Livewire.emit('updateDateCompare', start, end, distance);
    });

/*
    document.addEventListener("livewire:update", () => {
        $("#comparedaterange").daterangepicker("remove");
        $('#comparedaterange').daterangepicker({
            opens: 'left',
            autoUpdateInput: false,
            alwaysShowCalendars: true,
            maxDate: new Date(),
            ranges: {
                'No compare': [moment(), moment()],
                'Previous 1 month': [moment("{{ $fromDate }}").subtract(1, 'month'), moment("{{ $toDate }}").subtract(1, 'month')],
                'Previous 2 month': [moment("{{ $fromDate }}").subtract(2, 'month'), moment("{{ $toDate }}").subtract(2, 'month')],
                'Previous 3 month': [moment("{{ $fromDate }}").subtract(3, 'month'), moment("{{ $toDate }}").subtract(3, 'month')],
                'Previous Year': [moment("{{ $fromDate }}").subtract(1, 'year'), moment("{{ $toDate }}").subtract(1, 'year')],
            }
        }, (start, end, label) => {console.log(start);
            $('#comparedaterange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            Livewire.emit('updateDateCompare', start, end);
        });
    });
*/
</script>