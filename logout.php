<?PHP

// Necessary phpCAS Setup files for RPI's system
require 'resources/rpiCAS.php';

if (phpCAS::isAuthenticated()) {
    // If the user is currently logged in, log them out using CAS.
    // TODO: add redirect to send them back to the homepage after logout
    phpCAS::logout();
} else {
    // Otherwise, they don't need to be logged out, go to index
    header('location: ./index.php');
}
?>