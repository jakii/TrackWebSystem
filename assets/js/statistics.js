document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById("statisticsChart").getContext("2d");

  fetch("api/api_statistics.php")
    .then(res => res.json())
    .then(data => {
      new Chart(ctx, {
        type: "line",
        data: {
          labels: data.labels,
          datasets: [
            {
              label: "Document Views",
              data: data.views,
              borderColor: "#2AB7CA",
              backgroundColor: "rgba(42,183,202,0.2)",
              fill: true,
              tension: 0.3
            },
            {
              label: "Document Downloads",
              data: data.downloads,
              borderColor: "#004F80",
              backgroundColor: "rgba(0,79,128,0.2)",
              fill: true,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: "bottom" }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: { stepSize: 1 }
            }
          }
        }
      });
    });
});
