<!DOCTYPE HTML>
<html>

<head>
    <title>Create Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <div class="menu">
        <?php include 'menu.php'; ?>
    </div>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Create Order</h1>
            <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method='post'>
                <table class='table table-hover table-responsive table-bordered'>
                    <?php
                    include 'config/database.php';

                    if ($_POST) {
                        // include database connection
                        // echo "<pre>";
                        // var_dump($_POST);
                        // echo"</pre>";

                        // posted values
                        $customer = $_POST['customer'];
                        $product_id = $_POST['product_id'];
                        $quantity = $_POST['quantity'];
                        $flag = 1;

                        if ($customer == "") {
                            $flag == 0;
                        }

                        for ($y = 0; $y < count($product_id); $y++) {
                            if ($product_id[$y] == "" || $quantity[$y] == "") {
                                $flag == 0;
                            }
                        }

                        if (count($product_id) != count(array_unique($product_id))) {
                            $flag == 0;
                            echo "<div class='alert alert-danger'>Duplicate.</div>";
                        }


                        if ($flag == 1) {
                            // insert query
                            $query = "INSERT INTO orders SET customer=:customer";
                            // prepare query for execution
                            $stmt = $con->prepare($query);

                            // bind the parameters
                            $stmt->bindParam(':customer', $customer);
                            $stmt->execute();
                            $id = $con->lastInsertID();
                            echo "<div class='alert alert-success'>Record was saved. The Order ID is $id.</div>";

                            for ($y = 0; $y < count($product_id); $y++) {
                                // Execute the query

                                // insert query
                                $query = "INSERT INTO orderdetails SET quantity=:quantity, product_id=:product_id, order_id=:order_id";
                                // prepare query for execution
                                $stmt = $con->prepare($query);

                                // bind the parameters
                                $stmt->bindParam(':quantity', $quantity[$y]);
                                $stmt->bindParam(':product_id', $product_id[$y]);
                                $stmt->bindParam(':order_id', $id);

                                $stmt->execute();
                            }
                        } else {
                            echo "Please fill in information";
                        }
                    }
                    ?>



                    <?php

                    echo "<tr>";
                    echo "<td>Username</td>";
                    echo "<td>";
                    $query = "SELECT first_name , last_name, username FROM customer ORDER BY username DESC";
                    $stmt = $con->prepare($query);
                    $stmt->execute();

                    $num = $stmt->rowCount();

                    if ($num > 0) {

                        echo "<select class='form-select' aria-label='Default select example' name='customer'>";
                        echo "<option value='A'>Select customer</option>";
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            extract($row);
                            echo "<option value=$username>$first_name $last_name";
                            echo "</option>";
                        }
                        echo "</select>";
                    }
                    echo "</td>";
                    echo "</tr>";
                    ?>

                    <tr class='productQuantity'>

                        <?php
                        $productquery = "SELECT id, name FROM products ORDER BY id DESC";
                        $productstmt = $con->prepare($productquery);
                        $productstmt->execute();
                        $productnum = $productstmt->rowCount();

                        echo "<td>";
                        if ($productnum > 0) {
                            echo "<select class= 'form-select' aria-label='Default select example' name='product_id[]'>";
                            echo "<option value=''>Select product</option>";
                            while ($row = $productstmt->fetch(PDO::FETCH_ASSOC)) {
                                extract($row);
                                echo "<option value=$id>$name";
                                echo "</option>";
                            }
                            echo "</select>";
                        }
                        echo "</td>";
                        ?>

                        <td>
                            <select class='form-select' aria-label='Default select example' name='quantity[]'>
                                <option value='A'>Quantity</option>
                                <option value='1'>1</option>
                                <option value='2'>2</option>
                                <option value='3'>3</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <div class="d-flex justify-content-center flex-column flex-lg-row">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="add_one btn mb-3 mx-2">Add More Product</button>
                                    <button type="button" class="del_last btn mb-3 mx-2">Delete Last Product</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type='submit' value='Submit' class='btn btn-primary' />
                        </td>
                    </tr>
                </table>


            </form>

    </div> <!-- end .container -->


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

        <script>
            document.addEventListener('click', function(event) {
                if (event.target.matches('.add_one')) {
                    var element = document.querySelector('.productQuantity');
                    var clone = element.cloneNode(true);
                    element.after(clone);
                }
                if (event.target.matches('.del_last')) {
                    var total = document.querySelectorAll('.productQuantity').length;
                    if (total > 1) {
                        var element = document.querySelector('.productQuantity');
                        element.remove(element);
                    }
                }
            }, false);
        </script>
</body>