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