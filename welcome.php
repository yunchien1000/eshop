<?php
// include 'session.php';
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>Read Customer</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <?php include 'menu.php';

    // include database connection
    include 'config/database.php';
    ?>
    <?php
    echo "Today Date: ";
    echo date("M j, Y");
    echo "<br>";
    echo "Welcome";

    include 'config/database.php';

    $id = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record user not found.');

    $query = "SELECT username, last_name, gender FROM customer WHERE username=?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $lastname = $row['last_name'];
    $gender = $row['gender'];

    if ($gender = 1) {
        echo " Ms ";
    } else {
        echo " Mr ";
    };
    echo $lastname;

    ?>
    <div class="container px-4">
        <div class="row gx-1">
            <div class="col text-center border bg-light">
                <p class="fw-bold text-uppercase">Total Order</p>
                <?php
                $query = "SELECT * FROM orders ORDER BY order_id DESC";
                $stmt = $con->prepare($query);
                $stmt->execute();

                // this is how to get number of rows returned
                $num = $stmt->rowCount();

                if ($num > 0) {
                    echo $num;
                }
                ?>

            </div>
            <div class="col text-center border bg-light">

                <p class="fw-bold text-uppercase">Total Price</p>

                <?php
                $totalpricequery = "SELECT orderdetails_id,order_id,product_id,quantity, products.id ,products.price as proprice, products.name as proname FROM orderdetails INNER JOIN products ON orderdetails.product_id = products.id";
                $totalpricestmt = $con->prepare($totalpricequery);
                $totalpricestmt->execute();
                $row = $totalpricestmt->fetch(PDO::FETCH_ASSOC);
                $totalamount = 0;
                $proprice = $row['proprice'];
                $quantity = $row['quantity'];
                while ($row = $totalpricestmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $total = ($proprice * $quantity);
                    $totalamount = $totalamount + $total;
                }
                echo $totalamount;
                ?>
            </div>
            <div class="col text-center border bg-light">
                <p class="fw-bold text-uppercase">Total Price</p>
                <?php
                // delete message prompt will be here
                $action = isset($_GET['action']) ? $_GET['action'] : "";
                // if it was redirected from delete.php
                if ($action == 'deleted') {
                    echo "<div class='alert alert-success'>Record was deleted.</div>";
                }

                // select all data
                $query = "SELECT username, email, first_name, last_name, gender, date_of_birth, registration_date_time, account_status FROM customer ORDER BY username DESC";
                $stmt = $con->prepare($query);
                $stmt->execute();

                // this is how to get number of rows returned
                $num = $stmt->rowCount();

                if ($num > 0) {
                    echo $num;
                }
                ?>

            </div>
        </div>
    </div>

    <div class="container px-4">
        <div class="row gx-5">
            <div class="col">
                <div class="p-3 border bg-light">Top Selling Product</div>
                <?php
                $topsellingquery = "SELECT product_id,
                SUM(quantity) AS TotalQuantity,
                (SELECT SUM(inv.qty) FROM `inventory` AS inv WHERE inv.product_code = oi.product_code) AS CurrentStock
                FROM `order_items` AS oi
                GROUP BY oi.product_code
                ORDER BY TotalQuantity DESC
                LIMIT 3";
                ?>
            </div>
        </div>
    </div>


    <?php
    try {

        if (isset($myUsername)) {
            $query = "SELECT order_id, orderdetails.name as oname, max(order_date) as MaxDate
            FROM orderdetails
            WHERE orderdetails.name = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $myUsername);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            //var_dump($row);

            // values to fill up our form
            $order_id = $row['order_id'];
            $oname = $row['oname'];
            $order_date = $row['MaxDate'];
        }

        if (isset($order_date)) {
            $query = "SELECT order_id, orders.customer as oname, product_id, quantity, max(order_date) as MaxDate, products.price as pprice, products.id as pid, products.name as pname
            FROM orders
            -- INNER JOIN products 
            -- ON orderdetails.product_id = products.id
            -- INNER JOIN orders 
            -- ON orders.customer = orders.id
            WHERE orders.order_datetime = ?";
            $stmt = $con->prepare($query);
            $stmt->bindParam(1, $order_datetime);
            //var_dump($row);
            $stmt->execute();

            // values to fill up our form
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $pprice = $row['pprice'];
                $quantity = $row['quantity'];
                $total = 0;
                $totalamount = 0;
                extract($row);
                $total = ($pprice * $quantity);
            }
        }
        $totalamount = $totalamount + $total;


    ?>
        <div class="container px-4">
            <div class="row gx-5">
                <div class="col">
                    <div class="p-3 border bg-light">
                        <h3>Lastest Order</h3>
                        <div class='col-5'>Order ID : </td>
                            <td class='col-6'><?php echo "OID " . $order_id ?>
                        </div>
                        <div class='col-5'>Customer Name : </td>
                            <td class='col-6'><?php echo $oname ?>
                        </div>
                        <div class='col-5'>Total Amount : </td>
                            <td class='col-6'><?php echo $totalamount ?>
                        </div>
                        <div class='col-5'>Order Date : </td>
                            <td class='col-6'><?php echo $order_datetime ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--
        <div class='highlight'>
            <h3>Lastest Order</h3>
        </div>
        -->

    <?php
    }
    // show error
    catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
    }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>