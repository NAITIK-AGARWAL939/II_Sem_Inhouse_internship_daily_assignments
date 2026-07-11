<?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">
                <h2>
                    <i class="fa-solid fa-user-plus"></i>
                    Student Registration Form
                </h2>
            </div>

            <div class="card-body">

                <form action="process.php" method="POST" enctype="multipart/form-data">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>

                    <!-- CGPA -->
                    <div class="mb-3">
                        <label class="form-label">CGPA</label>
                        <input type="number" class="form-control" name="cgpa" min="0" max="10" step="0.01" required>
                    </div>

                    <!-- Branch -->
                    <div class="mb-3">
                        <label class="form-label">Branch</label>
                        <input type="text" class="form-control" name="branch" required>
                    </div>

                    <!-- College -->
                    <div class="mb-3">
                        <label class="form-label">College Name</label>
                        <input type="text" class="form-control" name="college" required>
                    </div>

                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label d-block">Gender</label>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Male" required>
                            <label class="form-check-label">Male</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Female">
                            <label class="form-check-label">Female</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" value="Other">
                            <label class="form-check-label">Other</label>
                        </div>
                    </div>

                    <!-- Course -->
                    <div class="mb-3">
                        <label class="form-label">Course</label>

                        <select class="form-select" name="course" required>
                            <option value="">Select Course</option>
                            <option>B.Tech</option>
                            <option>PHD</option>
                            <option>M.Tech</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="3" name="address" required></textarea>
                    </div>

                    <!-- Photo -->
                    <div class="mb-4">
                        <label class="form-label">Student Photo</label>
                        <input type="file" class="form-control" name="photo">
                        <small class="text-muted">
                            (UI only. Upload is not processed.)
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-paper-plane"></i>
                        Submit Registration
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

<?php include 'footer.php'; ?>
