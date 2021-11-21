<?php
        session_unset();
        session_destroy();

        if(!isset($_SESSION["username"])){
            header("location: login.php");
            exit;
        }
    ?>