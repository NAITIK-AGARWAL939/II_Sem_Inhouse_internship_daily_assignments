<?php
require_once "config.php";

$errors = [];

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

    
    $photo_filename = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['photo']['type'], $allowed_types)) {
            $errors[] = "Photo must be JPG, PNG, or WEBP.";
        } elseif ($_FILES['photo']['size'] > $max_size) {
            $errors[] = "Photo must be under 2MB.";
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo_filename = "student_" . uniqid() . "." . $ext;
            $upload_path = __DIR__ . "/uploads/" . $photo_filename;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $errors[] = "Failed to upload photo.";
                $photo_filename = null;
            }
        }
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO students (name, email, branch, cgpa, photo, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssdss", $name, $email, $branch, $cgpa, $photo_filename, $status);
        $stmt->execute();
        header("Location: students.php?msg=added");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Student</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width: 600px;">
    <h3 class="mb-4">Add New Student</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm" novalidate>
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="name" class="form-control" required
                   value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Branch / Department</label>
            <input type="text" name="branch" class="form-control" required
                   value="<?= htmlspecialchars($_POST['branch'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">CGPA (0.0 - 10.0)</label>
            <input type="number" step="0.1" min="0" max="10" name="cgpa" class="form-control" required
                   value="<?= htmlspecialchars($_POST['cgpa'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="Active" <?= (($_POST['status'] ?? '') === 'Active') ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= (($_POST['status'] ?? '') === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Photo (optional, JPG/PNG/WEBP, max 2MB)</label>
            <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
        </div>
        <div class="d-flex justify-content-between">
            <a href="students.php" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-success">Save Student</button>
        </div>
    </form>
</div>
</body>
</html>
