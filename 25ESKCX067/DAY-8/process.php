<?php
include 'header.php';

// Validation
if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['cgpa']) ||
    empty($_POST['branch']) ||
    empty($_POST['college']) ||
    empty($_POST['gender']) ||
    empty($_POST['course']) ||
    empty($_POST['address'])
) {
    echo "<div class='alert alert-danger text-center mt-5'>
            <h4>All fields are required!</h4>
            <a href='index.php' class='btn btn-primary mt-3'>Go Back</a>
          </div>";

    include 'footer.php';
    exit();
}

// Get Data
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$cgpa = (float)$_POST['cgpa'];
$branch = htmlspecialchars($_POST['branch']);
$college = htmlspecialchars($_POST['college']);
$gender = htmlspecialchars($_POST['gender']);
$course = htmlspecialchars($_POST['course']);
$address = htmlspecialchars($_POST['address']);

// Function for Grade
function calculateGrade($cgpa)
{
    if ($cgpa >= 9) {
        return ["Excellent", "success"];
    } elseif ($cgpa >= 8) {
        return ["Very Good", "primary"];
    } elseif ($cgpa >= 7) {
        return ["Good", "warning"];
    } else {
        return ["Keep Improving", "danger"];
    }
}

$result = calculateGrade($cgpa);
$grade = $result[0];
$color = $result[1];

$date = date("l, d F Y");
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="confirm-card text-center">

            <img src="https://via.placeholder.com/120" alt="Profile">

            <h2 class="mb-3">
                Welcome,
                <?php echo $name; ?>!
            </h2>

            <p class="text-muted">
                Registration Successful
            </p>

            <hr>

            <div class="text-start">

                <p><strong>Email:</strong> <?php echo $email; ?></p>

                <p><strong>Branch:</strong> <?php echo $branch; ?></p>

                <p><strong>College:</strong> <?php echo $college; ?></p>

                <p><strong>Gender:</strong> <?php echo $gender; ?></p>

                <p><strong>Course:</strong> <?php echo $course; ?></p>

                <p><strong>Address:</strong> <?php echo $address; ?></p>

                <p><strong>CGPA:</strong> <?php echo $cgpa; ?></p>

                <p><strong>Date:</strong> <?php echo $date; ?></p>

            </div>

            <div class="mt-4">
                <span class="badge bg-<?php echo $color; ?>">
                    <?php echo $grade; ?>
                </span>
            </div>

            <div class="mt-4">
                <a href="index.php" class="btn btn-primary">
                    Register Another Student
                </a>
            </div>

        </div>

    </div>
</div>

<?php include 'footer.php'; ?>