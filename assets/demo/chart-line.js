window.App = window.App || {};

window.App.lineChart = {
  chart: null,

  // Inisialisasi chart
  init: function () {

    const canvas = document.getElementById("myAreaChart");

    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    this.chart = new Chart(ctx, {
      type: "line",
      data: {
        labels: [],
        datasets: [
          {
            label: "Peminjaman",
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            data: [],
          },
        ],
      },
      options: {
        plugins: {
          legend: { display: false },
        },
      },
    });
  },

  // Update data chart
  update: function (labels, values) {

    if (!this.chart) return;

    this.chart.data.labels = labels;
    this.chart.data.datasets[0].data = values;

    this.chart.update();
  },
};