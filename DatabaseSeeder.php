<?php

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/vendor/autoload.php';

DB::$host = DB_HOST;
DB::$port = DB_PORT;
DB::$user = DB_USER;
DB::$password = DB_PASSWORD;

// Create the database
DB::query('CREATE DATABASE IF NOT EXISTS ' . DB_NAME);
DB::useDB(DB_NAME);

echo 'DB created/exists' . PHP_EOL;

// Create `products` table
DB::query("
    CREATE TABLE IF NOT EXISTS products (
        id_product INT NOT NULL AUTO_INCREMENT,
        sku CHAR(2) NOT NULL UNIQUE,
        price INT NOT NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id_product)
    )
");

// Create `product_bundles` table
DB::query("
	CREATE TABLE IF NOT EXISTS product_bundles (
		id_product        INT NOT NULL,
		product_quantity  INT NOT NULL,
		bundle_price      INT NOT NULL,
		UNIQUE (id_product),
    	FOREIGN KEY (id_product) REFERENCES products(id_product) ON DELETE CASCADE
	)
");

// Create `sales` table
DB::query("
    CREATE TABLE IF NOT EXISTS sales (
		id_sale     INT AUTO_INCREMENT,
		sale_date   DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
		total_price INT NOT NULL,
		PRIMARY KEY (id_sale)
    )
");

// Create `sales_details` table
DB::query("
    CREATE TABLE IF NOT EXISTS sales_details (
		id_sale_detail   INT NOT NULL AUTO_INCREMENT,
		id_sale          INT NOT NULL,
		id_product       INT NOT NULL,
		sku              CHAR(2) NOT NULL,
		product_quantity INT NOT NULL DEFAULT 1,
		line_price       INT NOT NULL,
		PRIMARY KEY (id_sale_detail),
		FOREIGN KEY (id_sale) REFERENCES sales(id_sale) ON DELETE CASCADE
    )
");

echo 'DB tables created/exist' . PHP_EOL;

// Insert sample data into `products` table
DB::query("
	INSERT IGNORE INTO products (id_product, sku, price, created_at) VALUES
	(1, 'A', 50, CURRENT_TIMESTAMP),
	(2, 'B', 30, CURRENT_TIMESTAMP),
	(3, 'C', 20, CURRENT_TIMESTAMP),
	(4, 'D', 10, CURRENT_TIMESTAMP)
");

// Insert sample data into `product_bundles` table
DB::query("
	INSERT IGNORE INTO product_bundles (id_product, product_quantity, bundle_price) VALUES
	(1, 3, 130),
	(2, 2, 45)
");

echo 'DB seeded with data' . PHP_EOL;
echo 'Script completed' . PHP_EOL;
