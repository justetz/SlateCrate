<?php
    function echoPageTitle() {
        $url=strtok($_SERVER["REQUEST_URI"],'?');
        switch(basename($url, ".php")) {
            case "index":
                echo "Home";
                break;
            case "slatecrate":
                echo "Home";
                break;
            case "newpost":
                echo "New Post";
                break;
            default:
                echo ucfirst(basename($url, ".php"));
                break;
        }
    }
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">


    <title><?php echoPageTitle(); ?> | SlateCrate</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>