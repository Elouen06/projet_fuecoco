document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded event triggered');

    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const numGuestsInput = document.getElementById('num-guests');
    const totalPriceElement = document.getElementById('total-price');
    const pricePerNight = 100; // Prix par nuit

    let startDate = null;
    let endDate = null;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let numGuests = parseInt(numGuestsInput.value); // Nombre de voyageurs

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
            table += `<td class="available" data-date="${date}">${day}</td>`;
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

        document.querySelectorAll('.calendar td.available').forEach(cell => {
            cell.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');

                if (!startDate || endDate) {
                    startDate = selectedDate;
                    endDate = null;
                    startDateInput.value = startDate;
                    endDateInput.value = '';
                    totalPriceElement.innerText = ''; // Réinitialiser le prix total
                    document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                    this.classList.add('selected');
                } else if (!endDate && selectedDate >= startDate) {
                    endDate = selectedDate;
                    endDateInput.value = endDate;
                    document.querySelectorAll('.calendar td').forEach(cell => {
                        const cellDate = cell.getAttribute('data-date');
                        if (cellDate >= startDate && cellDate <= endDate) {
                            cell.classList.add('selected');
                        }
                    });
                    calculateTotalPrice(); // Calculer le prix total
                }
            });
        });
    }

    function calculateTotalPrice() {
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const nights = (end - start) / (1000 * 60 * 60 * 24);
            const totalPrice = nights * pricePerNight * numGuests; // Inclure le nombre de voyageurs dans le calcul
            totalPriceElement.innerText = `Prix total : ${totalPrice}€`;
            document.getElementById('total-price-input').value = totalPrice; // Mise à jour du champ de prix total dans le formulaire
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
        calculateTotalPrice(); // Recalculer le prix total lors de la modification du nombre de voyageurs
    });

    updateCalendar();
});
