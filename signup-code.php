<?php
// Include the database connection file
require 'config/dbcon.php';

// Check if the form is submitted
if(isset($_POST['signupBtn'])) {
    // Get form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $accountType = mysqli_real_escape_string($conn, $_POST['accountType']);

    // Hash the password before saving it to the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check for duplicate entries
    $checkUnique = function($column, $value, $table) use ($conn) {
        $query = "SELECT * FROM $table WHERE $column = '$value'";
        $result = mysqli_query($conn, $query);
        return mysqli_num_rows($result) > 0;
    };

    if ($accountType === 'admin') {
        // For administrators, get the staff ID
        $staff_id = mysqli_real_escape_string($conn, $_POST['staff_id']);

        // Array of valid staff IDs
        $valid_staff_ids = [1000, 1001, 1002, 1004, 1005];

        // Check if the entered staff ID is valid
        if (in_array($staff_id, $valid_staff_ids)) {
            // Check for duplicate staff ID
            if ($checkUnique('staff_id', $staff_id, 'admins')) {
                echo "<script>
                        alert('Staff ID is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate email
            if ($checkUnique('email', $email, 'admins')) {
                echo "<script>
                        alert('Email is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate phone number
            if ($checkUnique('phone', $phone, 'admins')) {
                echo "<script>
                        alert('Phone number is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate name
            if ($checkUnique('name', $name, 'admins')) {
                echo "<script>
                        alert('Name is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Proceed with the sign-up if the staff ID is valid and unique
            $sql = "INSERT INTO admins (name, email, password, phone, staff_id) VALUES ('$name', '$email', '$hashed_password', '$phone', '$staff_id')";
        } else {
            // If the staff ID is not valid, show an error message and redirect back to sign-up page
            echo "<script>
                    alert('Invalid staff ID. Please enter a valid staff ID to sign up as an administrator.');
                    window.location.href='signup.php';
                  </script>";
            exit(0);
        }
    } else {
        // For users, get the registration ID
        $registration_id = mysqli_real_escape_string($conn, $_POST['registration_id']);

        // Array of valid registration IDs
        $valid_registration_ids = [2000, 2001, 2003, 2005, 2006];

        // Check if the entered registration ID is valid
        if (in_array($registration_id, $valid_registration_ids)) {
            // Check for duplicate registration ID
            if ($checkUnique('registration_id', $registration_id, 'users')) {
                echo "<script>
                        alert('Registration ID is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate email
            if ($checkUnique('email', $email, 'users')) {
                echo "<script>
                        alert('Email is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate phone number
            if ($checkUnique('phone', $phone, 'users')) {
                echo "<script>
                        alert('Phone number is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Check for duplicate name
            if ($checkUnique('name', $name, 'users')) {
                echo "<script>
                        alert('Name is already taken.');
                        window.location.href='signup.php';
                      </script>";
                exit(0);
            }

            // Proceed with the sign-up if the registration ID is valid and unique
            $sql = "INSERT INTO users (name, email, password, phone, registration_id) VALUES ('$name', '$email', '$hashed_password', '$phone', '$registration_id')";
        } else {
            // If the registration ID is not valid, show an error message and redirect back to sign-up page
            echo "<script>
                    alert('Invalid registration ID. Please enter a valid registration ID to sign up as a user.');
                    window.location.href='signup.php';
                  </script>";
            exit(0);
        }
    }

    // Execute the query
    if(mysqli_query($conn, $sql)) {
        // Display a success message
        echo "<script>
                alert('Congratulations! Sign-up successful. You can now log in.');
                window.location.href='login.php';
              </script>";
        exit(0);
    } else {
        // Display an error message if the query fails
        echo "<script>
                alert('Sign-up failed. Please try again.');
                window.location.href='signup.php';
              </script>";
        exit(0);
    }
} else {
    // Redirect to the sign-up page if the form wasn't submitted
    header("Location: signup.php");
    exit(0);
}
?>
