<?php
require 'resources/functions.php';
require 'resources/config.php';
require 'resources/rpiCAS.php';

//$conn = connect($config['DB_HOST'], $config['DB_USERNAME'],
  //              $config['DB_PASSWORD'], 'slatecratedb');

//$results = query('SELECT * FROM customers', $conn);

?>
<!DOCTYPE html>
<html lang="en">
<?php require 'partials/head.partial.php'; ?>
<body>
<?php require 'partials/navigation.partial.php'; ?>
<!-- *****************************************************************************************************************
 HEADERWRAP
 ***************************************************************************************************************** -->
<div id="headerwrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
                <h1>Find useful, online resources</h1>
                <h3>A link database created for RPI students, by RPI students</h3>
                <?php
                if (phpCAS::isAuthenticated()) {
                    echo "<p><a class='btn btn-outline btn-lg' href='classes.php'>View classes</a></p>";
                } else {
                    echo "<p><a class='btn btn-outline btn-lg' href='login.php'>Sign in with RCS</a></p>";
                }
                ?>

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

                <p>Made for RPI Students by RPI Students. 
                We wanted a tool that could help us in our classes,
                 so we shared it with you: the student body! 
                </p>

                
            </div>
            <div class="col-md-4">
                <i class="fa fa-check-circle"></i>
                <h4>Quality assured</h4>

                <p>Access requires an RPI login so that the community can stay close knit and relevant. Information for your classes will be provided by people
                 currently enrolled in, interested in, or graduated from your course.
                </p>
            </div>
        </div>
    </div>
    <!--/container -->
</div>
<!--/service -->

<?php require 'partials/footer.partial.php'; ?>

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
