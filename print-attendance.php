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
$mpdf = new \Mpdf\Mpdf([
    'margin_left' => 15,
    'margin_right' => 15,
    'margin_top' => 35,
    'margin_bottom' => 20,
    'margin_header' => 10,
    'margin_footer' => 10
]);

// Set document properties
$mpdf->SetTitle("Attendance List for " . $eventName);
$mpdf->SetAuthor("BukSU Events");

// Header and Footer
$mpdf->SetHTMLHeader('
    <div style="text-align: left; font-weight: bold; font-size: 14pt; color: #2c3e50;">
        <img src="images/black.png" height="40" style="vertical-align:middle; margin-right:10px;">
        Bukidnon State University
    </div>
    <div style="border-bottom: 2px solid #2980b9; margin-top: 5px;"></div>
', 'O');

$mpdf->SetHTMLFooter('
    <div style="border-top: 1px solid #2980b9; font-size:10pt; color:#888; text-align:right;">
        Page {PAGENO} of {nbpg}
    </div>
');

// Add content to the PDF
$html = '
<style>
    body { font-family: "Segoe UI", Arial, sans-serif; font-size: 12pt; color: #222; }
    h1 { text-align: center; margin-bottom: 10px; color: #2980b9; font-size: 22pt; }
    .subtitle { text-align: center; font-size: 13pt; color: #555; margin-bottom: 25px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #2980b9; padding: 7px 10px; }
    th { background: #2980b9; color: #fff; font-weight: bold; font-size: 12pt; }
    tr:nth-child(even) td { background: #f4f8fb; }
    tr:hover td { background: #eaf2fb; }
    .footer { text-align: right; font-size: 10pt; color: #888; margin-top: 20px; }
    .signature { margin-top: 40px; }
    .signature .label { font-size: 11pt; color: #555; }
    .signature .line { border-bottom: 1px solid #888; width: 250px; margin-top: 20px; }
</style>
<h1>Attendance List</h1>
<div class="subtitle">Event: <b>' . htmlspecialchars($eventName) . '</b></div>
';

if (!empty($attendees)) {
    $html .= '
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Roles</th>
                <th>Contact No</th>
                <th>Attendance Date & Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';
    foreach ($attendees as $index => $attendee) {
        $html .= '<tr>
            <td>' . ($index + 1) . '</td>
            <td>' . htmlspecialchars($attendee['firstname']) . '</td>
            <td>' . htmlspecialchars($attendee['lastname']) . '</td>
            <td>' . htmlspecialchars($attendee['roles']) . '</td>
            <td>' . htmlspecialchars($attendee['contact_no']) . '</td>
            <td>' . htmlspecialchars(date('M d, Y h:i A', strtotime($attendee['attendance_date']))) . '</td>
            <td>' . htmlspecialchars(ucfirst($attendee['attendance_status'])) . '</td>
        </tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p style="text-align:center; color:#c0392b; font-size:13pt;">No attendees recorded for this event.</p>';
}

// Signature section
$html .= '
<div class="signature">
    <div class="label">Prepared by:</div>
    <div class="line"></div>
    <div class="label" style="margin-top:5px;">Signature over printed name</div>
</div>
';

// Footer
$html .= '<div class="footer">Generated on: ' . date('F d, Y h:i A') . '</div>';

// Write the HTML to the PDF
$mpdf->WriteHTML($html);

// Output the PDF to the browser for download
$mpdf->Output("attendance_" . str_replace(' ', '_', $eventName) . ".pdf", 'D'); // 'D' for download
?>