// Chart.defaults.global.defaultFontFamily =
//   '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
// Chart.defaults.global.defaultFontColor = "#292b2c";

// let myLineChart;

// function initLineChart(data) {
//   const ctx = document.getElementById("myAreaChart");

//   myLineChart = new Chart(ctx, {
//     type: "line",
//     data: {
//       labels: data.map((d) => d.date),
//       datasets: [
//         {
//           label: "Penjualan",
//           lineTension: 0.3,
//           backgroundColor: "rgba(2,117,216,0.2)",
//           borderColor: "rgba(2,117,216,1)",
//           data: data.map((d) => d.value),
//         },
//       ],
//     },
//     options: {
//       legend: { display: false },
//       hover: { mode: null },
//     },
//   });
// }

// function updateLineChartCustom(labels, values) {
//   myLineChart.data.labels = labels;
//   myLineChart.data.datasets[0].data = values;
//   myLineChart.update();
// }