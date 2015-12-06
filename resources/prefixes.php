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
function determineIfActivePrefix($currentPrefix, $prefixToCheck)
{
    if ($currentPrefix == $prefixToCheck) {
        return "class='active'";
    }
    return "";
}

/**
 * Generates <option> elements containing all valid prefixes. Can pass in an existing value to have a selected
 * <option>, assuming the existing value is valid.
 * @param $prefixes
 * @param $currentPrefix
 */
function populatePrefixSelect($prefixes, $currentPrefix)
{
    foreach ($prefixes as $p) {
        // Add another item to the list, calling the function
        // 'determineIfActive' to determine if the active class
        // should be included in the item
        if ($p != $currentPrefix) {
            echo "<option value='" . $p . "'>" . $p . "</option>";
        } else {
            echo "<option value='" . $p . "' selected>" . $p . "</option>";
        }
        echo "<option value='" . $p . "'>" . $p . "</option>";
    }
}