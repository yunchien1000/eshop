<!DOCTYPE HTML>
<html>

<head>
    <title>Create Products</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <?php include 'menu.html';?>    

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
        </div>
        <!-- html form to create product will be here -->
    </div>
    <!-- end .container -->

    <!-- PHP insert code will be here -->

    <!-- html form here where the product information will be entered -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' class='form-control' /></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control'></textarea></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><input type='number' name='price' class='form-control' /></td>
            </tr>
            <tr>
                <td>Manufacture Date</td>
                <td><input type='date' name='manufacture_date' class='form-control' /></td>
            </tr>
            <tr>
                <td>Expired Date</td>
                <td><input type='date' name='expired_date' class='form-control' /></td>
            </tr>
            <tr>
                <td>Promotion Price</td>
                <td><input type='number' name='promotion_price' class='form-control' /></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type='submit' value='Save' class='btn btn-primary' />
                    <a href='index.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>
    </form>

    <?php
    
    if ($_POST) {
        // include database connection
        include 'config/database.php';
        try {
            // insert query
            $query = "INSERT INTO products SET name=:name, description=:description, price=:price, manufacture_date=:manufacture_date, expired_date=:expired_date, promotion_price=:promotion_price, created=:created";
            // prepare query for execution
            $stmt = $con->prepare($query);
            // posted values
            $name = htmlspecialchars(strip_tags($_POST['name']));
            $description = htmlspecialchars(strip_tags($_POST['description']));
            $price = htmlspecialchars(strip_tags($_POST['price']));
            $manufacture_date = date(strip_tags($_POST['manufacture_date']));
            $edate = date(strip_tags($_POST['expired_date']));
            $promotion_price = htmlspecialchars(strip_tags($_POST['promotion_price']));
            // bind the parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':manufacture_date', $manufacture_date);
            $stmt->bindParam(':expired_date', $edate);
            $stmt->bindParam(':promotion_price', $promotion_price);
            // specify when this record was inserted to the database
            $created = date('Y-m-d H:i:s');
            $stmt->bindParam(':created', $created);
            // Execute the query
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Record was saved.</div>";
            } else {
                echo "<div class='alert alert-danger'>Unable to save record.</div>";
            }


            if (is_numeric($price)){
                echo var_export($price,true),PHP_EOL;
            }else {
                echo var_export($price, true). "is NOT numeric", PHP_EOL;
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

