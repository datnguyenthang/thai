<canvas id="orderChart"></canvas>
<script type="module">
    // Assuming you have the data available as PHP variables in your view
    let delayed;
    const paxOrders = {!! json_encode($paxOrders) !!};
    const paxTravels = {!! json_encode($paxTravels) !!};

    // Extracting labels and data for the chart
    // Modify this based on your data structure
    const labels_paxOrder = paxOrders.map(item => item.data);
    const data_paxOrder = paxOrders.map(item => item.pax);

    const data_paxTravel = paxTravels.map(item => item.pax);

    const orderCtx = document.getElementById('orderChart').getContext('2d');

    const orderChart = new Chart(orderCtx, {
        type: 'bar',
        data: {
            labels: labels_paxOrder,
            datasets: [
                {
                    label: 'Pax - New Order',
                    data: data_paxOrder,
                    backgroundColor: 'rgba(144, 238, 144, 0.5)',
                    borderColor: 'rgba(144, 238, 144, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Pax - Travelled',
                    data: data_paxTravel,
                    backgroundColor: 'rgba(147, 112, 219, 0.5)',
                    borderColor: 'rgba(147, 112, 219, 1)',
                    borderWidth: 1,
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
                    stacked: false,
                    group: 'group' ,
                },
                y: {
                    stacked: false
                }
            }
        }
    });

    Livewire.on('ordersUpdated', (data) => {
        const paxOrderNewData = JSON.parse(data.paxOrdersNewData);
        const paxTravelNewData = JSON.parse(data.paxTravelsNewData);

        // Clear existing data
        orderChart.data.labels = [];
        orderChart.data.datasets[0].data = [];
        orderChart.data.datasets[1].data = [];

        if (paxOrderNewData.length == 0) return false;

        const labels_newData = paxOrderNewData.map(item => item.data);
        const data_paxOrderData = paxOrderNewData.map(item => item.pax);
        const data_PaxTravelData = paxTravelNewData.map(item => item.pax);

        // Add new data
        orderChart.data.labels = labels_newData;
        orderChart.data.datasets[0].data = data_paxOrderData;
        orderChart.data.datasets[1].data = data_PaxTravelData;

        orderChart.update();

    });
</script>