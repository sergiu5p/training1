<?php
    session_start();
    if( !isset($_SESSION["cartIds"]) ) {
        header("location: index.php");
    }
