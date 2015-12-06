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

function populatePagination($count, $prefix, $currentPage) {
    if(($count / 19) + 1 >= 2) {
        echo ($count / 18) + 1;
        echo "<div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";

        $link = "?";
        if ($prefix != "") {
            $link .= "prefix=" . $prefix . "&";
        }

        echo "<a ";
        if($currentPage > 1) {
            echo "href=\"".$link."page=".($currentPage-1)."\"";
        } else {
            echo "disabled";
        }
        echo " class=\"btn btn-primary\"><span class='fa fa-chevron-left'></span></a>";

        for ($button = 1; $button < ($count / 18) + 1; $button++) {
            echo "<a href=\"".$link."page=$button\" class=\"btn btn-primary";
            if(intval($button) == intval($currentPage)) {
                echo " active";
            }
            echo "\">$button</a>";
        }

        echo "<a ";
        if($currentPage < ($count / 18)) {
            echo "href=\"".$link."page=".($currentPage+1)."\"";
        } else {
            echo "disabled";
        }
        echo " class=\"btn btn-primary\"><span class='fa fa-chevron-right'></span></a>";

        echo "</div></div>";
    }
}

function populateData($conn, $prefix, $search, $sort, $page, $isAdmin) {
    try{
        if($prefix != ""){
            $p = "'" . $prefix . "'";
            $var = $conn->prepare("SELECT * FROM `categories` WHERE `prefix` = $p AND `title` LIKE '%$search%' ORDER BY $sort");
            $count = $conn->query("SELECT COUNT(`title`) FROM `categories` WHERE `prefix` = $p AND `title` LIKE '%$search%'")->fetchColumn();
        }else{
            $var = $conn->prepare("SELECT * FROM `categories` WHERE `title` LIKE '%$search%' ORDER BY $sort");
            $count = $conn->query("SELECT COUNT(`title`) FROM `categories` WHERE `title` LIKE '%$search%'")->fetchColumn();
        }
        if($count == NULL){ $count = 0; }
        $var->execute();

        $c = 0;
        $p = $page != "" ? intval($page) : 1;

        echo "<div class='row'>";
        while($result = $var->fetch(PDO::FETCH_ASSOC)){
            if($c >= ($p - 1) * 18 && $c < $p * 18){

                //to get the number of links
                $l = $conn->query("SELECT COUNT(link_id) FROM `links` WHERE `category_id` = '" . $result["category_id"] . "'")->fetchColumn();
                if($l == NULL){ $l = 0; }

                //print out the boxes
                echo "<div class='col-md-6'>
								<a href='links.php?class=".$result["category_id"]."''>
									<div class='well well-sm well-hover'>
    								<h6 class='text-muted'>".$result["prefix"]."</h6>
    								<h4>".$result["title"]."</h4>
    								<p>Contains ".$l.($l > 1 ? " links" : " link").".</p>
    								<p class='text-muted small info-text'>
    									<span class='pull-left'>submitted by ".$result["rcs_id"]."</span>
    									<span class='pull-right'>".$result["creation_date"]."</span>";
                echo "<span class='clearfix'></span></p></div></a>";

                if($isAdmin || $result["rcs_id"] == phpCAS::getUser()) {
                    echo "<form method=\"post\" action='classes.php";
                    if($prefix != ""){
                        echo "?prefix=".$prefix;
                    }
                    echo "' class=\"admin-panel delete-form\">";
                    echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                    echo "<form method=\"post\" action='editclass.php' class=\"admin-panel\">";
                    echo "<button type=\"submit\" class=\"btn btn-default\" name=\"edit\" value=" . $result["category_id"] . ">Edit</button></form>";
                }
                echo "</div>";
            }else if($c >= $p * 18){ break; }
            ++$c;
        }

        if($c == 0){
            echo "<div class='col-xs-12'>" . infoAlert("No classes. You should add one!") . "</div>";
        }
    }catch(PDOException $e){ echo $e; }

    populatePagination($count, $prefix, $p);
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