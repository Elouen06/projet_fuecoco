document.addEventListener('DOMContentLoaded', function() {
    // Sélection des éléments HTML nécessaires
    const startDateInput = document.getElementById('start-date');
    const endDateInput = document.getElementById('end-date');
    const numGuestsInput = document.getElementById('num-guests');
    const totalPriceElement = document.getElementById('total-price');
    const reserveButton = document.getElementById('reserve-button');
    const pricePerNight = 100; // Prix par nuit

    let startDate = null; // Variable pour stocker la date de début sélectionnée
    let endDate = null; // Variable pour stocker la date de fin sélectionnée
    let currentMonth = new Date().getMonth(); // Mois courant
    let currentYear = new Date().getFullYear(); // Année courante
    let numGuests = parseInt(numGuestsInput.value); // Nombre de guests

    // Fonction pour générer le calendrier pour un mois et une année donnés
    function generateCalendar(month, year) {
        const calendar = document.getElementById('calendar');
        calendar.innerHTML = ''; // Réinitialise le contenu du calendrier

        const daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        let daysInMonth = new Date(year, month + 1, 0).getDate(); // Nombre de jours dans le mois
        const firstDayOfMonth = new Date(year, month, 1).getDay() || 7; // Premier jour du mois (1 = Lundi, 7 = Dimanche)
        const daysInPrevMonth = new Date(year, month, 0).getDate(); // Nombre de jours dans le mois précédent

        let table = '<table>';
        table += '<thead><tr>';
        for (let day of daysOfWeek) {
            table += `<th>${day}</th>`; // Ajoute les en-têtes de jour de la semaine
        }
        table += '</tr></thead>';
        table += '<tbody><tr>';

        const today = new Date().toISOString().split('T')[0]; // Date d'aujourd'hui au format ISO

        // Ajoute les jours du mois précédent en gris
        let prevMonthDay = daysInPrevMonth - (firstDayOfMonth - 2); // Calcule le premier jour à afficher du mois précédent
        for (let i = 1; i < firstDayOfMonth; i++) {
            const prevMonthDate = `${year}-${String(month).padStart(2, '0')}-${String(prevMonthDay).padStart(2, '0')}`;
            table += `<td class="prev-month available" data-date="${prevMonthDate}">${prevMonthDay}</td>`;
            prevMonthDay++;
        }

        // Ajoute les jours du mois courant
        for (let day = 1; day <= daysInMonth; day++) {
            const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isPastDate = date < today; // Vérifie si la date est passée
            const isBlocked = blockedDates.includes(date); // Vérifie si la date est bloquée
            const isReserved = reservedDates.includes(date); // Vérifie si la date est réservée

            let className = isPastDate ? 'disabled' : isReserved ? 'reserved' : isBlocked ? 'blocked' : 'available'; // Définit la classe CSS selon le statut de la date
            let badge = isReserved ? '<div class="reservation-badge">Réservé</div>' : isBlocked ? '<div class="blocked-badge">Bloqué</div>' : ''; // Ajoute un badge pour les dates réservées ou bloquées

            table += `<td class="${className}" data-date="${date}">${day}${badge}</td>`;

            if ((day + firstDayOfMonth - 1) % 7 === 0) {
                table += '</tr><tr>'; // Commence une nouvelle ligne après chaque semaine
            }
        }

        // Ajoute les jours du mois suivant en gris
        let daysInNextMonth = 1;
        while ((daysInMonth + firstDayOfMonth - 1) % 7 !== 6) {
            const nextMonthDate = `${year}-${String(month + 2).padStart(2, '0')}-${String(daysInNextMonth).padStart(2, '0')}`;
            table += `<td class="next-month available" data-date="${nextMonthDate}">${daysInNextMonth}</td>`;
            daysInMonth++;
            daysInNextMonth++;
        }

        // S'assure que le tableau a exactement 6 lignes
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
        calendar.innerHTML = table; // Affiche le tableau dans le calendrier

        // Style pour les dates du mois suivant et du mois précédent
        const style = document.createElement('style');
        style.innerHTML = `
            .prev-month,
            .next-month {
                color: gray;
            }
        `;
        document.head.appendChild(style);

        // Ajoute un événement pour les jours disponibles
        document.querySelectorAll('.calendar td.available').forEach(cell => {
            cell.addEventListener('click', function() {
                const selectedDate = this.getAttribute('data-date');

                if (selectedDate === startDate) {
                    // Désélectionne la date de début
                    startDate = null;
                    startDateInput.value = '';
                    document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                    checkDatesAndCalculate();
                } else if (selectedDate === endDate) {
                    // Désélectionne la date de fin
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
                    // Sélectionne la date de début
                    startDate = selectedDate;
                    endDate = null;
                    startDateInput.value = startDate;
                    endDateInput.value = '';
                    document.querySelectorAll('.calendar td').forEach(cell => cell.classList.remove('selected'));
                    this.classList.add('selected');
                    checkDatesAndCalculate();
                } else if (!endDate && selectedDate > startDate) {
                    // Sélectionne la date de fin
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
                    // Réajuste la date de début si la sélection est inversée
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

        // Empêche la sélection des dates passées
        document.querySelectorAll('.calendar td.disabled').forEach(cell => {
            cell.addEventListener('click', function() {
                alert("Cette date est déjà passée.");
            });
        });
    }

    // Vérifie si la période contient des dates bloquées ou réservées
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

    // Vérifie les dates sélectionnées et calcule le prix total
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

    // Calcule le prix total de la réservation
    function calculateTotalPrice() {
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const nights = (end - start) / (1000 * 60 * 60 * 24); // Calcule le nombre de nuits
            const totalPrice = nights * pricePerNight * numGuests; // Calcule le prix total
            totalPriceElement.value = `Prix total : ${totalPrice}€`;
        } else {
            totalPriceElement.value = 'Prix total : 0€';
        }
    }

    // Met à jour l'affichage du calendrier
    function updateCalendar() {
        const monthNames = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        document.getElementById('current-month').innerText = `${monthNames[currentMonth]} ${currentYear}`;
        generateCalendar(currentMonth, currentYear);
    }

    // Gestionnaire d'événement pour le bouton du mois précédent
    document.getElementById('prev-month').addEventListener('click', function() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        updateCalendar();
    });

    // Gestionnaire d'événement pour le bouton du mois suivant
    document.getElementById('next-month').addEventListener('click', function() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        updateCalendar();
    });

    // Mise à jour du nombre de guests et recalcul du prix total
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

    // Initialise le calendrier
    updateCalendar();
});
