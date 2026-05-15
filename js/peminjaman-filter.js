

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
    const filterRoom    = document.getElementById('filterRoom');
    const filterPeminjam = document.getElementById('filterPeminjam');
    const filterStatusKembali = document.getElementById('filterStatusKembali');

    // Helper: ambil semua row checkbox
    const getRowCheckboxes = () => tableBody.querySelectorAll('.row-checkbox');


    // ========== Pagination State ==========
    let currentPage = 1;
    let rowsPerPage = 10;
    let filteredRows = [];

    const rowsPerPageSelect = document.getElementById('rowsPerPage');
    const btnPrevPage       = document.getElementById('btnPrevPage');
    const btnNextPage       = document.getElementById('btnNextPage');
    const currentPageNum    = document.getElementById('currentPageNum');

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
                selectAll.checked = [...all].every(cb => cb.checked);
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
            if(filterStatus) filterStatus.value  = '';
            if(filterJenis) filterJenis.value   = '';
            if(filterTanggal) filterTanggal.value = '';
            if(filterRoom) filterRoom.value = '';
            if(filterPeminjam) filterPeminjam.value = '';
            if(filterStatusKembali) filterStatusKembali.value = '';
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
        const statusVal  = filterStatus ? filterStatus.value : '';
        const jenisVal   = filterJenis ? filterJenis.value : '';
        const tanggalVal = filterTanggal ? filterTanggal.value : '';
        const roomVal    = filterRoom ? filterRoom.value.toLowerCase().trim() : '';
        const peminjamVal = filterPeminjam ? filterPeminjam.value.toLowerCase().trim() : '';
        const statusKembaliVal = filterStatusKembali ? filterStatusKembali.value : '';

        const rows = tableBody ? tableBody.querySelectorAll('tr[data-status]') : [];
        filteredRows = [];

        rows.forEach(row => {
            const text   = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const type   = row.getAttribute('data-type');
            const rowDate = row.getAttribute('data-date');
            const room   = (row.getAttribute('data-room') || '').toLowerCase();
            const peminjam = (row.getAttribute('data-peminjam') || '').toLowerCase();
            const statusKembali = row.getAttribute('data-status-kembali');

            const matchSearch = !searchTerm || text.includes(searchTerm);
            const matchStatus = !statusVal  || status === statusVal;
            const matchType   = !jenisVal   || type === jenisVal;
            const matchDate   = !tanggalVal || rowDate === tanggalVal;
            const matchRoom   = !roomVal    || room.includes(roomVal);
            const matchPeminjam = !peminjamVal || peminjam.includes(peminjamVal);
            const matchStatusKembali = !statusKembaliVal || statusKembali === statusKembaliVal;

            if (matchSearch && matchStatus && matchType && matchDate && matchRoom && matchPeminjam && matchStatusKembali) {
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
