<?php

function activeClassIfRequestMatches($requestUri) {
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");

    if ($current_file_name == $requestUri)
        return 'class="active"';
}

?>

<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
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
                <li <?=activeClassIfRequestMatches("index")?>><a href="index.php">HOME</a></li>
                <li <?=activeClassIfRequestMatches("posts")?>><a href="posts.php">POSTS</a></li>

                <?php
                if (phpCAS::isAuthenticated()) {
                    echo "<li ".activeClassIfRequestMatches("newpost")."><a href='newpost.php'>ADD POST</a></li>";
                    echo "<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown'>".phpCAS::getUser()."<b class='caret'></b></a>
                            <ul class='dropdown-menu'>
                                <li><a href='single-project.php'>ADD A LINK</a></li>
                                <li><a href='logout.php'>SIGN OUT</a></li>
                            </ul>
                          </li>";
                }else{
                    echo "<li><a href='login.php'>SIGN IN</a></li>";
                }
                ?>


            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>