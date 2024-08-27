document.addEventListener('DOMContentLoaded', function() {
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let selectedDatesToAdd = [];
    let selectedDatesToRemove = [];

    function generateCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay() || 7;
        const daysInPrevMonth = new Date(year, month, 0).getDate(); // Get the number of days in the previous month

        let table = '<table>';
        table += '<thead><tr>';
        for (let day of daysOfWeek) {
            table += `<th>${day}</th>`;
        }
        table += '</tr></thead>';
        table += '<tbody><tr>';

        // Add days from the previous month in gray
        let prevMonthDay = daysInPrevMonth - (firstDayOfMonth - 2); // Calculate the starting day for the previous month
        for (let i = 1; i < firstDayOfMonth; i++) {
            const prevMonthDate = `${year}-${String(month).padStart(2, '0')}-${String(prevMonthDay).padStart(2, '0')}`;
            table += `<td class="prev-month available" data-date="${prevMonthDate}">${prevMonthDay}</td>`;
            prevMonthDay++;
        }

        // Add days of the current month
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

        // Add days from the next month in gray
        let daysInNextMonth = 1;
        while ((daysInMonth + firstDayOfMonth - 1) % 7 !== 6) {
            const nextMonthDate = `${year}-${String(month + 2).padStart(2, '0')}-${String(daysInNextMonth).padStart(2, '0')}`;
            table += `<td class="next-month available" data-date="${nextMonthDate}">${daysInNextMonth}</td>`;
            daysInMonth++;
            daysInNextMonth++;
        }

        // Ensure the table has exactly 6 rows
        while ((daysInMonth + firstDayOfMonth - 1) < 42) {
            const nextMonthDate = `${year}-${String(month + 2).padStart(2, '0')}-${String(daysInNextMonth).padStart(2, '0')}`;
            table += `<td class="next-month available" data-date="${nextMonthDate}">${daysInNextMonth}</td>`;
            daysInMonth++;
            daysInNextMonth++;
            if ((daysInMonth + firstDayOfMonth - 1) % 7 === 0) {
                table += '</tr><tr>';
            }
        }

        table += '</tr></tbody></table>';
        calendar.innerHTML = table;

        // Set style for next month and previous month dates
        const style = document.createElement('style');
        style.innerHTML = `
            .prev-month,
            .next-month {
                color: gray;
            }
        `;
        document.head.appendChild(style);

        document.querySelectorAll('.calendar td.available, .calendar td.blocked').forEach(cell => {
            cell.addEventListener('click', function() {
                const date = this.getAttribute('data-date');
                if (this.classList.contains('blocked')) {
                    this.classList.toggle('selected-to-remove');
                    if (this.classList.contains('selected-to-remove')) {
                        selectedDatesToRemove.push(date);
                    } else {
                        selectedDatesToRemove = selectedDatesToRemove.filter(d => d !== date);
                    }
                } else {
                    this.classList.toggle('selected-to-add');
                    if (this.classList.contains('selected-to-add')) {
                        selectedDatesToAdd.push(date);
                    } else {
                        selectedDatesToAdd = selectedDatesToAdd.filter(d => d !== date);
                    }
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

    document.getElementById('add-block-form').addEventListener('submit', function(event) {
        const blockedDatesInput = document.getElementById('add-blocked-dates-input');
        blockedDatesInput.value = JSON.stringify(selectedDatesToAdd.filter(date => date !== ''));
    });

    document.getElementById('remove-block-form').addEventListener('submit', function(event) {
        const blockedDatesInput = document.getElementById('remove-blocked-dates-input');
        blockedDatesInput.value = JSON.stringify(selectedDatesToRemove.filter(date => date !== ''));
    });

    updateCalendar();
});
