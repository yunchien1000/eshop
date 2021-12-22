<?php
session_start();
?>
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
            <h1>Update Category</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $categories_id = isset($_GET['categories_id']) ? $_GET['categories_id'] : die('ERROR: Record id not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT categories_id, categories_name FROM categories WHERE categories_id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $categories_id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $categories_id = $row['categories_id'];
            $categories_name = $row['categories_name'];


            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['firstname'] to just $firstname only
                extract($row);
                // creating new table row per record
                echo "<tr>";
                echo "<td>{$categories_id}</td>";
                echo "<td>{$categories_name}</td>";
                echo "<td>";

                echo "<a href='categories_read_one.php?categories_id={$categories_id}' class='btn btn-info m-r-1em'>Read</a>";

                echo "<a href='categories_update.php?categories_id={$categories_id}' class='btn btn-primary m-r-1em'>Edit</a>";


                echo "<a href='#' onclick='delete_user({$categories_id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

<?php
        // check if form was submitted
        if ($_POST) {
            try {

                // posted values
                // $categories_id = htmlspecialchars(strip_tags($_POST['categories_id']));
                $categories_name = htmlspecialchars(strip_tags($_POST['categories_name']));
                $flag = 1;
                $massage = "";
                


                if($flag == 1) {
                    // write update query
                    // in this case, it seemed like we have so many fields to pass and
                    // it is better to label them and not use question marks
                    $query = "UPDATE categories SET categories_name=:categories_name WHERE categories_id = :categories_id";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);
                    // bind the parameters
                    $stmt->bindParam(':categories_id', $categories_id);
                    $stmt->bindParam(':categories_name', $categories_name);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }else {
                    echo "<div class='alert alert-danger'>$massage </div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>
        <!-- HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->

        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?categories_id={$categories_id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>ID</td>
                    <td><?php echo htmlspecialchars($categories_id, ENT_QUOTES);  ?></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='categories_name' id='categories_name' value="<?php echo htmlspecialchars($categories_name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>

                <td></td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='categories_index.php' class='btn btn-danger'>Back to read categories</a>
                </td>
                </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>