<?php
// filepath: e:\xampp\htdocs\BukSU-Events\generate_attendance_pdf.php

require_once __DIR__ . '/vendor/autoload.php';
include 'db.php';

// Check if event_id is provided
if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    die("Error: Event ID is missing or invalid.");
}

$event_id = $_GET['event_id'];

// Fetch event details for the title
$stmtEvent = $pdo->prepare("SELECT event_name FROM events WHERE event_id = ?");
$stmtEvent->execute([$event_id]);
$event = $stmtEvent->fetch(PDO::FETCH_ASSOC);
$eventName = $event ? $event['event_name'] : 'Unknown Event';

// Fetch attendees for the selected event with user details
$stmtAttendees = $pdo->prepare("
    SELECT u.firstname, u.lastname, u.email, u.contact_no, a.attendance_date, a.attendance_status, u.roles
    FROM attendees a
    JOIN users u ON a.user_id = u.user_id
    WHERE a.event_id = ?
    ORDER BY u.lastname ASC, u.firstname ASC
");
$stmtAttendees->execute([$event_id]);
$attendees = $stmtAttendees->fetchAll(PDO::FETCH_ASSOC);

// Create a new MPDF instance
$mpdf = new \Mpdf\Mpdf();

// Set document properties
$mpdf->SetTitle("Attendance List for " . $eventName);
$mpdf->SetAuthor("BukSU Events");

// Add content to the PDF
$html = '<h1>Attendance List for: ' . htmlspecialchars($eventName) . '</h1>';

if (!empty($attendees)) {
    $html .= '<table style="width:100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">#</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">First Name</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Last Name</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Email</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Roles</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Contact No</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Attendance Date & Time</th>
                        <th style="border: 1px solid #000; padding: 8px; text-align: left;">Status</th>
                    </tr>
                </thead>
                <p>_____________</p>
                <tbody>';
    foreach ($attendees as $index => $attendee) {
        $html .= '<tr>
                    <td style="border: 1px solid #000; padding: 8px;">' . ($index + 1) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['firstname']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['lastname']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['email']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['roles']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['contact_no']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($attendee['attendance_date']) . '</td>
                    <td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars(ucfirst($attendee['attendance_status'])) . '</td>
                </tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>No attendees recorded for this event.</p>';
}

// Write the HTML to the PDF
$mpdf->WriteHTML($html);

// Output the PDF to the browser for download
$mpdf->Output("attendance_" . str_replace(' ', '_', $eventName) . ".pdf", 'D'); // 'D' for download
?>