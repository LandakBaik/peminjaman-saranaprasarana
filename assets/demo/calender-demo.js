document.addEventListener('DOMContentLoaded', () => {

    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    const calendarDates = document.getElementById('calendarDates');

    const modal = document.getElementById('loanModal');
    const closeModal = document.querySelector('.close');

    const monthNames = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    const currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();

    // Dropdown bulan
    monthNames.forEach((month, index) => {
        const option = document.createElement('option');
        option.value = index;
        option.textContent = month;
        monthSelect.appendChild(option);
    });

    // Dropdown tahun
    for (let year = currentYear - 10; year <= currentYear + 10; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    }

    // Render kalender
    function renderCalendar(month, year) {

        calendarDates.innerHTML = '';

        monthSelect.value = month;
        yearSelect.value = year;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();

        // Tanggal bulan sebelumnya
        for (let i = firstDay; i > 0; i--) {
            const day = document.createElement('div');
            day.className = 'day inactive';

            day.innerHTML = `
                <div class="date-number">
                    ${daysInPrevMonth - i + 1}
                </div>
            `;

            calendarDates.appendChild(day);
        }

        // Tanggal bulan aktif
        for (let date = 1; date <= daysInMonth; date++) {

            const day = document.createElement('div');
            day.className = 'day';

            // Hari ini
            if (
                date === currentDate.getDate() &&
                month === currentDate.getMonth() &&
                year === currentDate.getFullYear()
            ) {
                day.classList.add('today');
            }

            day.innerHTML = `
                <div class="date-number">${date}</div>
            `;

            // Pilih tanggal
            day.addEventListener('click', () => {

                document
                    .querySelectorAll('.day')
                    .forEach(d => d.classList.remove('selected'));

                day.classList.add('selected');

                const yyyy = year;
                const mm = String(month + 1).padStart(2, '0');
                const dd = String(date).padStart(2, '0');

                const dateStr = `${yyyy}-${mm}-${dd}`;

                const inputMulai = document.getElementById('waktu_mulai');
                const inputSelesai = document.getElementById('waktu_selesai');

                // Isi waktu otomatis
                if (inputMulai && inputSelesai) {

                    inputMulai.value = `${dateStr}T08:00`;
                    inputSelesai.value = `${dateStr}T16:00`;

                    inputMulai.dispatchEvent(
                        new Event('change', { bubbles: true })
                    );

                    inputSelesai.dispatchEvent(
                        new Event('change', { bubbles: true })
                    );
                }

                // Tampilkan modal
                if (modal) {
                    modal.style.display = 'flex';
                }
            });

            calendarDates.appendChild(day);
        }

        // Isi sisa kotak
        const totalCells = firstDay + daysInMonth;
        const remaining = 42 - totalCells;

        for (let i = 1; i <= remaining; i++) {

            const day = document.createElement('div');

            day.className = 'day inactive';

            day.innerHTML = `
                <div class="date-number">${i}</div>
            `;

            calendarDates.appendChild(day);
        }
    }

    // Prev month
    document.getElementById('prevMonth')
        .addEventListener('click', () => {

            currentMonth--;

            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }

            renderCalendar(currentMonth, currentYear);
        });

    // Next month
    document.getElementById('nextMonth')
        .addEventListener('click', () => {

            currentMonth++;

            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }

            renderCalendar(currentMonth, currentYear);
        });

    // Ganti bulan
    monthSelect.addEventListener('change', () => {

        currentMonth = parseInt(monthSelect.value);

        renderCalendar(currentMonth, currentYear);
    });

    // Ganti tahun
    yearSelect.addEventListener('change', () => {

        currentYear = parseInt(yearSelect.value);

        renderCalendar(currentMonth, currentYear);
    });

    // Tutup modal
    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Klik luar modal
    window.addEventListener('click', (e) => {

        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    renderCalendar(currentMonth, currentYear);
});