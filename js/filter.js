

document.addEventListener('DOMContentLoaded', function () {

    // ========== DOM Elements ==========
    const searchInput   = document.getElementById('searchInput');
    const tableBody     = document.getElementById('peminjamanBody');
    const rowCountEl    = document.getElementById('rowCount');
    const selectAll     = document.getElementById('selectAll');
    const btnApply      = document.getElementById('btnApplyFilter');
    const btnReset      = document.getElementById('btnResetFilter');
    const filterStatus  = document.getElementById('filterStatus');
    const filterJenis   = document.getElementById('filterJenis');
    const filterTanggal = document.getElementById('filterTanggal');

    // Helper: ambil semua row checkbox
    const getRowCheckboxes = () => tableBody.querySelectorAll('.row-checkbox');


    // ========== Checkbox ==========

    selectAll.addEventListener('change', function () {
        getRowCheckboxes().forEach(cb => cb.checked = selectAll.checked);
    });

    tableBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-checkbox')) {
            const all = getRowCheckboxes();
            selectAll.checked = [...all].every(cb => cb.checked);
        }
    });


    // ========== Search ==========

    searchInput.addEventListener('input', function () {
        applyAll();
    });


    // ========== Filter ==========

    btnApply.addEventListener('click', function () {
        applyAll();
    });

    btnReset.addEventListener('click', function () {
        filterStatus.value  = '';
        filterJenis.value   = '';
        filterTanggal.value = '';
        applyAll();
    });


    // ========== Core: Apply Search + Filter ==========

    function applyAll() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusVal  = filterStatus.value;
        const jenisVal   = filterJenis.value;

        const rows     = tableBody.querySelectorAll('tr');
        let visible    = 0;
        const totalRows = rows.length;

        rows.forEach(row => {
            const text   = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const type   = row.getAttribute('data-type');

            const matchSearch = !searchTerm || text.includes(searchTerm);
            const matchStatus = !statusVal  || status === statusVal;
            const matchType   = !jenisVal   || type === jenisVal;

            if (matchSearch && matchStatus && matchType) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        // Update row count
        if (visible === 0) {
            rowCountEl.textContent = '0 of ' + totalRows;
        } else {
            rowCountEl.textContent = '1\u2013' + visible + ' of ' + visible;
        }
    }

});
