
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

// Isi dropdown bulan
monthNames.forEach((month, index) => {
    const option = document.createElement('option');
    option.value = index;
    option.textContent = month;
    monthSelect.appendChild(option);
});

// Isi dropdown tahun
for (let year = currentYear - 10; year <= currentYear + 10; year++) {
    const option = document.createElement('option');
    option.value = year;
    option.textContent = year;
    yearSelect.appendChild(option);
}

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
        day.innerHTML = `<div class="date-number">${daysInPrevMonth - i + 1}</div>`;
        calendarDates.appendChild(day);
    }

    // Tanggal bulan sekarang
    for (let date = 1; date <= daysInMonth; date++) {
        const day = document.createElement('div');
        day.className = 'day';

        if (
            date === currentDate.getDate() &&
            month === currentDate.getMonth() &&
            year === currentDate.getFullYear()
        ) {
            day.classList.add('today');
        }

        day.innerHTML = `<div class="date-number">${date}</div>`;

        day.addEventListener('click', () => {
            document.querySelectorAll('.day').forEach(d => d.classList.remove('selected'));
            day.classList.add('selected');

            // tanggal yang dipilih dapat disimpan di sini bila diperlukan

            const modal = document.getElementById('loanModal');
            modal.style.display = 'flex';
        });

        calendarDates.appendChild(day);
    }

    // Isi sisa kotak agar genap 42
    const totalCells = firstDay + daysInMonth;
    const remaining = 42 - totalCells;

    for (let i = 1; i <= remaining; i++) {
        const day = document.createElement('div');
        day.className = 'day inactive';
        day.innerHTML = `<div class="date-number">${i}</div>`;
        calendarDates.appendChild(day);
    }
}

// Tombol prev / next

document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth--;

    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }

    renderCalendar(currentMonth, currentYear);
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth++;

    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }

    renderCalendar(currentMonth, currentYear);
});

// Jika dropdown diubah
monthSelect.addEventListener('change', () => {
    currentMonth = parseInt(monthSelect.value);
    renderCalendar(currentMonth, currentYear);
});

yearSelect.addEventListener('change', () => {
    currentYear = parseInt(yearSelect.value);
    renderCalendar(currentMonth, currentYear);
});

// Tutup modal
closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === modal) {
        modal.style.display = 'none';
    }
});

renderCalendar(currentMonth, currentYear);
});