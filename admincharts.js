const shopizzyColors = {
    green: '#2ecc71',
    grey: '#ecf0f1',
    blue: '#3498db',
    yellow: '#f39c12'
};

let usersChart, salesChart, cartsChart;

function loadUsersChart() {
    fetch('get_total_users.php')
        .then(res => res.json())
        .then(data => {
            const total = data.total_users ?? 0;
            const dataset = {
                labels: ['Users', 'Others'],
                datasets: [{
                    data: [total, Math.max(100 - total, 0)],
                    backgroundColor: [shopizzyColors.green, shopizzyColors.grey],
                    borderWidth: 1
                }]
            };

            if (usersChart) {
                usersChart.data = dataset;
                usersChart.update();
            } else {
                const ctx = document.getElementById('totalUsersChart').getContext('2d');
                usersChart = new Chart(ctx, {
                    type: 'pie',
                    data: dataset,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Users Distribution' }
                        }
                    }
                });
            }
        });
}

function loadSalesChart() {
    fetch('get_total_sales.php')
        .then(res => res.json())
        .then(data => {
            // Expecting data format: [{ year: '2023', total: 5000 }, { year: '2024', total: 7200 }]
            const labels = data.map(entry => entry.year);
            const values = data.map(entry => entry.total);

            const dataset = {
                labels: labels,
                datasets: [{
                    label: 'Total Sales (ZMW)',
                    data: values,
                    backgroundColor: shopizzyColors.blue,
                    borderColor: shopizzyColors.darkBlue || shopizzyColors.blue,
                    borderWidth: 1
                }]
            };

            if (salesChart) {
                salesChart.data = dataset;
                salesChart.update();
            } else {
                const ctx = document.getElementById('totalSalesChart').getContext('2d');
                salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: dataset,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            title: { display: true, text: 'Total Sales by Year' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Sales (ZMW)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Year'
                                }
                            }
                        }
                    }
                });
            }
        });
}


function loadCartsChart() {
    fetch('get_abandoned_carts.php')
        .then(res => res.json())
        .then(data => {
            const [abandoned, recovered] = data;

            const dataset = {
                labels: ['Abandoned', 'Recovered'],
                datasets: [{
                    data: [abandoned, recovered],
                    backgroundColor: [shopizzyColors.yellow, shopizzyColors.grey],
                    borderWidth: 1
                }]
            };

            if (cartsChart) {
                cartsChart.data = dataset;
                cartsChart.update();
            } else {
                const ctx = document.getElementById('abandonedCartChart').getContext('2d');
                cartsChart = new Chart(ctx, {
                    type: 'pie',
                    data: dataset,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Abandoned vs Recovered Carts This Week' }
                        }
                    }
                });
            }
        });
}

// Initial load
loadUsersChart();
loadSalesChart();
loadCartsChart();

// Auto-refresh every 60 seconds
setInterval(() => {
    loadUsersChart();
    loadSalesChart();
    loadCartsChart();
}, 60000);

// Top 5 products
document.addEventListener("DOMContentLoaded", function () {
    fetch("get_top_products.php")
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                displayTopProducts(data.products);
            } else {
                document.querySelector(".product-cards-container").innerHTML = "<p>No data available.</p>";
            }
        })
        .catch(error => {
            console.error("Error fetching data:", error);
            document.querySelector(".product-cards-container").innerHTML = "<p>Error loading data.</p>";
        });
});

function displayTopProducts(products) {
    const container = document.querySelector(".product-cards-container");
    if (!products.length) {
        container.innerHTML = "<p>No top products found.</p>";
        return;
    }

    let html = "";

    products.forEach(product => {
        html += `
            <div class="product-card">
                <img src="${product.image}" alt="${product.name}">
                <h4>${product.name}</h4>
                <p>Category: ${product.category}</p>
                <p>Interactions: ${product.interactions}</p>
            </div>
        `;
    });

    container.innerHTML = html;
}
