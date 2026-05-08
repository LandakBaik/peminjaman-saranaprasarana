document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // DOM
    // =========================

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


    // =========================
    // Checkbox
    // =========================

    selectAll.addEventListener('change', function () {

        getRowCheckboxes().forEach(cb => {
            cb.checked = selectAll.checked;
        });

    });


    tableBody.addEventListener('change', function (e) {

        if (e.target.classList.contains('row-checkbox')) {

            const all = getRowCheckboxes();

            selectAll.checked =
                [...all].every(cb => cb.checked);

        }

    });


    // =========================
    // Search realtime
    // =========================

    searchInput.addEventListener('input', applyAll);


    // =========================
    // Filter button
    // =========================

    btnApply.addEventListener('click', applyAll);

    btnReset.addEventListener('click', function () {

        filterRuangan.value = '';
        filterStok.value = '';

        applyAll();

    });


    // =========================
    // Core Filter
    // =========================

    function applyAll() {

        const searchTerm = searchInput.value
            .toLowerCase()
            .trim();

        const ruanganVal = filterRuangan.value;
        const stokVal    = filterStok.value;

        const rows = tableBody.querySelectorAll('tr');

        let visible = 0;

        rows.forEach(row => {

            const text = row.textContent
                .toLowerCase();

            const ruangan = row.getAttribute('data-ruangan');

            const stok = row.getAttribute('data-tersedia');

            // =========================
            // Matching
            // =========================

            const matchSearch =
                !searchTerm ||
                text.includes(searchTerm);

            const matchRuangan =
                !ruanganVal ||
                ruangan === ruanganVal;

            const matchStok =
                !stokVal ||
                stok === stokVal;

            // =========================
            // Final check
            // =========================

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

        // =========================
        // Update row count
        // =========================

        const total = rows.length;

        rowCount.textContent =
            `${visible} of ${total}`;

    }


    // =========================
    // Init
    // =========================

    applyAll();

});