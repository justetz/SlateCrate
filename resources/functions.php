<?php

require 'config.php';

function determineAdminStatus($conn, $userId)
{
    //Check if current user is an admin
    $admin = $conn->prepare("SELECT `isadmin` FROM `users` WHERE `rcs_id` = '" . $userId . "'");
    $admin->execute();
    $isadmin = false;
    while ($result = $admin->fetch(PDO::FETCH_ASSOC)) {
        if ($result["isadmin"] == 1) {
            $isadmin = true;
        }
    }
    return $isadmin;
}

function forceAuth()
{
    if (!phpCAS::isAuthenticated()) {
        // If they're not currently logged in, take them to the RPI CAS page
        phpCAS::forceAuthentication();
    }
}

function echoClassForFilterButtons($currentSort, $toCompare) {
    echo "btn btn-default" . ($currentSort == $toCompare ? " active" : "");
}

function alert($lead, $message, $css, $isDismissable)
{
    $alertHTML = "<div class='alert $css";
    if ($isDismissable) {
        $alertHTML .= " alert-dismissible' role='alert'>
		   <button type='button' class='close' data-dismiss='alert' aria-label='Close><span aria-hidden='true'>&times;</span></button>";
    } else {
        $alertHTML .= "' role='alert'>";
    }

    if ($lead != "") {
        $alertHTML .= "<strong>$lead</strong> ";
    }
    $alertHTML .= $message;
    $alertHTML .= "</div>";
    return $alertHTML;
}

function successAlert($message)
{
    return alert("Success!", $message, "alert-success", true);
}

function errorAlert($message)
{
    return alert("Error!", $message, "alert-danger", true);
}

function infoAlert($message)
{
    return alert("", $message, "alert-info", false);
}

function populateAlertRow($alertType, $alertMessage)
{
    if ($alertType != "") {
        echo "<div class='row'><div class='col-xs-12'>";
        if ($alertType == "success") {
            echo successAlert($alertMessage);
        } else if ($alertType == "error") {
            echo errorAlert($alertMessage);
        }
        echo "</div></div>";
    }
}