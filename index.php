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


    // Add a new paste bin
    $app->get('/', function() use ($app) {    

        $app->response()->header('Content-Type', 'text/html');
        $app->render('addpaste.html');
    });

    $app->get('/listings', function() use ($app) {   

        try {
            $listings = RB::find('listings'); 
            echo json_encode(RB::exportAll($listings));
        } catch(\Exception $e) {
            
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    });

    // Get a list of languages to display in the dropdown
    $app->get('/languages', function() use ($app) {   

        try {
            $languages = RB::find('languages'); 
            echo json_encode(RB::exportAll($languages));
        } catch(\Exception $e) {
            
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    });

    $app->get('/admin', function() use ($app) {   

        echo "admin page";
        $app->response()->header('Content-Type', 'text/html');
        $app->render('admin.html');        
    });

    // Find a specific listing
    $app->get('/listings/:pasteid', function($pasteid) use ($app) {   
        echo "Single listing";
    });

    // Submit listing
    $app->post('/addpaste/:pasteid', function() use ($app) {   
        echo "Add new paste ";
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