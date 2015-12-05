<?php

require 'config.php';

/**
 * Connects to a MySQL database using the given parameters.
 * @param string $host
 * @param $username
 * @param $password
 * @param null $db
 * @return resource
 */
function connect($host = 'localhost', $username, $password, $db = null) {
	$conn = mysql_connect($host, $username, $password);

	// if (!$conn) die('Could not connect.');

	if ($db) {
		mysql_select_db($db, $conn);
	}

	return $conn;
}

/**
 * Executes a given query using a given database connection
 * @param $query string desired query to be executed
 * @param $conn object the database connection to be used
 * @return array|bool either a false for failure, or the result of the query
 */
function query($query, $conn) {
	$results = mysql_query($query, $conn);

	if ( $results ) {
		$rows = array();
		while($row = mysql_fetch_object($results)) {
			$rows[] = $row;
		}
		return $rows;
	}

	return false;
}

function alert($lead, $message, $css, $isDismissable) {
	$alertHTML = "<div class='alert $css";
	if($isDismissable) {
		$alertHTML .= " alert-dismissible' role='alert'>
		   <button type='button' class='close' data-dismiss='alert' aria-label='Close><span aria-hidden='true'>&times;</span></button>";
	} else {
		$alertHTML .= "' role='alert'>";
	}

	if($lead != "") {
		$alertHTML .= "<strong>$lead</strong> ";
	}
	$alertHTML .= $message;
	$alertHTML .= "</div>";
	return $alertHTML;
}

function successAlert($message) {
	return alert("Success!", $message, "alert-success", true);
}

function errorAlert($message) {
	return alert("Error!", $message, "alert-danger", true);
}

function infoAlert($message) {
	return alert("", $message, "alert-info", false);
}

function populateAlertRow($alertType, $alertMessage) {
	if($alertType != "") {
		echo "<div class='row'><div class='col-xs-12'>";
		if ($alertType == "success") {
			echo successAlert($alertMessage);
		} else if ($alertType == "error") {
			echo errorAlert($alertMessage);
		}
		echo "</div></div>";
	}
}

function determineAdminStatus($conn, $userId) {
	//Check if current user is an admin
	$admin = $conn->prepare("SELECT `isadmin` FROM `users` WHERE `rcs_id` = '" . $userId . "'");
	$admin->execute();
	$isadmin = false;
	while($result = $admin->fetch(PDO::FETCH_ASSOC)){
		if($result["isadmin"] == 1){ $isadmin = true; }
	}
	return $isadmin;
}
