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

const DEFAULT_THEME = "ace/theme/tomorrow";

$app = new \Slim\Slim(array(
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
    'log.writer' => new \Slim\Extras\Log\DateTimeFileWriter(array(
            'path' => '/var/log/apache2/pastey',
            'message_format' => '%label% - %date% - %message%'
    )),
    'debug' => true,
    'templates.path' => 'templates'
));

    // Admin page
    $app->get('/admin', function() use ($app) {   

        echo "admin page";
        $app->response()->header('Content-Type', 'text/html');
        $app->render('admin.html');

    })->name('admin');

    // Get all pastes
    $app->get('/pastesall', function() use ($app) {   

        try {
            $pastes = RB::find('pastes'); 
            echo json_encode(RB::exportAll($pastes));
        } catch(\Exception $e) {
            
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    })->name('getallpastes');

    // Paste listing page
    $app->get('/pastes/:pastebinkey', function($pastebinkey) use ($app) {

        error_log("Rendering paste for " + $pastebinkey);
        $app->render('viewpaste.html', array("pastebinkey" => $pastebinkey) );

    })->name('getpaste');

    // Pastedetail 
    $app->get('/pastedetail/:pastebinkey', function($pastebinkey) use ($app) {

        error_log("Rendering paste detail for " + $pastebinkey);
        $paste = RB::findOne('pastes', ' pastebinkey = :pastebinkey',  array('pastebinkey' => $pastebinkey));
        
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($paste->export());

    })->name('getpastejson');

    // Index Page
    $app->get('/', function() use ($app) {    

        $app->render('addpaste.html');

    })->name('indexpage');

    // Languages dropdown
    $app->get('/languages', function() use ($app) {   

        try {
            $languages = RB::findAll('languages', ' ORDER BY priority, name '); 
            echo json_encode(RB::exportAll($languages));

        } catch(\Exception $e) {
            
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    })->name('languagesdropdown');

    // POST New Paste
    $app->post('/pastes', function() use ($app) {   

        try {
        
            $paste = RB::dispense('pastes');
            $paste->name = $app->request->post('name');
            $paste->title = $app->request->post('title');
            $paste->theme = DEFAULT_THEME;
            $paste->language = $app->request->post('language');
            $paste->sourcecode = $app->request->post('sourcecode');
            $paste->theme = $app->request->post('theme');
            $paste->pastebinkey = uniqid("pastebin-") . round(microtime(true) * 1000);

            $paste->id = RB::store($paste);

            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode("/pastey/pastes/" . $paste->pastebinkey);

        } catch(\Exception $e) {
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    })->name('addnewpaste');

    // Update paste
    $app->put('/pastes/:pastebinkey', function($pastebinkey) use ($app) {   

        try {

            $paste = RB::findOne('pastes', ' pastebinkey = :pastebinkey',  array('pastebinkey' => $pastebinkey));

            $paste->sourcecode = $app->request->put("sourcecode");            
            $paste->theme = $app->request->put("theme");
            $paste->pastebinkey = $pastebinkey;
            
            RB::store($paste);

            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode("/pastey/pastes/" . $pastebinkey);

        } catch(\Exception $e) {
            error_log("updated paste " . $e);
            $app->response->headers->set('Content-Type', 'application/json');
            echo json_encode(array('error' => $e->getMessage()));
        }

    })->name('updatepaste');

// Run the application
$app->run();

?>