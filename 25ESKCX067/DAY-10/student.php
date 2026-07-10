<?php
require_once "config.php";

$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$branch    = isset($_GET['branch']) ? trim($_GET['branch']) : '';
$status    = isset($_GET['status']) ? trim($_GET['status']) : 'Active'; // default: show only active
$cgpa_min  = isset($_GET['cgpa_min']) && $_GET['cgpa_min'] !== '' ? floatval($_GET['cgpa_min']) : null;
$cgpa_max  = isset($_GET['cgpa_max']) && $_GET['cgpa_max'] !== '' ? floatval($_GET['cgpa_max']) : null;
$show_all_status = isset($_GET['show_all']) && $_GET['show_all'] === '1';


$conditions = [];
$params = [];
$types = "";

if ($search !== '') {
    $conditions[] = "(name LIKE ? OR email LIKE ? OR branch LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "sss";
}

if ($branch !== '') {
    $conditions[] = "branch = ?";
    $params[] = $branch;
    $types .= "s";
}

if (!$show_all_status && $status !== '') {
    $conditions[] = "status = ?";
    $params[] = $status;
    $types .= "s";
}

if ($cgpa_min !== null) {
    $conditions[] = "cgpa >= ?";
    $params[] = $cgpa_min;
    $types .= "d";
}

if ($cgpa_max !== null) {
    $conditions[] = "cgpa <= ?";
    $params[] = $cgpa_max;
    $types .= "d";
}

$where_sql = count($conditions) > 0 ? "WHERE " . implode(" AND ", $conditions) : "";

$sql = "SELECT * FROM students $where_sql ORDER BY name ASC";
$stmt = $conn->prepare($sql);
if ($types !== "") {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$students = $result->fetch_all(MYSQLI_ASSOC);

$total_students = $conn->query("SELECT COUNT(*) AS cnt FROM students")->fetch_assoc()['cnt'];
$avg_cgpa = $conn->query("SELECT ROUND(AVG(cgpa),2) AS avg_cgpa FROM students")->fetch_assoc()['avg_cgpa'];
$branch_stats = $conn->query("SELECT branch, COUNT(*) AS cnt FROM students GROUP BY branch ORDER BY cnt DESC")->fetch_all(MYSQLI_ASSOC);


$all_branches = $conn->query("SELECT DISTINCT branch FROM students ORDER BY branch ASC")->fetch_all(MYSQLI_ASSOC);


function highlight($text, $term) {
    if ($term === '') return htmlspecialchars($text);
    $escaped = htmlspecialchars($text);
    $escapedTerm = htmlspecialchars($term);
    return preg_replace('/(' . preg_quote($escapedTerm, '/') . ')/i', '<mark>$1</mark>', $escaped);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Management System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    body { background: #f4f6f9; }
    .table-striped > tbody > tr:nth-of-type(odd) > * { background-color: #eaf3ff; }
    .student-photo { width: 40px; height: 40px; object-fit: cover; border-radius: 50%; }
    mark { background: #ffe58a; padding: 0 2px; }
    .stat-card { border-radius: 12px; }
</style>
</head>
<body>
<div class="container py-4">

    <h2 class="mb-4"><i class="bi bi-mortarboard-fill text-primary"></i> Student Management System</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-<?= $_GET['msg'] === 'deleted' ? 'danger' : 'success' ?> alert-dismissible fade show" id="flashAlert" role="alert">
            <?php if ($_GET['msg'] === 'updated'): ?>Record updated successfully.
            <?php elseif ($_GET['msg'] === 'added'): ?>Student added successfully.
            <?php elseif ($_GET['msg'] === 'deleted'): ?>Record deleted successfully<?= isset($_GET['name']) ? ": " . htmlspecialchars($_GET['name']) : "" ?>.
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <script>
            setTimeout(() => { const a = document.getElementById('flashAlert'); if (a) new bootstrap.Alert(a).close(); }, 3000);
        </script>
    <?php endif; ?>

    
    <div class="row mb-4">
        <div class="col-md-4 mb-2">
            <div class="card stat-card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Total Students</h6>
                    <h3><?= $total_students ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card stat-card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Average CGPA</h6>
                    <h3><?= $avg_cgpa !== null ? $avg_cgpa : "N/A" ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card stat-card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Students per Branch</h6>
                    <?php foreach ($branch_stats as $b): ?>
                        <div class="d-flex justify-content-between">
                            <span><?= htmlspecialchars($b['branch']) ?></span>
                            <span class="badge bg-light text-dark"><?= $b['cnt'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>


    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Search (name, email, branch)</label>
            <input type="text" name="search" class="form-control" placeholder="e.g. john"
                   value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Course / Branch</label>
            <select name="branch" class="form-select">
                <option value="">All Branches</option>
                <?php foreach ($all_branches as $b): ?>
                    <option value="<?= htmlspecialchars($b['branch']) ?>" <?= $branch === $b['branch'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($b['branch']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small">Min CGPA</label>
            <input type="number" step="0.1" min="0" max="10" name="cgpa_min" class="form-control"
                   value="<?= htmlspecialchars($cgpa_min ?? '') ?>">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Max CGPA</label>
            <input type="number" step="0.1" min="0" max="10" name="cgpa_max" class="form-control"
                   value="<?= htmlspecialchars($cgpa_max ?? '') ?>">
        </div>
        <div class="col-md-2 form-check ms-2">
            <input class="form-check-input" type="checkbox" name="show_all" value="1" id="showAll"
                   <?= $show_all_status ? 'checked' : '' ?>>
            <label class="form-check-label small" for="showAll">Show Inactive too</label>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="badge bg-secondary fs-6">Showing <?= count($students) ?> student<?= count($students) === 1 ? '' : 's' ?></span>
        <a href="add_student.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Add Student</a>
    </div>


    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered bg-white align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>CGPA</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($students) === 0): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i> No students found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $s): ?>
                        <tr>
                            <td>
                                <?php if (!empty($s['photo']) && file_exists("uploads/" . $s['photo'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($s['photo']) ?>" class="student-photo" alt="photo">
                                <?php else: ?>
                                    <i class="bi bi-person-circle fs-3 text-secondary"></i>
                                <?php endif; ?>
                            </td>
                            <td><?= highlight($s['name'], $search) ?></td>
                            <td><?= highlight($s['email'], $search) ?></td>
                            <td><?= highlight($s['branch'], $search) ?></td>
                            <td><?= htmlspecialchars($s['cgpa']) ?></td>
                            <td>
                                <span class="badge bg-<?= $s['status'] === 'Active' ? 'success' : 'secondary' ?>">
                                    <?= htmlspecialchars($s['status']) ?>
                                </span>
                            </td>
                            <td><small class="text-muted"><?= date("d M Y, H:i", strtotime($s['updated_at'])) ?></small></td>
                            <td>
                                <a href="edit_student.php?id=<?= $s['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger"
                                   onclick="confirmDelete(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>'); return false;">
                                    <i class="bi bi-trash3"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    if (confirm("Are you sure you want to delete this record? This action cannot be undone.\n\nStudent: " + name)) {
        window.location.href = "delete_student.php?id=" + id;
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
