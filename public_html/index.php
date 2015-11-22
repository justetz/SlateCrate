<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.ico">

    <base href="public_html/">

    <title>Home | SlateCrate</title>

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

    <script src="assets/js/modernizr.js"></script>
</head>

<body>

<? require 'navbar.php'; ?>

<!-- *****************************************************************************************************************
 HEADERWRAP
 ***************************************************************************************************************** -->
<div id="headerwrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h1>Find useful, online resources</h1>
                <h3>A link database created by RPI students, for RPI students</h3>
                <p><a class="btn btn-outline btn-lg">Sign up with CAS</a></p>
            </div>
            <!--<div class="col-lg-8 col-lg-offset-2 himg">-->
                <!--<img src="assets/img/browser.png" class="img-responsive">-->
            <!--</div>-->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /headerwrap -->

<!-- *****************************************************************************************************************
 SERVICE LOGOS
 ***************************************************************************************************************** -->
<div id="service">
    <div class="container">
        <div class="row centered">
            <div class="col-md-4">
                <i class="fa fa-users"></i>
                <h4>Crowd sourced</h4>

                <p>
                    Who knows a field of study best? Those that are regularly involved in it! Then, what better way to
                    find quality content than what peers have recommended?
                </p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-graduation-cap text-rpi"></i>
                <h4>RPI in mind</h4>

                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book.</p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-check-circle"></i>
                <h4>Quality assured</h4>

                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book.</p>
            </div>
        </div>
    </div>
    <! --/container -->
</div>
<! --/service -->


<!-- *****************************************************************************************************************
 FOOTER
 ***************************************************************************************************************** -->
<div id="footerwrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <h4>About</h4>

                <div class="hline-w"></div>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s.</p>
            </div>
            <div class="col-lg-4">
                <h4>Social Links</h4>

                <div class="hline-w"></div>
                <p>
                    <a href="#"><i class="fa fa-dribbble"></i></a>
                    <a href="#"><i class="fa fa-facebook"></i></a>
                    <a href="#"><i class="fa fa-twitter"></i></a>
                    <a href="#"><i class="fa fa-instagram"></i></a>
                    <a href="#"><i class="fa fa-tumblr"></i></a>
                </p>
            </div>
            <div class="col-lg-4">
                <h4>Our Bunker</h4>

                <div class="hline-w"></div>
                <p>
                    Some Ave, 987,<br/>
                    23890, New York,<br/>
                    United States.<br/>
                </p>
            </div>

        </div>
        <! --/row -->
    </div>
    <! --/container -->
</div>
<! --/footerwrap -->

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/retina-1.1.0.js"></script>
<script src="assets/js/jquery.hoverdir.js"></script>
<script src="assets/js/jquery.hoverex.min.js"></script>
<script src="assets/js/jquery.prettyPhoto.js"></script>
<script src="assets/js/jquery.isotope.min.js"></script>
<script src="assets/js/custom.js"></script>


<script>
    // Portfolio
    (function ($) {
        "use strict";
        var $container = $('.portfolio'),
                $items = $container.find('.portfolio-item'),
                portfolioLayout = 'fitRows';

        if ($container.hasClass('portfolio-centered')) {
            portfolioLayout = 'masonry';
        }

        $container.isotope({
            filter: '*',
            animationEngine: 'best-available',
            layoutMode: portfolioLayout,
            animationOptions: {
                duration: 750,
                easing: 'linear',
                queue: false
            },
            masonry: {}
        }, refreshWaypoints());

        function refreshWaypoints() {
            setTimeout(function () {
            }, 1000);
        }

        $('nav.portfolio-filter ul a').on('click', function () {
            var selector = $(this).attr('data-filter');
            $container.isotope({filter: selector}, refreshWaypoints());
            $('nav.portfolio-filter ul a').removeClass('active');
            $(this).addClass('active');
            return false;
        });

        function getColumnNumber() {
            var winWidth = $(window).width(),
                    columnNumber = 1;

            if (winWidth > 1200) {
                columnNumber = 5;
            } else if (winWidth > 950) {
                columnNumber = 4;
            } else if (winWidth > 600) {
                columnNumber = 3;
            } else if (winWidth > 400) {
                columnNumber = 2;
            } else if (winWidth > 250) {
                columnNumber = 1;
            }
            return columnNumber;
        }

        function setColumns() {
            var winWidth = $(window).width(),
                    columnNumber = getColumnNumber(),
                    itemWidth = Math.floor(winWidth / columnNumber);

            $container.find('.portfolio-item').each(function () {
                $(this).css({
                    width: itemWidth + 'px'
                });
            });
        }

        function setPortfolio() {
            setColumns();
            $container.isotope('reLayout');
        }

        $container.imagesLoaded(function () {
            setPortfolio();
        });

        $(window).on('resize', function () {
            setPortfolio();
        });
    })(jQuery);
</script>
</body>
</html>
