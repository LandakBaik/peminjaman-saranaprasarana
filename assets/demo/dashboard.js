document.addEventListener("DOMContentLoaded", function () {

  App.lineChart.init();

  // Update dashboard
  function updateDashboard() {

    if (!window.dashboardStats) return;

    // Update line chart
    if (window.dashboardStats.trend) {

      const { labels, values } = window.dashboardStats.trend;

      App.lineChart.update(labels, values);
    }

    // Data KPI
    const kpiData = [
      window.dashboardStats.total || 0,
      window.dashboardStats.disetujui || 0,
      window.dashboardStats.ditolak || 0,
      window.dashboardStats.terlambat || 0
    ];

    App.kpi.update(kpiData);

    // Update doughnut chart
    App.doughnutChart.render([
      window.dashboardStats.disetujui || 0,
      window.dashboardStats.ditolak || 0,
      window.dashboardStats.terlambat || 0
    ]);
  }

  // Render awal
  updateDashboard();

  // Listener filter
  const filter = document.getElementById("filterType");

  if (filter) {
    filter.addEventListener("change", function () {

      // updateDashboard();

    });
  }
});