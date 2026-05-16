
document.addEventListener('DOMContentLoaded', function () {

    // ========== DOM Elements ==========
    const searchInput   = document.getElementById('searchRuangan');
    const tableBody     = document.getElementById('ruanganBody');
    const rowCountEl    = document.getElementById('rowCountRuangan');
    const selectAll     = document.getElementById('selectAllRuangan');
    const btnApply      = document.getElementById('btnApplyRuanganFilter');
    const btnReset      = document.getElementById('btnResetRuanganFilter');
    const filterTipe    = document.getElementById('filterTipe');
    const filterKapasitas = document.getElementById('filterKapasitas');
    const filterAset    = document.getElementById('filterAset');
    const filterTanggal = document.getElementById('filterTanggal');

    // Helper: ambil semua row checkbox
    const getRowCheckboxes = () => tableBody.querySelectorAll('.row-checkbox');


    // ========== Pagination State ==========
    let currentPage = 1;
    let rowsPerPage = 10;
    let filteredRows = [];

    const rowsPerPageSelect = document.getElementById('rowsPerPageRuangan');
    const btnPrevPage       = document.getElementById('btnPrevPageRuangan');
    const btnNextPage       = document.getElementById('btnNextPageRuangan');
    const currentPageNum    = document.getElementById('currentPageNumRuangan');

    // ========== Checkbox ==========

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            getRowCheckboxes().forEach(cb => cb.checked = selectAll.checked);
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

    // ========== Search ==========

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            applyAll();
        });
    }

    // ========== Filter ==========

    if (btnApply) {
        btnApply.addEventListener('click', function () {
            applyAll();
        });
    }

    if (btnReset) {
        btnReset.addEventListener('click', function () {
            if(filterTipe) filterTipe.value  = '';
            if(filterKapasitas) filterKapasitas.value = '';
            if(filterAset) filterAset.value = '';
            if(filterTanggal) filterTanggal.value = '';
            applyAll();
        });
    }

    // ========== Pagination Listeners ==========

    if (rowsPerPageSelect) {
        rowsPerPageSelect.addEventListener('change', function () {
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
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage) || 1;
            if (currentPage < totalPages) {
                currentPage++;
                renderPagination();
            }
        });
    }

    // ========== Core: Apply Search + Filter ==========

    function applyAll() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const tipeVal    = filterTipe ? filterTipe.value.toLowerCase().trim() : '';
        const kapasitasVal = filterKapasitas ? parseInt(filterKapasitas.value) : 0;
        const asetVal    = filterAset ? parseInt(filterAset.value) : 0;
        const tanggalVal = filterTanggal ? filterTanggal.value : '';

        const rows = tableBody ? tableBody.querySelectorAll('tr[data-tipe]') : [];
        filteredRows = [];

        rows.forEach(row => {
            const text   = row.textContent.toLowerCase();
            const tipe   = (row.getAttribute('data-tipe') || '').toLowerCase().trim();
            const kapasitas = parseInt(row.getAttribute('data-kapasitas') || '0');
            const aset   = parseInt(row.getAttribute('data-aset') || '0');
            const tanggal = row.getAttribute('data-tanggal') || '';

            const matchSearch = !searchTerm || text.includes(searchTerm);
            const matchTipe   = !tipeVal   || tipe === tipeVal;
            const matchKapasitas = !kapasitasVal || kapasitas >= kapasitasVal;
            const matchAset   = !asetVal || aset >= asetVal;
            const matchTanggal = !tanggalVal || tanggal === tanggalVal;

            if (matchSearch && matchTipe && matchKapasitas && matchAset && matchTanggal) {
                filteredRows.push(row);
            } else {
                row.style.display = 'none';
            }
        });

        currentPage = 1;
        renderPagination();
    }

    // ========== Render Pagination ==========

    function renderPagination() {
        if (!rowsPerPageSelect) return;

        rowsPerPage = parseInt(rowsPerPageSelect.value, 10);
        const totalRows = filteredRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIndex = (currentPage - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;

        filteredRows.forEach((row, index) => {
            if (index >= startIndex && index < endIndex) {
                row.style.display = '';
                // Update sequential number (assuming No is in the 2nd cell, index 1)
                const noCell = row.cells[1];
                if (noCell) noCell.textContent = index + 1;
            } else {
                row.style.display = 'none';
            }
        });

        if (rowCountEl) {
            if (totalRows === 0) {
                rowCountEl.textContent = 'Menampilkan 0 data';
            } else {
                const endDisplay = Math.min(endIndex, totalRows);
                rowCountEl.textContent = `Menampilkan ${startIndex + 1}\u2013${endDisplay} dari ${totalRows} data`;
            }
        }

        if (currentPageNum) currentPageNum.textContent = currentPage;
        if (btnPrevPage) btnPrevPage.disabled = currentPage === 1;
        if (btnNextPage) btnNextPage.disabled = currentPage === totalPages;
    }

    // Initial Call
    applyAll();

});
