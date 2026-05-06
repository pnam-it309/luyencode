<?php
use Cake\Datasource\ConnectionManager;
require 'config/bootstrap.php';

$connection = ConnectionManager::get('default');
$results = $connection->execute('SELECT * FROM users')->fetchAll('assoc');
print_r($results);
