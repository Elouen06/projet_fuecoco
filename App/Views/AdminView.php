<?php
namespace Views;

class AdminView {
    public function render($calendar, $blockedDates, $reservedDates, $reservations) {
        $csrfToken = generate_csrf_token();
        echo '<main>
            <h1>Admin Dashboard</h1>
            <h2>Block Dates</h2>
            <div class="calendar-block">
                <div class="calendar-navigation">
                    <button id="prev-month">&lt;</button>
                    <span id="current-month"></span>
                    <button id="next-month">&gt;</button>
                </div>
                <div class="calendar" id="calendar"></div>
                <form id="add-block-form" method="post" action="?action=admin_add_blocked_dates">
                    <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                    <input type="hidden" name="blocked_dates" id="add-blocked-dates-input">
                    <button type="submit">Add Selected Dates</button>
                </form>
                <form id="remove-block-form" method="post" action="?action=admin_remove_blocked_dates">
                    <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                    <input type="hidden" name="blocked_dates" id="remove-blocked-dates-input">
                    <button type="submit">Remove Selected Dates</button>
                </form>
            </div>
            <h2>Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
                foreach ($reservations as $reservation) {
                    echo '<tr>
                        <td>' . htmlspecialchars($reservation['start_date']) . '</td>
                        <td>' . htmlspecialchars($reservation['end_date']) . '</td>
                        <td>
                            <form method="post" action="?action=admin_cancel_reservation&id=' . htmlspecialchars($reservation['id']) . '">
                                <input type="hidden" name="csrf_token" value="' . htmlspecialchars($csrfToken) . '">
                                <button type="submit">Cancel</button>
                            </form>
                        </td>
                    </tr>';
                }
                echo '</tbody>
            </table>
        </main>
        <script>
            const blockedDates = ' . json_encode($blockedDates) . ';
            const reservedDates = ' . json_encode($reservedDates) . ';
        </script>
        <script src="Assets/js/admin-calendar.js"></script>';
    }
}
