<?php

/**
 * Updates the vote count for the link based on the delta (+1 or -1) provided
 * @param $conn object the open database connection
 * @param $id string the link's id
 * @param $delta int the amount the vote should be changed by (usually 1 or -1)
 */
function executeVote($conn, $id, $delta) {
    $vote = $conn->prepare("SELECT `score` FROM `links` WHERE `link_id` = :id");
    $vote->bindParam(':id', $id);
    $vote->execute();

    $score = ($vote->fetch(PDO::FETCH_ASSOC));
    $score = $score["score"] + $delta;

    $query = $conn->prepare("UPDATE `links` SET `score` =  :score WHERE `link_id` = :id");
    $query->bindParam(':score', $score);
    $query->bindParam(':id', $id);
    $query->execute();
}

function executeAdd($conn, $url, $classForAdd, $linkName) {
    try {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            // Obtained from http://stackoverflow.com/questions/2762061/how-to-add-http-if-its-not-exists-in-the-url
            $url = "http://" . $url;
        }

        $query = $conn->prepare("INSERT INTO `links` (`link`, `rcs_id`, `category_id`, `creation_date`, `title`) " .
            "VALUES (:url, :rcs, :classForAdd, CURDATE(), :linkName);");
        $query->bindParam(':url', $url);
        $query->bindParam(':rcs', phpCAS::getUser());
        $query->bindParam(':classForAdd', $classForAdd);
        $query->bindParam(':linkName', $linkName);
        $status = $query->execute();

        if ($status) {
            return array("success", "Your new link, entitled $linkName, was successfully added!");
        } else {
            return array("error", "Oh no! The request failed!");
        }
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

function executeEdit($conn, $url, $classForAdd, $linkName, $id) {
    try {
        $query = $conn->prepare("UPDATE `links` SET `title` = :linkName, `link` = :url, `category_id` = :classForAdd WHERE `link_id` = :id");
        $query->bindParam(':linkName', $linkName);
        $query->bindParam(':url', $url);
        $query->bindParam(':classForAdd', $classForAdd);
        $query->bindParam(':id', $id);
        $status = $query->execute();

        if ($status) {
            return array("success", "The link, entitled $linkName, was successfully edited!");
        } else {
            return array("error", "Oh no! The request failed!");
        }
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

function executeDelete($conn, $linkId) {
    try {
        $query = $conn->prepare("DELETE FROM `links` WHERE `link_id` = :linkId");
        $query->bindParam(':linkId', $linkId);
        $status = $query->execute();

        if ($status) {
            return array("success", "The link was successfully deleted!");
        } else {
            return array("error", "Oh no! The request failed!");
        }
    } catch (PDOException $e) {
        return array("error", $e);
    }
}

function determineClassFilter($conn, $class) {
    try {
        if ($class != "") {
            $query = $conn->prepare("SELECT `title` FROM `categories` WHERE `category_id` = :id");
            $query->bindParam(':id', $class);
            $query->execute();

            if ($query->rowCount() == 0) {
                // Category ID not valid
                header('location: ./links.php');
            }

            $result = $query->fetch(PDO::FETCH_ASSOC);
            return $result["title"];
        }
    } catch (PDOException $e) {
        echo $e;
    }
}

function populateAddButton($id) {
    echo "<a href='addlink.php?";
    if ($id != "") {
        echo "class=$id";
    }
    echo "' class='btn btn-primary pull-right'><span class='fa fa-plus'></span> Add a link</a>";
}

function populateBackButtonText($id) {
    if ($id != "") {
        echo "<span class=\"fa fa-chevron-left\"></span> Back to Classes";
    } else {
        echo "View Classes";
    }
}

function populateData($conn, $dataReq, $isAdmin) {
    try {
        $count = 0;
        if (isset($_GET["page"])) {
            $p = $_GET["page"];
        } else {
            $p = 1;
        }

        echo "<div class='row'>";
        $data = $dataReq->fetchAll(PDO::FETCH_ASSOC);

        foreach ($data as $result) {
            if ($count >= ($p - 1) * 16 && $count < $p * 16) {
                $categoryHTML = "<h6 class='text-muted truncate'>";
                if (!isset($_GET["class"])) {
                    $var = $conn->prepare("SELECT `title`,`prefix` FROM `categories` WHERE `category_id` = :id");
                    $var->bindParam(':id', $result["category_id"]);
                    $var->execute();
                    $r2 = $var->fetch(PDO::FETCH_ASSOC);
                    $categoryHTML .= $r2['title'] . " (" . $r2['prefix'] . ")";
                } else {
                    $categoryHTML .= "Link";
                }
                $categoryHTML .= "</h6>";

                echo "<div class='col-md-3'>
                        <a href='" . $result["link"] . "' target=\"_blank\">
                            <div class='well well-sm well-hover'>"
                    . $categoryHTML
                    . "<h4 class='truncate'>" . $result["title"] . "</h4>"
                    . "<p class='text-muted small'>
                                <span class='pull-left'>
                                    submitted by " . $result["rcs_id"] .
                    "</span>
                                <span class='pull-right'>" . $result["creation_date"] . "</span>
                                <span class='clearfix'></span>
                               </p>
                           </div>
                         </a>";

                //for getting back to the same page you started on
                $class = "?";
                if (isset($_GET["class"])) {
                    $class .= "class=" . $_GET["class"];
                }
                $page = $class;
                if (isset($_GET["page"])) {
                    if ($class == "?") {
                        $page = "?page=" . $_GET["page"];
                    } else {
                        $page = $class . "&page=" . $_GET["page"];
                    }
                }

                //for upvote and downvote
                echo "<div class='row'><div class=\"col-xs-12 text-center\">";
                echo "<form class='admin-panel' method=\"post\" action='links.php" . $page . "' class=\"form-inline\">";
                echo "<button type=\"submit\" class=\"btn btn-default pull-left\" name=\"downvote\" value=" . $result["link_id"] . ">
                <span class='fa fa-thumbs-down'</span></button>";
                echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"upvote\" value=" . $result["link_id"] . ">
                <span class='fa fa-thumbs-up'</span></button></form>";
                echo "<span class=\"text-center\"><a class='btn btn-default disabled'>" . $result["score"] . "</a></span>";
                echo "</div></div>";

                //if the user is an administrator, they can delete and edit
                if ($isAdmin || $result["rcs_id"] == phpCAS::getUser()) {
                    //delete button
                    echo "<form class='admin-panel delete-form' method=\"post\" action='links.php$class' class=\"form-horizontal\">";
                    echo "<button type=\"submit\" class=\"btn btn-default pull-right\" name=\"delete\" value=" . $result["link_id"] . ">Delete</button></form>";

                    //edit button
                    echo "<form class='admin-panel' method=\"post\" action='editlink.php" . $page . "' class=\"form-horizontal\">";
                    echo "<button type=\"submit\" class=\"btn btn-default pull-left\" name=\"edit\" value=" . $result["link_id"] . ">Edit</button></form>";
                }
                echo "</div>";
            }

            $count++;
        }

        if ($count == 0) {
            echo "<div class='row'><div class='col-xs-12'>" . infoAlert("No links. You should add one.") . "</div></div>";
        }
    } catch (PDOException $e) {
        echo $e;
    }

    if (($count / 16) + 1 >= 2) {
        echo "<div class=\"clearfix\"></div><div class=\"col-xs-12 centered\"><hr/><div class=\"btn-group\">";

        for ($button = 1; $button < ($count / 16) + 1; $button++) {
            $link = "?";
            if (isset($_GET["class"])) {
                $link = $link . "class=" . $_GET["class"] . "&";
            }
            $link .= "page=$button";
            echo "<a href=\"$link\" class=\"btn btn-primary\">$button</a>";
        }
        echo "</div></div>";
    }
    echo "</div>";
}

forceAuth();

// Establish a connection to the database for this page.
$conn = new PDO('mysql:host=localhost;dbname=slatecrate', $config['DB_USERNAME'], $config['DB_PASSWORD']);

$isAdmin = determineAdminStatus($conn, phpCAS::getUser());

$categoryTitle = determineClassFilter($conn, (isset($_GET["class"]) ? $_GET["class"] : ""));

$pageHeader = "Links for " . (isset($_GET["class"]) ? $categoryTitle : "all classes");

//up or downvote
if (isset($_POST["upvote"])) {
    executeVote($conn, $_POST["upvote"], 1);
} else if (isset($_POST["downvote"])) {
    executeVote($conn, $_POST["downvote"], -1);
} else if (isset($_POST["user"])) {
    // add class if we need to
    $alertArray = executeAdd($conn, $_POST["URL"], $_POST["classForAdd"], $_POST["linkName"]);
} else if (isset($_POST["delete"])) {
    $alertArray = executeDelete($conn, $_POST["delete"]);
} else if (isset($_POST["edit"])) {
    $alertArray = executeEdit($conn, $_POST["URL"], $_POST["classForAdd"], $_POST["linkName"], $_POST["edit"]);
}

if (isset($alertArray)) {
    $alertType = $alertArray[0];
    $alertMessage = $alertArray[1];
} else {
    $alertMessage = "";
    $alertType = "";
}

//sort
$sort = isset($_POST["sort"]) ? $_POST["sort"] : "`score` DESC";

try {
    $search = "";
    if (isset($_POST["srch"])) {
        $search = '%' . $_POST["srch"] . '%';
    } else {
        $search = '%';
    }

    if (isset($_GET["class"])) {
        $c = $_GET["class"];
        $query = $conn->prepare("SELECT * FROM `links` WHERE `category_id` = :id AND `title` LIKE :search ORDER BY " . $sort);
        $query->bindParam(':id', $c);
        $query->bindParam(':search', $search);
        $query->execute();
    } else {
        $query = $conn->prepare("SELECT * FROM `links` WHERE `title` LIKE :search ORDER BY " . $sort);
        $query->bindParam(':search', $search);
        $query->execute();
    }
} catch (PDOException $e) {
    echo $e;
}