document.addEventListener('DOMContentLoaded', function () {

    // DOM utama
    const searchInput =
        document.getElementById('searchUser');

    const tableBody =
        document.getElementById('userBody');

    const rowCountEl =
        document.getElementById('rowCountUser');

    const selectAll =
        document.getElementById('selectAllUser');

    const btnApply =
        document.getElementById('applyUserFilter');

    const btnReset =
        document.getElementById('resetUserFilter');

    const filterRole =
        document.getElementById('filterRole');

    const filterStatus =
        document.getElementById('filterStatusUser');


    // State pagination
    let currentPage = 1;

    let rowsPerPage = 10;

    let filteredRows = [];

    const rowsPerPageSelect =
        document.getElementById('rowsPerPageUser');

    const btnPrevPage =
        document.getElementById('btnPrevPageUser');

    const btnNextPage =
        document.getElementById('btnNextPageUser');

    const currentPageNum =
        document.getElementById('currentPageNumUser');


    // Ambil checkbox baris
    const getRowCheckboxes = () => {

        if (!tableBody) return [];

        return tableBody.querySelectorAll('.row-checkbox');
    };


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

        searchInput.addEventListener('input', () => {

            currentPage = 1;

            applyAll();
        });
    }


    // Tombol filter
    if (btnApply) {

        btnApply.addEventListener('click', function () {

            currentPage = 1;

            applyAll();
        });
    }


    // Reset filter
    if (btnReset) {

        btnReset.addEventListener('click', function () {

            if (filterRole) {
                filterRole.value = '';
            }

            if (filterStatus) {
                filterStatus.value = '';
            }

            currentPage = 1;

            applyAll();
        });
    }


    // Event pagination
    if (rowsPerPageSelect) {

        rowsPerPageSelect.addEventListener('change', function () {

            rowsPerPage =
                parseInt(this.value);

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

            const maxPage =
                Math.ceil(filteredRows.length / rowsPerPage);

            if (currentPage < maxPage) {

                currentPage++;

                renderPagination();
            }
        });
    }


    // Search dan filter tabel
    function applyAll() {

        const searchTerm =
            searchInput
            ? searchInput.value.toLowerCase().trim()
            : '';

        const roleVal =
            filterRole
            ? filterRole.value.toLowerCase().trim()
            : '';

        const statusVal =
            filterStatus
            ? filterStatus.value.toLowerCase().trim()
            : '';

        const rows =
            tableBody
            ? tableBody.querySelectorAll('tr[data-role]')
            : [];

        filteredRows = [];

        rows.forEach(row => {

            const text =
                row.textContent.toLowerCase();

            const role =
                (row.getAttribute('data-role') || '')
                .toLowerCase()
                .trim();

            const status =
                (row.getAttribute('data-status') || '')
                .toLowerCase()
                .trim();

            // Pencocokan filter
            const matchSearch =
                !searchTerm ||
                text.includes(searchTerm);

            const matchRole =
                !roleVal ||
                role === roleVal;

            const matchStatus =
                !statusVal ||
                status === statusVal;

            // Hasil filter
            if (
                matchSearch &&
                matchRole &&
                matchStatus
            ) {

                filteredRows.push(row);

            } else {

                row.style.display = 'none';
            }
        });

        renderPagination();
    }


    // Render pagination
    function renderPagination() {

        const total =
            filteredRows.length;

        const maxPage =
            Math.ceil(total / rowsPerPage) || 1;

        if (currentPage > maxPage) {
            currentPage = maxPage;
        }

        if (currentPage < 1) {
            currentPage = 1;
        }

        const startIndex =
            (currentPage - 1) * rowsPerPage;

        const endIndex =
            startIndex + rowsPerPage;

        // Sembunyikan semua row
        const allRows =
            tableBody
            ? tableBody.querySelectorAll('tr[data-role]')
            : [];

        allRows.forEach(r => {
            r.style.display = 'none';
        });

        // Tampilkan row halaman aktif
        filteredRows.forEach((row, index) => {

            if (
                index >= startIndex &&
                index < endIndex
            ) {

                row.style.display = '';

                // Update nomor urut
                const noCell = row.cells[1];

                if (noCell) {
                    noCell.textContent = index + 1;
                }

            } else {

                row.style.display = 'none';
            }
        });

        // Update informasi data
        if (rowCountEl) {

            const endDisplay =
                Math.min(endIndex, total);

            const startDisplay =
                total === 0
                ? 0
                : startIndex + 1;

            rowCountEl.textContent =
                `Menampilkan ${startDisplay}-${endDisplay} dari ${total} data`;
        }

        // Update tombol pagination
        if (currentPageNum) {
            currentPageNum.textContent = currentPage;
        }

        if (btnPrevPage) {
            btnPrevPage.disabled =
                (currentPage === 1);
        }

        if (btnNextPage) {
            btnNextPage.disabled =
                (currentPage === maxPage || total === 0);
        }
    }
    // Render awal
    applyAll();

});