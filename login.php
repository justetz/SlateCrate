<?php

// Necessary phpCAS Setup files for RPI's system
require 'resources/rpiCAS.php';

if (!phpCAS::isAuthenticated()) {
    // If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
} else{
    // Otherwise, they don't need to be logged in, go to posts
    header('location: ./index.php');
}

?>