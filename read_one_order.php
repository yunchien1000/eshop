<!DOCTYPE HTML>
<html>

<head>
    <title>Read One Orders</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <?php include 'menu.php'; ?>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Customer Orders</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['order_id']) ? $_GET['order_id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT orderdetails_id, order_id, product_id, quantity, products.id, products.name as proname, products.price as proprice FROM orderdetails INNER JOIN products ON orderdetails.product_id = products.id WHERE order_id = ?";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $order_id = $row['order_id'];
            $quantity = $row['quantity'];
        ?>
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Order Id</td>
                    <td colspan="6"><?php echo htmlspecialchars($order_id, ENT_QUOTES);  ?></td>
                </tr>
                <?php
                $totalamount = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                ?>
                    <tr>
                        <td>Product</td>
                        <td><?php
                            echo htmlspecialchars($proname, ENT_QUOTES);
                            ?>
                        </td>

                        <td>Quantity</td>
                        <td><?php
                            echo htmlspecialchars($quantity, ENT_QUOTES);
                            ?>
                        </td>

                        <td>Price</td>
                        <td><?php
                            $total = ($proprice * $quantity);
                            echo $total;
                            $totalamount = $totalamount + $total;
                            ?>
                        </td>
                    </tr>

                <?php } ?>
                <tr>
                    <td>Total</td>
                    <td colspan="5"><?php
                    echo number_format($totalamount, 2);
                    ?>
                    </td>
                </tr>
            <?php
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
            ?>

            <tr>
                <td colspan="6">
                    <a href='order_index.php' class='btn btn-danger'>Back to read order</a>
                </td>
            </tr>
            </table>


    </div> <!-- end .container -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>