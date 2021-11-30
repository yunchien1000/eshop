<?php
session_start();
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Log In</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<?php
include 'config/database.php';

if ($_POST) {

    $username = htmlspecialchars(strip_tags($_POST['username']));
    $email = htmlspecialchars(strip_tags($_POST['email']));
    $password = htmlspecialchars(strip_tags($_POST['password']));

    if ($username == "" || $password == "" || $email == "") {
        echo "<div class='alert alert-danger'>Please enter your information </div>";
    } else {

        if (isset($username)) {
            $query = "SELECT username , password, email, account_status FROM customer WHERE username= ? ";

            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $username);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (is_array($row)) {
                if (md5($password) == $row['password']) {
                    if ($row['account_status'] == 1) {
                        if ($email == $row['email']) {
                            // echo "<div class='alert alert-success'>Logged In!</div>";
                            $_SESSION['username'] = $username;
                            header("location: welcome.php");
                            exit;
                        }else {
                            echo"<div class='alert alert-danger'>Wrong email. </div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Acount is unactive</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Wrong password</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>User not found</div>";
            }
        }
    }
}
?>

<body class="bg-light my-5 py-5">

    <div class="col-lg-5 mx-auto">
        <div class="bg-white p-5 rounded shadow">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Please Sign In</h2>


                <div class="mb-3 mt-4">
                    <label class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control">
                </div>

                <div class="mb-5">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">

                    <input type="checkbox" onclick="myFunction()">Show Password
                </div>

                <div class="d-grid mx-auto">
                    <button type="submit" class="btn btn-primary">Sign In</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>