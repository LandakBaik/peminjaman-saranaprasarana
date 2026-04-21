document.addEventListener("DOMContentLoaded", function () {
  const fullData = [
    { value: 1 },
    { value: 3 },
    { value: 2 },
    { value: 1 },
    { value: 8 },
    { value: 6 },
    { value: 3 },
    { value: 3 },
    { value: 2 },
    { value: 2 },
    { value: 5 },
    { value: 4 },
    { value: 3 },
  ];

  App.lineChart.init();

  function processData(type) {
    let labels = [];
    let values = fullData.map((d) => d.value);

    if (type === "daily") {
      values = values.slice(-7);
      labels = values.map(
        (_, i) =>
          ["Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"][
            i % 7
          ],
      );

      let hari = [0, 0, 0, 0, 0, 0, 0];
      values.forEach((val, i) => {
        const index = Math.floor(i / 1) % 7;
        hari[index] += val;
      });
    } else if (type === "weekly") {
      labels = ["Minggu 1", "Minggu 2", "Minggu 3", "Minggu 4"];

      let minggu = [0, 0, 0, 0];
      values.forEach((val, i) => {
        const index = Math.floor(i / 3);
        if (index < 4) minggu[index] += val;
      });

      values = minggu;
    } else if (type === "monthly") {
      labels = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
      ];

      let bulan = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      values.forEach((val, i) => {
        const index = Math.floor(i / 5);
        if (index < 12) bulan[index] += val;
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

  updateDashboard("daily");
});
