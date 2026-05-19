document.addEventListener('DOMContentLoaded', function () {

    // DOM utama
    const searchInput = document.getElementById('searchBarang');
    const tableBody   = document.getElementById('barangBody');

    const filterRuangan = document.getElementById('filterRuangan');
    const filterStok    = document.getElementById('filterStok');

    const btnApply = document.getElementById('applyBarangFilter');
    const btnReset = document.getElementById('resetBarangFilter');

    const rowCount = document.getElementById('rowCountBarang');

    const selectAll = document.getElementById('selectAllBarang');

    const getRowCheckboxes = () =>
        tableBody.querySelectorAll('.row-checkbox');


    // Select semua checkbox
    if (selectAll) {

        selectAll.addEventListener('change', function () {

            getRowCheckboxes().forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }


    // Sinkron checkbox
    if (tableBody) {

        tableBody.addEventListener('change', function (e) {

            if (e.target.classList.contains('row-checkbox')) {

                const all = getRowCheckboxes();

                if (selectAll) {
                    selectAll.checked =
                        [...all].every(cb => cb.checked);
                }
            }
        });
    }


    // Search realtime
    if (searchInput) {
        searchInput.addEventListener('input', applyAll);
    }


    // Tombol filter
    if (btnApply) {
        btnApply.addEventListener('click', applyAll);
    }

    // Reset filter
    if (btnReset) {

        btnReset.addEventListener('click', function () {

            if (filterRuangan) filterRuangan.value = '';

            if (filterStok) filterStok.value = '';

            applyAll();
        });
    }


    // Filter tabel
    function applyAll() {

        const searchTerm = searchInput.value
            .toLowerCase()
            .trim();

        const ruanganVal = filterRuangan.value;

        const stokVal = filterStok.value;

        const rows = tableBody.querySelectorAll('tr');

        let visible = 0;

        rows.forEach(row => {

            const text = row.textContent
                .toLowerCase();

            const ruangan =
                row.getAttribute('data-ruangan');

            const stok =
                row.getAttribute('data-tersedia');

            // Pencocokan filter
            const matchSearch =
                !searchTerm ||
                text.includes(searchTerm);

            const matchRuangan =
                !ruanganVal ||
                ruangan === ruanganVal;

            const matchStok =
                !stokVal ||
                stok === stokVal;

            // Tampilkan hasil
            if (
                matchSearch &&
                matchRuangan &&
                matchStok
            ) {

                row.style.display = '';

                visible++;

            } else {

                row.style.display = 'none';
            }

        });

        // Update jumlah data
        const total = rows.length;

        rowCount.textContent =
            `${visible} of ${total}`;
    }


    // Render awal
    applyAll();

});