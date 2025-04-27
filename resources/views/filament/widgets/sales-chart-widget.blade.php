<!-- resources/views/filament/widgets/sales-chart-widget.blade.php -->

<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="text-xl font-semibold text-gray-800">Sales Overview</h3>

    <!-- Chart container -->
    <div class="mt-4">
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('salesChart').getContext('2d');

        // Get the sales data from PHP
        var salesData = @json($salesData);

        var labels = salesData.map(function(item) {
            return item.date;
        });

        var data = salesData.map(function(item) {
            return item.total_sales;
        });

        // Create the chart
        var salesChart = new Chart(ctx, {
            type: 'line', // You can change the type to 'bar', 'pie', etc.
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Over Time',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
