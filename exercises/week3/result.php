<?php
    if ($_POST) {
        // include database connection
        include 'config/database.php';
        try {
            // insert query
            $query = "INSERT INTO customer SET username=:username, password=:password, confirm_password=:confirm_password, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth";
            // prepare query for execution
            $stmt = $con->prepare($query);
            // posted values
            $username = htmlspecialchars(strip_tags($_POST['username']));
            $password = md5($_POST['password']);
            $confirm_password = md5($_POST['confirm_password']);
            $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
            $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
            $gender = htmlspecialchars(strip_tags($_POST['gender']));
            $DOB = date(strip_tags($_POST['date_of_birth']));

            if (empty($password || $confirm_password)) {
                echo "Please enter password";
            } elseif ($password != $confirm_password) {
                echo "<div class='alert alert-danger'>Password and Confirm password should match!</div>";
            } else {
                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $password);
                $stmt->bindParam(':confirm_password', $confirm_password);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':date_of_birth', $DOB);
                // specify when this record was inserted to the database
                // Execute the query
                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Unable to save record.</div>";
                }
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }
    ?>