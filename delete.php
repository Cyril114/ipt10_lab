<?php
require_once 'db_connect.php';
$id = trim($_GET['id'] ?? '');
if ($id === '') { die('Invalid student ID'); }


$s = $conn->prepare('SELECT first_name, last_name FROM students WHERE id = ?');
$s->bind_param('s', $id);
$s->execute();
$r = $s->get_result()->fetch_assoc();
$s->close();
if (!$r) {
    echo '<p>Student not found.</p>';
    $conn->close(); exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $d = $conn->prepare('DELETE FROM students WHERE id = ?');
    $d->bind_param('s', $id);
    $d->execute();

    if ($d->affected_rows === 1) {
        echo '<p>Student deleted successfully.</p>';
    } else {
        echo '<p>Failed to delete student.</p>';
    }
    $d->close();
    echo '<p><a href="index.php">Back to all students</a></p>';
    $conn->close();
} else {
   
    $name = htmlspecialchars($r['first_name'] . ' ' . $r['last_name']);
    $safeId = htmlspecialchars($id);
    echo '<h2>Delete Student</h2>'
       . '<p>Are you sure you want to delete <strong>' . $name . '</strong>?</p>'
       . '<form method="post" action="delete.php?id=' . urlencode($id) . '">'
       . '<input type="hidden" name="id" value="' . $safeId . '">'
       . '<button type="submit">Yes, delete</button> '
       . '<a href="index.php">Cancel</a>'
       . '</form>';
    $conn->close();
}
?>
