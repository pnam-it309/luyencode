<?php
require 'vendor/autoload.php';
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use App\Application;

$app = new Application('config');
$app->bootstrap();

$conn = ConnectionManager::get('default');
$results = $conn->execute('SELECT * FROM classes')->fetchAll('assoc');
print_r($results);
