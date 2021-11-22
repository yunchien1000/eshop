<!DOCTYPE HTML>
<html>

<head>
    <title>Update Customer</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <?php include 'menu.php'; ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record username not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, password, first_name, last_name, gender, date_of_birth, registration_date_time, account_status FROM customer WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $cusername = $row['username'];
            $password = $row['password'];
            $fname = $row['first_name'];
            $lname = $row['last_name'];
            $gender = $row['gender'];
            $DOB = $row['date_of_birth'];
            $registration_date_time = $row['registration_date_time'];
            $account_status = $row['account_status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }



        // retrieve our table contents
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['firstname'] to just $firstname only
            extract($row);
            // creating new table row per record
            echo "<tr>";
            echo "<td>{$cusername}</td>";
            echo "<td>{$password}</td>";
            echo "<td>${$fname}</td>";
            echo "<td>${$lname}</td>";
            echo "<td>${$gender}</td>";
            echo "<td>${$DOB}</td>";
            echo "<td>${$registration_date_time}</td>";
            echo "<td>${$account_status}</td>";
            echo "<td>";
            // read one record
            echo "<a href='c_read_one.php?username={$id}' class='btn btn-info m-r-1em'>Read</a>";

            // we will use this links on next part of this post
            echo "<a href='c_update.php?username={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

            // we will use this links on next part of this post
            echo "<a href='#' onclick='delete_user({$id});'  class='btn btn-danger'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }


        ?>


        <!-- HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->

        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($cusername, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='first_name' value="<?php echo htmlspecialchars($fname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='last_name' value="<?php echo htmlspecialchars($lname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <input class="form-check-input" type="radio" value="0" name="gender">
                        <label class="form-check-label" for="inlineRadio1">
                            Male
                        </label>
                        <input class="form-check-input" type="radio" value="1" name="gender" checked>
                        <label class="form-check-label" for="inlineRadio2">
                            Female
                        </label>
                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='date_of_birth' value="<?php echo htmlspecialchars($DOB, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Registration date & time</td>
                    <td><?php echo htmlspecialchars($registration_date_time, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td><?php echo htmlspecialchars($account_status != '1' ? 'Unonline' : 'Online', ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='c_index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>



        <?php
        // check if form was submitted
        if ($_POST) {
            try {

                // posted values
                $cusername = htmlspecialchars(strip_tags($_POST['username']));
                $fname = htmlspecialchars(strip_tags($_POST['first_name']));
                $lname = htmlspecialchars(strip_tags($_POST['last_name']));
                $gender = isset($_POST['gender']) ? $_POST['gender'] : "";
                $DOB = htmlspecialchars(strip_tags($_POST['date_of_birth']));
                $flag = 1;
                $massage = "";
                $year = substr($DOB, 0, 4);
                $nowyear = date("Y");

                if ($fname == "" || $lname == "" || $gender == "" || $DOB == "") {
                    $flag = 0;
                    $massage = $massage . "Please fill up customer information.";
                }
                $myage = $nowyear - $year;
                if ($myage < 18) {
                    $flag = 0;
                    $massage = $massage . "Must above or 18 years old. ";
                }


                if ($flag == 1) {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE customer SET username=:username, first_name=:first_name, last_name=:last_name, gender=:gender, date_of_birth=:date_of_birth WHERE username = :username";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':username', $cusername);
                    $stmt->bindParam(':first_name', $fname);
                    $stmt->bindParam(':last_name', $lname);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':date_of_birth', $DOB);
                    $stmt->bindParam(':username', $id);
                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>$massage </div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }
        ?>

    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>