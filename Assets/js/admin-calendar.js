document.addEventListener('DOMContentLoaded', function() {
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDates = [];

    function generateCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay() || 7;

        let table = '<table>';
        table += '<thead><tr>';
        for (let day of daysOfWeek) {
            table += `<th>${day}</th>`;
        }
        table += '</tr></thead>';
        table += '<tbody><tr>';

        for (let i = 1; i < firstDayOfMonth; i++) {
            table += '<td></td>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isBlocked = blockedDates.includes(date);
            const isReserved = reservedDates.includes(date);

            let className = isBlocked ? 'blocked' : isReserved ? 'reserved' : 'available';
            let badge = isReserved ? '<div class="reservation-badge">Réservé</div>' : isBlocked ? '<div class="blocked-badge">Bloqué</div>' : '';

            table += `<td class="${className}" data-date="${date}">${day}${badge}</td>`;

            if ((day + firstDayOfMonth - 1) % 7 === 0) {
                table += '</tr><tr>';
            }
        }

        while ((daysInMonth + firstDayOfMonth - 1) % 7 !== 6) {
            table += '<td></td>';
            daysInMonth++;
        }

        table += '</tr></tbody></table>';
        calendar.innerHTML = table;

        document.querySelectorAll('.calendar td.available, .calendar td.blocked').forEach(cell => {
            cell.addEventListener('click', function() {
                const date = this.getAttribute('data-date');
                if (this.classList.contains('blocked')) {
                    this.classList.remove('blocked');
                    blockedDates = blockedDates.filter(d => d !== date);
                    selectedDates = selectedDates.filter(d => d !== date);
                } else {
                    this.classList.add('blocked');
                    blockedDates.push(date);
                    selectedDates.push(date);
                }
            });
        });
    }

    function updateCalendar() {
        const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        document.getElementById('current-month').innerText = `${monthNames[currentMonth]} ${currentYear}`;
        generateCalendar(currentMonth, currentYear);
    }

    document.getElementById('prev-month').addEventListener('click', function() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        updateCalendar();
    });

    document.getElementById('next-month').addEventListener('click', function() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        updateCalendar();
    });

    document.getElementById('block-form').addEventListener('submit', function(event) {
        const blockedDatesInput = document.getElementById('blocked-dates-input');
        blockedDatesInput.value = JSON.stringify(selectedDates);
    });

    updateCalendar();
});
