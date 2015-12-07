<?php

/**
 * Determines if the url is on the same page as what's provided. For example, if
 * the URL ends with index.php and the user passes in 'index', it will return
 * class active. Otherwise, it returns a blank string.
 * @param $requestUri
 * @return string
 */
function activeClassIfRequestMatches($requestUri) {
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        return 'class="active"';

    return "";
}

?>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">
                <span class="fa fa-th"></span>
                SlateCrate
            </a>
        </div>
        <div class="navbar-collapse collapse navbar-right">
            <ul class="nav navbar-nav">
                <!-- Each link checks to see if it should have an active class -->
                <li <?= activeClassIfRequestMatches("index") ?>><a
                        href="index.php">HOME</a></li>
                <li <?= activeClassIfRequestMatches("classes") ?>><a
                        href="classes.php">CLASSES</a></li>
                <li <?= activeClassIfRequestMatches("links") ?>><a
                        href='links.php'>LINKS</a></li>

                <?php
                if (phpCAS::isAuthenticated()) {
                    // If the user has already signed in, show a dropdown menu
                    echo "<li class='dropdown'>";
                    echo "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>" . phpCAS::getUser() . "<b class='caret'></b></a>";
                    echo "<ul class='dropdown-menu'>";
                    echo "<li><a href='addclass.php'>ADD A CLASS</a></li>";
                    echo "<li><a href='addlink.php'>ADD A LINK</a></li>";
                    echo "<li><a href='logout.php'>SIGN OUT</a></li>";
                    echo "</ul>";
                    echo "</li>";
                } else {
                    // Otherwise, show the user the sign in option
                    echo "<li><a href='login.php'>SIGN IN</a></li>";
                }
                ?>
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>
