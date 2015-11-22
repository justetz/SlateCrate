<?php

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

