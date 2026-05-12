document.addEventListener("DOMContentLoaded", function () {
  // Initialize charts
  App.lineChart.init();

  function updateDashboard() {
    if (!window.dashboardStats) return;

    // Update Line Chart (Trend)
    if (window.dashboardStats.trend) {
      const { labels, values } = window.dashboardStats.trend;
      App.lineChart.update(labels, values);
    }

    // Update KPI and Doughnut Chart
    const kpiData = [
      window.dashboardStats.total || 0,
      window.dashboardStats.disetujui || 0,
      window.dashboardStats.ditolak || 0,
      window.dashboardStats.terlambat || 0
    ];

    App.kpi.update(kpiData);

    // Doughnut chart only shows the breakdown (excluding total)
    App.doughnutChart.render([
      window.dashboardStats.disetujui || 0,
      window.dashboardStats.ditolak || 0,
      window.dashboardStats.terlambat || 0
    ]);
  }

  // Initial update
  updateDashboard();

  // Handle filter changes (handled by page reload in dashboard.php, 
  // but we keep the listener if needed for future SPA-like behavior)
  const filter = document.getElementById("filterType");
  if (filter) {
    filter.addEventListener("change", function () {
      // In current implementation, dashboard.php reloads the page on change
      // so this might not be strictly necessary, but good for completeness
      // updateDashboard(); 
    });
  }
});