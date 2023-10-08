<canvas id="revenuesChart"></canvas>
<script type="module">
    let delayed;
    // Assuming you have the data available as PHP variables in your view
    const revenueOrderData = {!! json_encode($revenueOrders) !!};
    const revenueRideData = {!! json_encode($revenueRides) !!};

    // Extracting labels and data for the chart for Order
    const labels_revenueOrder = revenueOrderData.map(item => item.data);
    const data_revenueOrder = revenueOrderData.map(item => item.revenue);

    // Extracting labels and data for the chart for Ride
    const labels_revenueRide = revenueRideData.map(item => item.data);
    const data_revenueRide = revenueRideData.map(item => item.revenue);

    const revenueCtx = document.getElementById('revenuesChart').getContext('2d');

    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: labels_revenueOrder,
            datasets: [
                {
                    label: 'New Order',
                    data: data_revenueOrder,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Ride',
                    data: data_revenueRide,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',  // Use a different color
                    borderColor: 'rgba(255, 99, 132, 1)',       // Use a different border color
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
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
            },
        }
    });

    Livewire.on('revenuesUpdated', (data) => {
        const revenueOrderNewData = JSON.parse(data.revenueOrderNewData);
        const revenueRideNewData = JSON.parse(data.revenueRideNewData);

        // Clear existing data
        revenueChart.data.labels = [];
        revenueChart.data.datasets[0].data = [];
        revenueChart.data.datasets[1].data = [];

        if (revenueOrderNewData.length == 0) return false;

        const labels_orderNewData = revenueOrderNewData.map(item => item.data);
        const data_orderNewData = revenueOrderNewData.map(item => item.revenue);
        const data_rideNewData = revenueRideNewData.map(item => item.revenue);

        // Add new data
        revenueChart.data.labels = labels_orderNewData;
        revenueChart.data.datasets[0].data = data_orderNewData;
        revenueChart.data.datasets[1].data = data_rideNewData;

        revenueChart.update();

    });
</script>