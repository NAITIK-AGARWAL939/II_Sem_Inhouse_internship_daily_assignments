<?php
require_once "config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
if ($id <= 0) { header("Location: students.php"); exit; }

$errors = [];

// Fetch current record
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    header("Location: students.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $branch = trim($_POST['branch'] ?? '');
    $cgpa   = trim($_POST['cgpa'] ?? '');
    $status = trim($_POST['status'] ?? 'Active');

    if ($name === '') $errors[] = "Name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if ($branch === '') $errors[] = "Branch is required.";
    if (!is_numeric($cgpa) || $cgpa < 0 || $cgpa > 10) $errors[] = "CGPA must be between 0.0 and 10.0.";
    if (!in_array($status, ['Active', 'Inactive'])) $errors[] = "Invalid status.";

    $photo_filename = $student['photo']; // keep existing photo by default

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 2 * 1024 * 1024;

        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            $errors[] = "Photo must be JPG, PNG, or WEBP.";
        } elseif ($_FILES['photo']['size'] > $max_size) {
            $errors[] = "Photo must be under 2MB.";
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $new_filename = "student_" . uniqid() . "." . $ext;
            $upload_path = __DIR__ . "/uploads/" . $new_filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                // remove old photo if it exists
                if (!empty($student['photo']) && file_exists(__DIR__ . "/uploads/" . $student['photo'])) {
                    unlink(__DIR__ . "/uploads/" . $student['photo']);
                }
                $photo_filename = $new_filename;
            } else {
                $errors[] = "Failed to upload new photo.";
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, branch=?, cgpa=?, photo=?, status=? WHERE id=?");
        $stmt->bind_param("sssdssi", $name, $email, $branch, $cgpa, $photo_filename, $status, $id);
        $stmt->execute();
        header("Location: students.php?msg=updated");
        exit;
    } else {
        // keep POSTed values for redisplay
        $student = array_merge($student, $_POST);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 600px;">
    <h3 class="mb-4">Edit Student</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm" novalidate>
        <input type="hidden" name="id" value="<?= $id ?>">

        <?php if (!empty($student['photo'])): ?>
            <div class="mb-3 text-center">
                <img src="uploads/<?= htmlspecialchars($student['photo']) ?>" style="width:80px;height:80px;object-fit:cover;border-radius:50%;">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="name" class="form-control" required
                   value="<?= htmlspecialchars($student['name']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($student['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Branch / Department</label>
            <input type="text" name="branch" class="form-control" required
                   value="<?= htmlspecialchars($student['branch']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">CGPA (0.0 - 10.0)</label>
            <input type="number" step="0.1" min="0" max="10" name="cgpa" class="form-control" required
                   value="<?= htmlspecialchars($student['cgpa']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= $student['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $student['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Replace Profile Photo (optional)</label>
            <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>
        <small class="text-muted mb-3 d-block">Last updated: <?= date("d M Y, H:i", strtotime($student['updated_at'])) ?></small>
        <div class="d-flex justify-content-between">
            <a href="students.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Student</button>
        </div>
    </form>
</div>
</body>
</html>
