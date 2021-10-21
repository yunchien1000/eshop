<!DOCTYPE html>
<html>

<head>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

  <?php
  if ($_GET) {
    echo $_GET["fname"];
    echo "</br>";
    echo $_GET["lname"];
    echo "</br>";
    echo $_GET["hobby"];
  }
  ?>
  <h2 class="m-3">HTML Forms</h2>

  <form action="action.php" method="get">

    <div class="input-group m-3">
      <span class="input-group-text" id="basic-addon1">First Name</span>
      <input type="text" id="fname" name="fname" value="John"><br>


      <div class="input-group my-3">
        <span class="input-group-text" id="basic-addon1">Last Name</span>
        <input type="text" id="lname" name="lname" value="Doe"><br><br>
      </div>

      <div class="input-group ">
        <span class="input-group-text" id="basic-addon1">Hobby</span>
      <select name="hobby" id="hobby">
        <option value="Reading">Reading</option>
        <option value="Gaming">Gaming</option>
        <option value="Fishing">Fishing</option>
      </select>
      </div>
      <input type="submit" class="btn btn-primary my-3" value="Submit">
      
  </form>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>