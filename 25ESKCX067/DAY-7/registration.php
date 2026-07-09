<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
</head>
<body>

<h2>Student Registration Form</h2>

<form action="welcome.php" method="POST">

Name:<br>
<input type="text" name="name"><br><br>

Email:<br>
<input type="email" name="email"><br><br>

Phone:<br>
<input type="text" name="phone"><br><br>

Branch:<br>
<input type="text" name="branch"><br><br>

Gender:<br>
<input type="radio" name="gender" value="Male">Male
<input type="radio" name="gender" value="Female">Female
<input type="radio" name="gender" value="prefer not to say">Prefer not to say
<br><br>

CGPA:<br>
<select name="cgpa">
    <option>9</option>
    <option>8.5</option>
    <option>less than 8.5</option>
</select>
<br><br>

Address:<br>
<textarea name="address"></textarea>
<br><br>

Photo:<br>
<input type="file" name="photo">
<br><br>

<input type="submit" value="Register">

</form>

</body>
</html>
