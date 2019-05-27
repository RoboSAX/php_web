<?php
require_once(__DIR__."/content.inc.php");
define("CONTENT_DIR","content"); // without "./"!
include_once(__DIR__."/".CONTENT_DIR."/"."Caption.txt");

header('Content-Type: text/html; charset=UTF-8');
mb_internal_encoding('UTF-8');

// z.B. Startseite
$ref=null;
if (isset($_GET["ref"]))
    $ref=$_GET["ref"];


$content=new Content(CONTENT_DIR);
$content->scan_content();

//$content->create_head_navigation();
//$content->create_side_navigation();

//$page=$content->find_page($ref);
$page_content = $content->find_page_read_content($ref, true);

?>

<!DOCTYPE HTML>
<!--
    Read Only by HTML5 UP
    html5up.net | @n33co
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <title><?=TITLE?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="icon" type="image/png" href="/favicon.png" sizes="100x93">
        <link rel="stylesheet" href="assets/css/main.css" />
    </head>

    <body>
        <!-- Header -->
            <section id="header">
                <header>
                    <span class="image avatar"><a href="index.php"><img src="images/RoboLogo_klein.png" alt="" /></a></span>
                    <h1 id="logo"><a href="index.php"><?=TITLE?></a></h1>
                    <p><?=SUBTITLE?></p>
                </header>
                <nav id="nav">
                    <?=$content->create_head_navigation()?>
                    <?=$content->create_side_navigation()?>
                </nav>
            </section>

        <!-- Wrapper -->
            <div id="wrapper">

                <!-- Main -->
                    <div id="main">
                        <section id="two"><div class="container">
                            <?= $page_content ?>
                        </div></section>
                    </div>

                <!-- Footer -->
                    <section id="footer">
                        <div class="container">
                            <ul class="copyright">
                                <li>&copy; RoboSAX. All rights reserved.</li>
                                <li><a href="?ref=impressum">Impressum</a></li>
                                <li><a href="?ref=datenschutz">Datenschutz</a></li>
                            </ul>
                        </div>
                    </section>

            </div>

        <!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/jquery.scrollzer.min.js"></script>
            <script src="assets/js/jquery.scrolly.min.js"></script>
            <script src="assets/js/skel.min.js"></script>
            <script src="assets/js/util.js"></script>
            <script src="assets/js/main.js"></script>

            <?php include(__DIR__."/accept_ds.php"); ?>
    </body>
</html>
