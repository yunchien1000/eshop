<!DOCTYPE HTML>
<html>

<head>
    <title>Read Product</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <?php include 'menu.php'; ?>

    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Read Products</h1>
        </div>


        <?php
        // include database connection
        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";
        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }


        // delete message prompt will be here
        $query = "SELECT products.id, name, products.categories, price, promotion_price, manufacture_date, expired_date, categories_name as categories, categories_id FROM products INNER JOIN categories ON products.categories = categories_id ORDER BY products.id DESC";

        $categories = "";

        if ($_POST) {
            $query = "SELECT products.id, name, products.categories, price, promotion_price, manufacture_date, expired_date, categories_name as categories, categories_id FROM products INNER JOIN categories ON products.categories = categories_id WHERE  categories = ? ORDER BY products.id DESC";

            $categories = htmlspecialchars(strip_tags($_POST['categories']));

            if ($categories == "all") {
                $query = "SELECT products.id, name, products.categories, price, promotion_price, manufacture_date, expired_date, categories_name as categories, categories_id FROM products INNER JOIN categories ON products.categories = categories_id ORDER BY products.id DESC";
            }
        }

        // select all data

        $stmt = $con->prepare($query);
        if ($_POST && $categories !== "all") {
            $stmt->bindParam(1, $categories);
        }
        $stmt->execute();
        $num = $stmt->rowCount();

        // link to create record form
        echo "<a href='create.php' class='btn btn-primary my-2'>Create New Product</a>";
        ?>

        <?php
        $categoryquery = "SELECT categories_id, categories_name FROM categories ORDER BY categories_id DESC";
        $categorystmt = $con->prepare($categoryquery);
        $categorystmt->execute();

        $numcategory = $categorystmt->rowCount();

        if ($numcategory > 0) {
            echo "<form action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . " method='post'>";
            echo "<div class='container p-0 my-2'>";
            echo "<div class='row'>";
            echo "<div class='col-2'>";
            echo "<select class='form-select' aria-label='Default select example' name='categories'>";
            echo "<option value='all' name='all'>All</option>";

            while ($row = $categorystmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<option value='$categories_id'";
                if ($categories_id == $categories) {
                    echo "selected";
                }
                echo ">";
                echo "{$categories_name}";
                echo "</option>";
            }
            echo "</select>";
            echo "</div>";

            echo "<div class='col-2'>";
            echo "<button type='submit' class='btn btn-primary'>Search</button>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
            echo "</form>";
        }

        ?>

        <?php
        //check if more than 0 record found
        if ($num > 0) {

            // data from database will be here
            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Categories</th>";
            echo "<th>Name</th>";
            echo "<div class='d-flex flex-row-reverse'>";
            echo "<th>Price</th>";
            echo "<th>Promotion Price</th>";
            echo "</div>";
            echo "<th>Manufacture date</th>";
            echo "<th>Expired Date</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // table body will be here
            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$categories}</td>";
                echo "<td>{$name}</td>";

                $price_format = number_format($price, 2, '.', '');
                $pprice_format = number_format($promotion_price, 2, '.', '');
                echo "<td class='d-flex flex-row-reverse'>{$price_format}</td>";
                echo "<td>{$pprice_format}</td>";

                echo "<td>{$manufacture_date}</td>";
                echo "<td>{$expired_date}</td>";
                echo "<td>";
                // read one record
                echo "<a href='read_one.php?id={$id}' class='btn btn-info m-2'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='update.php?id={$id}' class='btn btn-primary m-2'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a onclick='delete_user({$id});'  class='btn btn-danger m-2'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            // end table
            echo "</table>";
        }
        // if no records found
        else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>


    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <script type='text/javascript'>
        // confirm record deletion
        function delete_user(id) {

            var answer = confirm('Are you sure?');
            if (answer) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>

</body>

</html>