<?php
function executeEdit($conn, $newTitle, $newPrefix, $id) {
    try {
        $conn->query("UPDATE `categories` SET `title` = '" . $newTitle . "', `prefix` = '" . $newPrefix . "' WHERE `category_id` = " . $id);

        return array("success", $newTitle . " was successfully edited!");
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

function executeAdd($conn, $className, $inputCategory, $user) {
    try {
        $string = "'" . $className . "', '" . $inputCategory . "', '" . $user . "', " . "CURDATE()";

        $conn->query("INSERT INTO `categories` (`title`, `prefix`, `rcs_id`, `creation_date`) VALUES (" . $string . ");");

        return array("success", "Your new class, entitled " . $_POST["className"] . ", was successfully added!");
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

function executeDelete($conn, $categoryId) {
    try {
        $del = $conn->prepare("SELECT `link_id` FROM `links` WHERE `category_id` = " . $categoryId);
        $del->execute();
        while($result = $del->fetch(PDO::FETCH_ASSOC)){
            $conn->query("DELETE FROM `links` WHERE `link_id` = " . $result["link_id"]);
        }
        $conn->query("DELETE FROM `categories` WHERE `category_id` = " . $categoryId);

        return array("success","The class, and its links, have been deleted!");
    } catch(PDOException $e){
        return array("error", $e);
    }
}

function populateSidebar($prefix) {
    echo "<li role='presentation'";
    if($prefix == "") {
        echo " class='active'";
    }
    echo "><a href='classes.php'>All Prefixes</a></li>";

    require_once 'resources/prefixes.php';

    foreach ($prefixes as $p) {
        // Add another item to the list, calling the function
        // 'determineIfActive' to determine if the active class
        // should be included in the item
        echo "<li role='presentation' " . determineIfActivePrefix($prefix, $p)
            . "><a href='?prefix=" . $p . "'>" . $p . "</a></li>";
    }
}

function populatePagination($count, $prefix) {
    if(($count / 16) + 1 >= 2) {
        echo "<div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";
        for ($button = 1; $button < ($count / 16) + 1; $button++) {
            $link = "?";
            if ($prefix != "") {
                $link .= "prefix=" . $prefix . "&";
            }
            $link .= "page=$button";
            echo "<a href=\"$link\" class=\"btn btn-primary\">$button</a>";
        }
        echo "</div></div>";
    }
}

if (!phpCAS::isAuthenticated()) {
// If they're not currently logged in, take them to the RPI CAS page
    phpCAS::forceAuthentication();
}

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

$isAdmin = determineAdminStatus($conn, phpCAS::getUser());

if(isset($_POST["edit"])){
    // Update an edited class with new details
    $alertArray = executeEdit($conn, $_POST["className"], $_POST["inputCategory"], $_POST["edit"]);
} else if(isset($_POST["user"])){
    // Create a newly added class with the new details
    $alertArray = executeAdd($conn, $_POST["className"], $_POST["inputCategory"], $_POST["user"]);
} else if(isset($_POST["delete"])){
    // Complete the deletion of a class
    $alertArray = executeDelete($conn, $_POST["delete"]);
}

if(isset($alertArray)) {
    $alertType = $alertArray[0];
    $alertMessage = $alertArray[1];
} else {
    $alertMessage = "";
    $alertType = "";
}

$search = isset($_POST["srch"]) ? $_POST["srch"] : "";
$sort = isset($_POST["sort"]) ? $_POST["sort"] : "`title`";

// Set the page heading appropriately, depending on if the url specifies a prefix
$pageHeader = isset($_GET["prefix"]) ? "Classes for " . $_GET["prefix"] : "All Classes";