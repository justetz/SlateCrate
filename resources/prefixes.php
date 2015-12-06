<?php

/**
 * This array contains all valid prefixes at RPI. These
 * values will be used to populate the sidebar of the page.
 * @var array
 */
$prefixes = json_decode(file_get_contents("resources/prefixes.json"));

/**
 * Checks the provided prefix and returns the needed HTML code to mark the list
 * item as active, only if there is a prefix passed to the page and it matches
 * the parameter
 * @param string $currentPrefix
 * @param string $prefixToCheck
 * @return string
 */
function determineIfActivePrefix($currentPrefix, $prefixToCheck) {

    if($currentPrefix == $prefixToCheck) {
        return "class='active'";
    }
    return "";
}