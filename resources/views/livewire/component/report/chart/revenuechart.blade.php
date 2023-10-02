<canvas id="revenuesChart"></canvas>
<script type="module">
    let delayed;
    // Assuming you have the data available as PHP variables in your view
    const revenueData = {!! json_encode($revenues) !!};

    // Extracting labels and data for the chart
    const labels_revenue = revenueData.map(item => item.data); // Modify this based on your data structure
    const data_revenue = revenueData.map(item => item.revenue);
    const revenueCtx = document.getElementById('revenuesChart').getContext('2d');

    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: labels_revenue,
            datasets: [{
                label: 'Revenue',
                data: data_revenue,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
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
            },
        }
    });

    Livewire.on('revenuesUpdated', (data) => {
        const revenueNewData = JSON.parse(data.revenuesNewData);
        // Clear existing data
        revenueChart.data.labels = [];
        revenueChart.data.datasets[0].data = [];

        if (revenueNewData.length == 0) return false;

        const labels_newData = revenueNewData.map(item => item.data);
        const data_newData = revenueNewData.map(item => item.revenue);

        // Add new data
            revenueChart.data.labels = labels_newData;
            revenueChart.data.datasets[0].data = data_newData;

        revenueChart.update();

    });
</script>