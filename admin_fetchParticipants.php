<?php
include 'admin_controllerEvent.php';

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

$stmt = $conn->prepare("SELECT participant_fullname, participant_email, signup_date FROM event_participants WHERE event_id = ? ORDER BY signup_date ASC");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p>No participants yet.</p>";
    exit();
}
?>

<table class="table table-striped align-middle mb-0 text-center">
    <thead class="table-success">
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Date & Time</th>
        </tr>
    </thead>
    <tbody>
        <?php $counter = 1; while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $counter++ ?></td>
            <td><?= htmlspecialchars($row['participant_fullname']) ?></td>
            <td><?= htmlspecialchars($row['participant_email']) ?></td>
            <td><?= date('M d, Y h:i A', strtotime($row['signup_date'])) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
