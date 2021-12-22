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
            <h1>Create Categories</h1>
        </div>
        <!-- html form to create product will be here -->

        <!-- PHP insert code will be here -->
        <!-- html form here where the product information will be entered -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Category Name</td>
                    <td><input type='text' name='categories_name' class='form-control' /></td>
                </tr>
                
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='categories_index.php' class='btn btn-danger'>Back to read categories</a>
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
            $categories = htmlspecialchars(strip_tags($_POST['categories_name']));

            $flag = 1;
            $massage = "";
            

            if ($categories == "") {
                $flag = 0;
                $massage = $massage . "Please fill up categories name.  ";
            }


            if ($flag == 1) {
                // insert query
                $query = "INSERT INTO categories SET categories_name=:categories_name";
                // prepare query for execution
                $stmt = $con->prepare($query);

                // bind the parameters
                $stmt->bindParam(':categories_name', $categories);
    
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