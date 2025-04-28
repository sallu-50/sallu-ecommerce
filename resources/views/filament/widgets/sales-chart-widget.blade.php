<div class="p-6 bg-white rounded-lg shadow">
    <h3 class="text-xl font-semibold text-gray-800">Sales Overview</h3>

    <!-- Display your name here -->
    <p class="mt-2 text-gray-600">Welcome, Salman!</p>

    <!-- Display total sales and number of orders -->
    <div class="mt-4 text-lg text-gray-700">
        <p>Total Completed orders: {{ number_format($salesData->count()) }}</p>
        <p>Total Sales: ${{ number_format($totalSales, 2) }}</p>
        <p>Total Orders: {{ $totalOrders }}</p>
    </div>

    <div>
        @foreach ($salesData as $order)
            <p>{{ $order->toJson() }}</p>
        @endforeach
    </div>

    <!-- Chart -->
    <div class="mt-4">
        <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Ensure the sales data is passed correctly from PHP to JavaScript
        const salesData = @json($salesData);

        // Log the salesData to console to check if data is correctly passed
        console.log(salesData);

        // Prepare data for chart
        const labels = salesData.map(item => item.date);
        const data = salesData.map(item => item.total_sales);

        // Set up the chart
        const ctx = document.getElementById('salesChart').getContext('2d');

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sales Over Time',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
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
