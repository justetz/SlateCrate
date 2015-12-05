<?php

/**
 * This array contains all valid prefixes at RPI. These
 * values will be used to populate the sidebar of the page.
 * @var array
 */
$prefixes = [
    "ARCH", "ARTS", "ASTR", "BCBP", "BIOL", "BMED", "CHEM",
    "CISH", "CSCI", "DSES", "ECON", "ECSE", "ENGR", "ENVE",
    "ERTH", "ESCE", "IENV", "IHSS", "ISCI", "ITEC", "LANG",
    "LGHT", "LITR", "MANE", "MATH", "MATP", "MGMT", "MTLE",
    "PHIL", "PHYS", "PSYC", "STSH", "STSS", "USAF", "USAR",
    "USNA", "WRIT"
];

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