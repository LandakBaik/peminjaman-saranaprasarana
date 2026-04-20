window.App = window.App || {};

window.App.kpi = {
  update: function (data) {
    const [disetujui, ditolak, terlambat] = data;
    const total = disetujui + ditolak + terlambat;

    const elTotal = document.getElementById("totalPinjam");
    const elSetuju = document.getElementById("disetujui");
    const elTolak = document.getElementById("ditolak");
    const elTelat = document.getElementById("terlambat");

    if (elTotal) elTotal.innerText = total.toLocaleString("id-ID");
    if (elSetuju) elSetuju.innerText = disetujui;
    if (elTolak) elTolak.innerText = ditolak;
    if (elTelat) elTelat.innerText = terlambat;
  },
};
