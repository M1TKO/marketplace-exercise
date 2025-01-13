<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constants.php';

DB::$user     = DB_USER;
DB::$password = DB_PASSWORD;
DB::$host     = DB_HOST;
DB::$port     = DB_PORT;

// On first run ever, populate the DB
try {
	DB::useDB(DB_NAME);
} catch (Throwable) {
	exec('php ' . DB_SEEDER_FILE);
	DB::useDB(DB_NAME);
}

