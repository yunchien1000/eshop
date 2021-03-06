<?php session_start(); ?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Create Customer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <?php include 'menu.php'; ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Customer</h1>
        </div>
        <!-- html form to create product will be here -->

        <!-- PHP insert code will be here -->
        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type='password' name='password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type='password' name='confirm_password' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='text' name='email' class='form-control' /></td>
                </tr>
                <tr>
                    <td>First name</td>
                    <td><input type='text' name='first_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td><input type='text' name='last_name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input class="form-check-input" type="radio" value="0" name="gender">
                        <label class="form-check-label" for="inlineRadio1">
                            Male
                        </label>
                        <input class="form-check-input" type="radio" value="1" name="gender">
                        <label class="form-check-label" for="inlineRadio2">
                            Female
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='c_index.php' class='btn btn-danger'>Back to read customer</a>
                    </td>
                </tr>
            </table>
        </form>
    </div><!-- end .container -->
    <?php

    if ($_POST) {
        // include database connection
        include 'config/database.php';
        try {

            // posted values
            $username = htmlspecialchars(strip_tags($_POST['username']));
            $password = htmlspecialchars(strip_tags($_POST['password']));
            $confirm_password = $_POST['confirm_password'];
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $first_name = htmlspecialchars(strip_tags($_POST['first_name']));
            $last_name = htmlspecialchars(strip_tags($_POST['last_name']));
            $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
            $DOB = date('Y-m-d', strtotime($_POST['date_of_birth']));
            $flag = 1;
            $massage = "";
            $year = substr($DOB, 0, 4);
            $nowyear = date("Y");



            if ($username == "" || $password == "" || $confirm_password == "" || $email == "" || $first_name == "" || $last_name == "" || $gender == "" || $DOB == "") {
                $flag = 0;
                $massage = $massage . "Please fill up your information.  ";
            }


            $query = "SELECT username FROM customer WHERE username = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($row)) {
                $flag = 0;
                $massage = $massage . "Username already taken.  ";
            }


            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $flag = 0;
                $massage = $massage . "Invalid email format.  ";
            }

            $query = "SELECT email FROM customer WHERE email = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $email);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($row)) {
                $flag = 0;
                $massage = $massage . "Email already taken.  ";
            }

            if (strlen($password) < 6) {
                $flag = 0;
                $massage = $massage . "Password must more than 6 character.  ";
            }
            if ($password != $confirm_password) {
                $flag = 0;
                $massage = $massage . "Password must be same.  ";
            }

            $myage = $nowyear - $year;

            if ($myage < 18) {
                $flag = 0;
                $massage = $massage . "Must above or 18 years old.  ";
            }



            if ($flag == 1) {
                // insert query
                $query = "INSERT INTO customer SET username=:username, password=:password, email=:email,first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':username', $username);
                $newpassword = md5($password);
                $stmt->bindParam(':password', $newpassword);
                $stmt->bindParam(':email', $email);
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
            } else {
                echo "<div class='alert alert-danger'>$massage </div>";
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>