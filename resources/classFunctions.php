<?php
/**
 * This function completes the edit action that is initiated when the classes.php page is loaded by the submission of
 * the editclass.php form.
 * @param $conn object the opened database connection
 * @param $newTitle string the class's new title (same as old if unchanged)
 * @param $newPrefix string the class's new prefix (same as old if unchanged)
 * @param $id string the class's id (primary key, cannot be changed)
 * @return array string containing whether a success or error alert should be displayed and the message to display in
 *               the alert
 */
function executeEdit($conn, $newTitle, $newPrefix, $id)
{
    try {
        $conn->query("UPDATE `categories` SET `title` = '" . $newTitle . "', `prefix` = '" . $newPrefix . "' WHERE `category_id` = " . $id);

        return array("success", $newTitle . " was successfully edited!");
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

/**
 * This function completes the add action that is initiated when the classes.php page is loaded by the submission of
 * the addclass.php form.
 * @param $conn object the opened database connection
 * @param $className string the name of the class
 * @param $inputCategory string the new class's prefix (what department the class is in)
 * @param $user string the creator's RCS ID
 * @return array string containing whether a success or error alert should be displayed and the message to display in
 *               the alert
 */
function executeAdd($conn, $className, $inputCategory, $user)
{
    try {
        $string = "'" . $className . "', '" . $inputCategory . "', '" . $user . "', " . "CURDATE()";

        $conn->query("INSERT INTO `categories` (`title`, `prefix`, `rcs_id`, `creation_date`) VALUES (" . $string . ");");

        return array("success", "Your new class, entitled " . $_POST["className"] . ", was successfully added!");
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

/**
 * This function completes the delete action that is initiated when the classes.php page is loaded by any delete button
 * shown on the classes.php page.
 * @param $conn object the opened database connection
 * @param $categoryId string the unique identifier for the class being deleted
 * @return array string containing whether a success or error alert should be displayed and the message to display in
 *               the alert
 */
function executeDelete($conn, $categoryId)
{
    try {
        $del = $conn->prepare("SELECT `link_id` FROM `links` WHERE `category_id` = " . $categoryId);
        $del->execute();
        while ($result = $del->fetch(PDO::FETCH_ASSOC)) {
            $conn->query("DELETE FROM `links` WHERE `link_id` = " . $result["link_id"]);
        }
        $conn->query("DELETE FROM `categories` WHERE `category_id` = " . $categoryId);

        return array("success", "The class, and its links, have been deleted!");
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

/**
 * Using the list of prefixes defined in the resources/prefixes.json file, this function populates the HTML used by the
 * nav-pills sidebar found on the classes.php page. This function takes into account what prefix is currently selected
 * and appropriately adds an active class to the active item.
 * @param $currentPrefix string the current prefix in the '?class=' portion of the URI. If a class is not selected, an
 *                       empty string should be passed in.
 */
function populateSidebar($currentPrefix)
{
    echo "<li role='presentation'";
    if ($currentPrefix == "") {
        echo " class='active'";
    }
    echo "><a href='classes.php'>All Prefixes</a></li>";

    require_once 'resources/prefixes.php';

    foreach ($prefixes as $p) {
        // Add another item to the list, calling the function
        // 'determineIfActive' to determine if the active class
        // should be included in the item
        echo "<li role='presentation' " . determineIfActivePrefix($currentPrefix, $p)
            . "><a href='?prefix=" . $p . "'>" . $p . "</a></li>";
    }
}

/**
 * This function, given a total count of classes, the currently selected prefix (empty string if none), and the current
 * page currently displayed, generates a button group that appropriately displays pagination buttons.
 * @param $count int number of pages
 * @param $prefix string the currently selected prefix, empty string if none.
 * @param $currentPage int the currently visible page
 */
function populatePagination($count, $prefix, $currentPage)
{
    if (($count / 19) + 1 >= 2) {
        echo "<div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";

        $link = "?";
        if ($prefix != "") {
            $link .= "prefix=" . $prefix . "&";
        }

        echo "<a ";
        if ($currentPage > 1) {
            echo "href=\"" . $link . "page=" . ($currentPage - 1) . "\"";
        } else {
            echo "disabled";
        }
        echo " class=\"btn btn-primary\"><span class='fa fa-chevron-left'></span></a>";

        for ($button = 1; $button < ($count / 18) + 1; $button++) {
            echo "<a href=\"" . $link . "page=$button\" class=\"btn btn-primary";
            if (intval($button) == intval($currentPage)) {
                echo " active";
            }
            echo "\">$button</a>";
        }

        echo "<a ";
        if ($currentPage < ($count / 18)) {
            echo "href=\"" . $link . "page=" . ($currentPage + 1) . "\"";
        } else {
            echo "disabled";
        }
        echo " class=\"btn btn-primary\"><span class='fa fa-chevron-right'></span></a>";

        echo "</div></div>";
    }
}

/**
 * This function populates the main content of the classes.php: the classes. Each class is displayed as a well HTML
 * element. Additionally, this function calls populatePagination.
 * @param $conn object the opened database connection
 * @param $prefix string the currently selected prefix
 * @param $search string the search query
 * @param $sort string the currently selected sort parameter
 * @param $page int the page to display
 * @param $isAdmin boolean whether or not the logged-in user is an administrator
 */
function populateData($conn, $prefix, $search, $sort, $page, $isAdmin)
{
    try {
        if ($prefix != "") {
            $p = "'" . $prefix . "'";
            $var = $conn->prepare("SELECT * FROM `categories` WHERE `prefix` = $p AND `title` LIKE '%$search%' ORDER BY $sort");
            $count = $conn->query("SELECT COUNT(`title`) FROM `categories` WHERE `prefix` = $p AND `title` LIKE '%$search%'")->fetchColumn();
        } else {
            $var = $conn->prepare("SELECT * FROM `categories` WHERE `title` LIKE '%$search%' ORDER BY $sort");
            $count = $conn->query("SELECT COUNT(`title`) FROM `categories` WHERE `title` LIKE '%$search%'")->fetchColumn();
        }
        if ($count == NULL) {
            $count = 0;
        }
        $var->execute();

        $c = 0;
        $p = $page != "" ? intval($page) : 1;

        echo "<div class='row'>";
        while ($result = $var->fetch(PDO::FETCH_ASSOC)) {
            if ($c >= ($p - 1) * 18 && $c < $p * 18) {

                //to get the number of links
                $l = $conn->query("SELECT COUNT(link_id) FROM `links` WHERE `category_id` = '" . $result["category_id"] . "'")->fetchColumn();
                if ($l == NULL) {
                    $l = 0;
                }

                //print out the boxes
                echo "<div class='col-md-6'><a href='links.php?class=" . $result["category_id"] . "''>";
			    echo "<div class='well well-sm well-hover'>";

                // display the prefix for the class
    			echo "<h6 class='text-muted'>" . $result["prefix"] . "</h6>";

                // class title
    			echo "<h4>" . $result["title"] . "</h4>";

                // number of links
    			echo "<p>Contains " . $l . ($l != 1 ? " links" : " link") . ".</p>";

                // metadata
    			echo "<p class='text-muted small info-text'>";

                // submitted by
    			echo "<span class='pull-left'>submitted by " . $result["rcs_id"] . "</span>";

                // creation date
    			echo "<span class='pull-right'>" . $result["creation_date"] . "</span>";

                // fix the floats and close
                echo "<span class='clearfix'></span>";
                echo "</p></div></a>";

                // Determine whether or not to show the edit and delete buttons
                // Only show for admins or if the user created the class
                if ($isAdmin || $result["rcs_id"] == phpCAS::getUser()) {
                    echo "<form method=\"post\" action='classes.php";
                    if ($prefix != "") {
                        echo "?prefix=" . $prefix;
                    }
                    echo "' class=\"admin-panel delete-form\">";
                    echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"delete\" value=" . $result["category_id"] . ">Delete</button></form>";
                    echo "<form method=\"post\" action='editclass.php' class=\"admin-panel\">";
                    echo "<button type=\"submit\" class=\"btn btn-default\" name=\"edit\" value=" . $result["category_id"] . ">Edit</button></form>";
                }
                echo "</div>";
            } else if ($c >= $p * 18) {
                break;
            }
            ++$c;
        }

        if ($c == 0) {
            echo "<div class='col-xs-12'>" . infoAlert("No classes. You should add one!") . "</div>";
        }
    } catch (PDOException $e) {
        echo $e;
    }

    populatePagination($count, $prefix, $p);
}

/**
 * Initialization code:
 * This code is run at the load of classes.php, which is why the code is standalone, as opposed to wrapped in a funcion.
 */

// Forces the user to be authorized by CAS before displaying the page.
forceAuth();

// Connect to the database
$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

// Determine if the user has admin status
$isAdmin = determineAdminStatus($conn, phpCAS::getUser());

/**
 * Submitted actions: these are called when classes.php is loaded as an action of HTML forms from classes.php,
 * addclasses.php, and editclasses.php.
 */
if (isset($_POST["edit"])) {
    // Update an edited class with new details
    $alertArray = executeEdit($conn, $_POST["className"], $_POST["inputCategory"], $_POST["edit"]);
} else if (isset($_POST["user"])) {
    // Create a newly added class with the new details
    $alertArray = executeAdd($conn, $_POST["className"], $_POST["inputCategory"], $_POST["user"]);
} else if (isset($_POST["delete"])) {
    // Complete the deletion of a class
    $alertArray = executeDelete($conn, $_POST["delete"]);
}

/**
 * Uses the responses of executeEdit, executeAdd, and executeDelete to generate appropriate alerts as necessary.
 */
if (isset($alertArray)) {
    $alertType = $alertArray[0];
    $alertMessage = $alertArray[1];
} else {
    $alertMessage = "";
    $alertType = "";
}

// Fulfills the search and sort variables based on what's submitted or using default values.
$search = isset($_POST["srch"]) ? $_POST["srch"] : "";
$sort = isset($_POST["sort"]) ? $_POST["sort"] : "`title`";

// Set the page heading appropriately, depending on if the url specifies a prefix
$pageHeader = isset($_GET["prefix"]) ? "Classes for " . $_GET["prefix"] : "All Classes";