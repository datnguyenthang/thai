<canvas id="orderChart"></canvas>
<script type="module">
    // Assuming you have the data available as PHP variables in your view
    let delayed;
    const orderData = {!! json_encode($orders) !!};
    const paxesData = {!! json_encode($paxes) !!};

    // Extracting labels and data for the chart
    // Modify this based on your data structure
    const labels_order = orderData.map(item => item.data);
    const data_order = orderData.map(item => item.totalOrder);

    const labels_pax = paxesData.map(item => item.data);
    const data_pax = paxesData.map(item => item.pax);


    const orderCtx = document.getElementById('orderChart').getContext('2d');

    const orderChart = new Chart(orderCtx, {
        type: 'bar',
        data: {
            labels: labels_order,
            datasets: [
                {
                label: 'Order',
                data: data_order,
                backgroundColor: 'rgba(144, 238, 144, 0.5)',
                borderColor: 'rgba(144, 238, 144, 1)',
                borderWidth: 1
                },
                {
                    label: 'Pax',
                    data: data_pax,
                    type: 'line',
                    backgroundColor: 'rgba(147, 112, 219, 0.5)',
                    borderColor: 'rgba(147, 112, 219, 1)',
                    borderWidth: 1,
                    fill: false
                }
            ]
        },
        options: {
            animation: {
                onComplete: () => {
                    delayed = true;
                },
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default' && !delayed) {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true
                }
            }
        }
    });

    Livewire.on('ordersUpdated', (data) => {
        const ordersNewData = JSON.parse(data.ordersNewData);
        const paxesNewData = JSON.parse(data.paxesNewData);
        // Clear existing data
        orderChart.data.labels = [];
        orderChart.data.datasets[0].data = [];
        orderChart.data.datasets[1].data = [];

        if (ordersNewData.length == 0) return false;

        const labels_newData = ordersNewData.map(item => item.data);
        const data_newOrderData = ordersNewData.map(item => item.totalOrder);
        const data_newPaxData = paxesNewData.map(item => item.pax);

        // Add new data
        orderChart.data.labels = labels_newData;
        orderChart.data.datasets[0].data = data_newOrderData;
        orderChart.data.datasets[1].data = data_newPaxData;

        orderChart.update();

    });
</script>