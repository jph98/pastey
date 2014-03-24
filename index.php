<?php

//$_ENV['SLIM_MODE'] = 'production';

require 'vendor/autoload.php';

// Requires the PDO drivers installed
// Make sure the extension (extension=php_pdo_mysql.dll) is enabled in php.ini
use RedBean_Facade as RB;

session_cache_limiter(false);
session_start();

RB::setup('mysql:host=localhost;dbname=pastey','pastey','!pastey01');

// Lock schema and prevent modifications
RB::freeze(true);

$app = new \Slim\Slim(array(
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'debug' => true,
    'templates.path' => 'templates'
));

$app->setName('Pastey');

    // Index
    $app->get('/', function() use ($app) {    

        $app->response()->header('Content-Type', 'text/html');
        $app->render('listings.html');

        //$app->redirect('home/'.$_SESSION['group_id']);

        //  $_SESSION['user'] = $email;
        // if (isset($_SESSION['urlRedirect'])) {

        // $app->flash('errors', $errors);

    });

    // View the listings
    $app->get('/listings', function() use ($app) {   

        try {
            $listings = RB::find('listings'); 
            echo json_encode(RB::exportAll($listings));
        } catch(\Exception $e) {
            $app->flash('error', $e->getMessage());
            $app->redirect('/error');
        }
    });

    // Find a specific listing
    $app->get('/listings/:pasteid', function($pasteid) use ($app) {   
        echo "Single listing";
    });

    // Submit listing
    $app->post('/listings/:pasteid', function() use ($app) {   
        echo "Add listing";
    });

    function viewPaste($pasteid) {
        echo "View paste " + $pasteid;
    }

    function addPaste($pasteid) {
        echo "Add paste " + $pasteid;
    }

// Run the application
$app->run();

?>