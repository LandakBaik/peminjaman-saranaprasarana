document.addEventListener('DOMContentLoaded', function () {

    // ========== DOM Elements ==========
    const searchInput   = document.getElementById('searchRiwayat');
    const tableBody     = document.getElementById('riwayatBody');
    const rowCountEl    = document.getElementById('rowCountRiwayat');
    const selectAll     = document.getElementById('selectAllRiwayat');
    const btnApply      = document.getElementById('btnApplyRiwayatFilter');
    const btnReset      = document.getElementById('btnResetRiwayatFilter');
    const filterStatus  = document.getElementById('filterStatusRiwayat');
    const filterJenis   = document.getElementById('filterJenisRiwayat');
    const filterTanggal = document.getElementById('filterTanggalRiwayat');

    // ========== Pagination State ==========
    let currentPage = 1;
    let rowsPerPage = 10;
    let filteredRows = [];

    const rowsPerPageSelect = document.getElementById('rowsPerPageRiwayat');
    const btnPrevPage       = document.getElementById('btnPrevPageRiwayat');
    const btnNextPage       = document.getElementById('btnNextPageRiwayat');
    const currentPageNum    = document.getElementById('currentPageNumRiwayat');

    // ========== Checkbox ==========

    const getRowCheckboxes = () => {
        if (!tableBody) return [];
        return tableBody.querySelectorAll('.row-checkbox');
    };

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            getRowCheckboxes().forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }

    if (tableBody) {
        tableBody.addEventListener('change', function (e) {
            if (e.target.classList.contains('row-checkbox')) {
                const all = getRowCheckboxes();
                if (selectAll) selectAll.checked = [...all].every(cb => cb.checked);
            }
        });
    }

    // ========== Events ==========

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            currentPage = 1;
            applyAll();
        });
    }

    if (btnApply) {
        btnApply.addEventListener('click', function () {
            currentPage = 1;
            applyAll();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function () {
            if (filterStatus) filterStatus.value = '';
            if (filterJenis) filterJenis.value = '';
            if (filterTanggal) filterTanggal.value = '';
            currentPage = 1;
            applyAll();
        });
    }

    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', function () {
            rowsPerPage = parseInt(this.value);
            currentPage = 1;
            renderPagination();
        });
    }

    if (btnPrevPage) {
        btnPrevPage.addEventListener('click', function () {
            if (currentPage > 1) {
                currentPage--;
                renderPagination();
            }
        });
    }

    if (btnNextPage) {
        btnNextPage.addEventListener('click', function () {
            const maxPage = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < maxPage) {
                currentPage++;
                renderPagination();
            }
        });
    }

    // ========== Core Logic ==========

    function applyAll() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const statusVal  = filterStatus ? filterStatus.value.toLowerCase().trim() : '';
        const jenisVal   = filterJenis ? filterJenis.value.toLowerCase().trim() : '';
        const tanggalVal = filterTanggal ? filterTanggal.value : '';

        const rows = tableBody ? tableBody.querySelectorAll('tr[data-status]') : [];
        filteredRows = [];

        rows.forEach(row => {
            const text   = row.textContent.toLowerCase();
            const status = (row.getAttribute('data-status') || '').toLowerCase().trim();
            const type   = (row.getAttribute('data-type') || '').toLowerCase().trim();
            const date   = (row.getAttribute('data-date') || '');

            const matchSearch = !searchTerm || text.includes(searchTerm);
            const matchStatus = !statusVal || status === statusVal;
            const matchJenis  = !jenisVal  || type === jenisVal;
            const matchDate   = !tanggalVal || date === tanggalVal;

            if (matchSearch && matchStatus && matchJenis && matchDate) {
                filteredRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        renderPagination();
    }

    function renderPagination() {
        const total = filteredRows.length;
        const maxPage = Math.ceil(total / rowsPerPage) || 1;

        if (currentPage > maxPage) currentPage = maxPage;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        // Hide all first
        const allRows = tableBody ? tableBody.querySelectorAll('tr[data-status]') : [];
        allRows.forEach(r => r.style.display = 'none');

        // Show only current page
        filteredRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
                // Update sequential number (assuming No is in the 3nd cell, index 1)
                // Wait, No is index 1 because checkbox is index 0
                const noCell = row.cells[1];
                if (noCell) noCell.textContent = index + 1;
            } else {
                row.style.display = 'none';
            }
        });

        // Update UI
        if (rowCountEl) {
            const endDisplay = Math.min(endIndex, total);
            const startDisplay = total === 0 ? 0 : startIndex + 1;
            rowCountEl.textContent = `Menampilkan ${startDisplay}-${endDisplay} dari ${total} data`;
        }

        if (currentPageNum) {
            currentPageNum.textContent = currentPage;
        }

        if (btnPrevPage) btnPrevPage.disabled = (currentPage === 1);
        if (btnNextPage) btnNextPage.disabled = (currentPage === maxPage || total === 0);
    }

    // ========== Init ==========
    applyAll();

});
