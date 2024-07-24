document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const numGuestsInput = document.getElementById('num-guests');
    const totalPriceElement = document.getElementById('total-price');
    const reserveButton = document.getElementById('reserve-button');
    const pricePerNight = 100; // Prix par nuit

    let startDate = null;
    let endDate = null;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let numGuests = parseInt(numGuestsInput.value);

    function generateCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = '';

        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        let daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDayOfMonth = new Date(year, month, 1).getDay() || 7; // Get the weekday of the first day of the month (1 = Monday, 7 = Sunday)
        const daysInPrevMonth = new Date(year, month, 0).getDate(); // Get the number of days in the previous month

        let table = '<table>';
        table += '<thead><tr>';
        for (let day of daysOfWeek) {
            table += `<th>${day}</th>`;
        }
        table += '</tr></thead>';
        table += '<tbody><tr>';

        const today = new Date().toISOString().split('T')[0];
        
        // Add days from the previous month in gray
        let prevMonthDay = daysInPrevMonth - (firstDayOfMonth - 2); // Calculate the starting day for the previous month
        for (let i = 1; i < firstDayOfMonth; i++) {
            const prevMonthDate = `${year}-${String(month).padStart(2, '0')}-${String(prevMonthDay).padStart(2, '0')}`;
            table += `<td class="prev-month" data-date="${prevMonthDate}">${prevMonthDay}</td>`;
            prevMonthDay++;
        }

        // Add days of the current month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isPastDate = date < today;
            const isBlocked = blockedDates.includes(date);
            const isReserved = reservedDates.includes(date);

            let className = isPastDate ? 'disabled' : isReserved ? 'reserved' : isBlocked ? 'blocked' : 'available';
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
            table += `<td class="next-month" data-date="${nextMonthDate}">${daysInNextMonth}</td>`;
            daysInMonth++;
            daysInNextMonth++;
        }

        // Ensure the table has exactly 6 rows
        while ((daysInMonth + firstDayOfMonth - 1) < 42) {
            const nextMonthDate = `${year}-${String(month + 2).padStart(2, '0')}-${String(daysInNextMonth).padStart(2, '0')}`;
            table += `<td class="next-month" data-date="${nextMonthDate}">${daysInNextMonth}</td>`;
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

        document.querySelectorAll('.calendar td.available').forEach(cell => {
            cell.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');

                if (selectedDate === startDate) {
                    // Désélectionner la date de début
                    startDate = null;
                    startDateInput.value = '';
                    document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                    checkDatesAndCalculate();
                } else if (selectedDate === endDate) {
                    // Désélectionner la date de fin
                    endDate = null;
                    endDateInput.value = '';
                    document.querySelectorAll('.calendar td').forEach(cell => {
                        const cellDate = cell.getAttribute('data-date');
                        if (cellDate >= startDate && cellDate <= endDate) {
                            cell.classList.remove('selected');
                        }
                    });
                    checkDatesAndCalculate();
                } else if (!startDate || endDate) {
                    startDate = selectedDate;
                    endDate = null;
                    startDateInput.value = startDate;
                    endDateInput.value = '';
                    document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                    this.classList.add('selected');
                    checkDatesAndCalculate();
                } else if (!endDate && selectedDate > startDate) {
                    if (!containsBlockedOrReservedDates(startDate, selectedDate)) {
                        endDate = selectedDate;
                        endDateInput.value = endDate;
                        document.querySelectorAll('.calendar td').forEach(cell => {
                            const cellDate = cell.getAttribute('data-date');
                            if (cellDate >= startDate && cellDate <= endDate) {
                                cell.classList.add('selected');
                            }
                        });
                        checkDatesAndCalculate();
                    } else {
                        alert('La période sélectionnée contient des dates bloquées ou réservées.');
                        reserveButton.disabled = true;
                    }
                } else if (!endDate && selectedDate < startDate) {
                    if (!containsBlockedOrReservedDates(selectedDate, startDate)) {
                        startDate = selectedDate;
                        endDate = null;
                        startDateInput.value = startDate;
                        endDateInput.value = '';
                        document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                        this.classList.add('selected');
                        checkDatesAndCalculate();
                    } else {
                        alert('La période sélectionnée contient des dates bloquées ou réservées.');
                        reserveButton.disabled = true;
                    }
                }
            });
        });

        document.querySelectorAll('.calendar td.disabled').forEach(cell => {
            cell.addEventListener('click', function() {
                alert("Cette date est déjà passée.");
            });
        });
    }

    function containsBlockedOrReservedDates(startDate, endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        for (let d = new Date(start); d <= end; d.setDate(d.getDate() + 1)) {
            const dateStr = d.toISOString().split('T')[0];
            if (blockedDates.includes(dateStr) || reservedDates.includes(dateStr)) {
                return true;
            }
        }
        return false;
    }

    function checkDatesAndCalculate() {
        if (startDate && endDate) {
            if (containsBlockedOrReservedDates(startDate, endDate)) {
                alert('La période sélectionnée contient des dates bloquées ou réservées.');
                reserveButton.disabled = true;
            } else {
                calculateTotalPrice();
                reserveButton.disabled = false;
            }
        } else {
            calculateTotalPrice();
            reserveButton.disabled = true;
        }
    }

    function calculateTotalPrice() {
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const nights = (end - start) / (1000 * 60 * 60 * 24);
            const totalPrice = nights * pricePerNight * numGuests;
            totalPriceElement.value = `Prix total : ${totalPrice}€`;
        } else {
            totalPriceElement.value = 'Prix total : 0€';
        }
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

    numGuestsInput.addEventListener('change', function() {
        let value = parseInt(this.value);
        if (isNaN(value) || value < 1) {
            value = 1;
        } else if (value > 4) {
            value = 4;
        }
        numGuests = value;
        this.value = value;
        checkDatesAndCalculate();
    });

    updateCalendar();
});
