document.addEventListener("DOMContentLoaded", function () {
  const chartElement = document.getElementById('weeklyUploadsChart');

  if (chartElement) {
    fetch("api/weekly_uploads.php")
      .then(response => response.json())
      .then(data => {
        const labels = data.map(item => item.week);
        const values = data.map(item => item.uploads);

        new Chart(chartElement.getContext('2d'), {
          type: 'line',
          data: {
            labels: labels,
            datasets: [{
              label: 'Documents Uploaded',
              data: values,
              backgroundColor: 'rgba(0, 79, 128, 0.7)',
              borderColor: '#004F80',
              borderWidth: 2
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
              duration: 1500,
              easing: 'easeOutBounce'
            },
            scales: {
              y: {
                beginAtZero: true,
                stepSize: 1
              }
            }
          }
        });
      })
      .catch(error => console.error("Error fetching weekly uploads:", error));
  }
});
