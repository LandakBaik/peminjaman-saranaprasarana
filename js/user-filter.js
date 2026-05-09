document.addEventListener('DOMContentLoaded', function () {

    // DOM
    const searchInput = document.getElementById('searchUser');
    const tableBody = document.getElementById('userBody');

    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatusUser');

    const btnApply = document.getElementById('applyUserFilter');
    const btnReset = document.getElementById('resetUserFilter');

    const rowCount = document.getElementById('rowCountUser');

    const selectAll = document.getElementById('selectAllUser');

    const getRowCheckboxes = () =>
        tableBody.querySelectorAll('.row-checkbox');


    // Checkbox
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


    // Search realtime
    searchInput.addEventListener('input', applyAll);


    // Button filter
    btnApply.addEventListener('click', applyAll);

    btnReset.addEventListener('click', function () {

        filterRole.value = '';
        filterStatus.value = '';

        applyAll();

    });


    // Core filter
    function applyAll() {

        const searchTerm = searchInput.value
            .toLowerCase()
            .trim();

        const roleVal = filterRole.value;
        const statusVal = filterStatus.value;

        const rows = tableBody.querySelectorAll('tr');

        let visible = 0;

        rows.forEach(row => {

            const text = row.textContent.toLowerCase();

            const role = row.getAttribute('data-role');

            const status = row.getAttribute('data-status');

            const matchSearch =
                !searchTerm ||
                text.includes(searchTerm);

            const matchRole =
                !roleVal ||
                role === roleVal;

            const matchStatus =
                !statusVal ||
                status === statusVal;

            if (
                matchSearch &&
                matchRole &&
                matchStatus
            ) {

                row.style.display = '';
                visible++;

            } else {

                row.style.display = 'none';

            }

        });

        const total = rows.length;

        rowCount.textContent =
            `${visible} of ${total}`;

    }


    // Init
    applyAll();

});