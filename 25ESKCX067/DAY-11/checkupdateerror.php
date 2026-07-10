<?php
$error = "";

$newpassword = "";
$confirmpassword = "";
$oldpassword = "";

session_start();

$id = $_SESSION['user_id'];

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $newpassword = mysqli_real_escape_string($conn, $_POST["newpassword"]);
    $confirmpassword = mysqli_real_escape_string($conn, $_POST["confirmpassword"]);
    $oldpassword = mysqli_real_escape_string($conn, $_POST["oldpassword"]);


    if($newpassword == "" || $confirmpassword == "" || $oldpassword == ""){

        $error = "All fields are required.";
        echo $error;
        exit();

    }
    elseif($newpassword != $confirmpassword){

        $error = "Password does not match";
        echo $error;
        exit();

    }

    // Password requirements for new password
    elseif(strlen($newpassword) < 8){

        echo "Password must be at least 8 characters long.";
        exit();

    }
    elseif(!preg_match("/[A-Z]/", $newpassword)){

        echo "Password must contain at least one uppercase letter.";
        exit();

    }
    elseif(!preg_match("/[a-z]/", $newpassword)){

        echo "Password must contain at least one lowercase letter.";
        exit();

    }
    elseif(!preg_match("/[0-9]/", $newpassword)){

        echo "Password must contain at least one number.";
        exit();

    }
    elseif(!preg_match("/[!@#$%^&*]/", $newpassword)){

        echo "Password must contain at least one special character.";
        exit();

    }

    else{


        // Get old password hash from database
        $checkquery = "SELECT password FROM user WHERE id='$id'";

        $checkresult = mysqli_query($conn, $checkquery);

        $user = mysqli_fetch_assoc($checkresult);



        if($user && password_verify($oldpassword, $user['password'])){


            // Hash new password
            $hashednewPassword = password_hash($newpassword, PASSWORD_DEFAULT);


            // Update password
            $updatequery = "UPDATE user 
                            SET password='$hashednewPassword' 
                            WHERE id='$id'";


            $result = mysqli_query($conn, $updatequery);


            if($result){

                echo "Password updated successfully";

                header("Location: dashboard.php");
                exit();

            }
            else{

                echo "Error: " . mysqli_error($conn);

            }


        }
        else{

            echo "Old password is incorrect";

        }

    }

}

?>