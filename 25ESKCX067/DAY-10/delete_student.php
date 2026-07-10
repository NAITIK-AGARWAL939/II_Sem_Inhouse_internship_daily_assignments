<?php
require_once "config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Fetch record first so we can report the name and clean up the photo file
    $stmt = $conn->prepare("SELECT name, photo FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if ($student) {
        $del = $conn->prepare("DELETE FROM students WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();

        if (!empty($student['photo']) && file_exists(__DIR__ . "/uploads/" . $student['photo'])) {
            unlink(__DIR__ . "/uploads/" . $student['photo']);
        }

        header("Location: students.php?msg=deleted&name=" . urlencode($student['name']));
        exit;
    }
}

header("Location: students.php");
exit;
