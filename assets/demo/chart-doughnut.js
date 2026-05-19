window.App = window.App || {};

window.App.doughnutChart = {
  chart: null,

  // Render chart
  render: function (data) {

    const canvas = document.getElementById("myDoughnutChart");

    if (!canvas) return;

    const ctx = canvas.getContext("2d");

    // Hapus chart lama
    if (this.chart && typeof this.chart.destroy === "function") {
      this.chart.destroy();
    }

    // Buat chart baru
    this.chart = new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Disetujui", "Ditolak", "Terlambat"],
        datasets: [
          {
            data: data,
            backgroundColor: ["#198754", "#dc3545", "#ffc107"],
          },
        ],
      },
      options: {
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    });
  },
};