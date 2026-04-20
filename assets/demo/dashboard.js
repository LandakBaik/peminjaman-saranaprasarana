document.addEventListener("DOMContentLoaded", function () {
  const fullData = [
    { value: 10000 },
    { value: 30162 },
    { value: 26263 },
    { value: 18394 },
    { value: 18287 },
    { value: 28682 },
    { value: 31274 },
    { value: 33259 },
    { value: 25849 },
    { value: 24159 },
    { value: 32651 },
    { value: 31984 },
    { value: 38451 },
  ];

  // =========================
  // INIT SEMUA
  // =========================
  App.lineChart.init();

  // =========================
  // FILTER LOGIC
  // =========================
  function processData(type) {
    let labels = [];
    let values = fullData.map((d) => d.value);

    if (type === "daily") {
      labels = values.map((_, i) => "Hari " + (i + 1));
    } else if (type === "weekly") {
      labels = ["Minggu 1", "Minggu 2", "Minggu 3", "Minggu 4"];

      let minggu = [0, 0, 0, 0];
      values.forEach((val, i) => {
        const index = Math.floor(i / 3);
        if (index < 4) minggu[index] += val;
      });

      values = minggu;
    } else if (type === "monthly") {
      labels = ["Bulan 1", "Bulan 2", "Bulan 3"];

      let bulan = [0, 0, 0];
      values.forEach((val, i) => {
        const index = Math.floor(i / 5);
        if (index < 3) bulan[index] += val;
      });

      values = bulan;
    }

    return { labels, values };
  }

  // =========================
  // UPDATE SEMUA KOMPONEN
  // =========================
  function updateDashboard(type) {
    const { labels, values } = processData(type);

    App.lineChart.update(labels, values);

    // dummy KPI
    const kpiData = [8, 1, 1];

    App.kpi.update(kpiData);
    App.doughnutChart.render(kpiData);
  }

  // =========================
  // EVENT
  // =========================
  const filter = document.getElementById("filterType");
  if (filter) {
    filter.addEventListener("change", function () {
      updateDashboard(this.value);
    });
  }

  // =========================
  // INIT DEFAULT
  // =========================
  updateDashboard("daily");
});
