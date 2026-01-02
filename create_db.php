<?php
$db = new PDO('sqlite:/var/www/html/writable/fashion_catalog.db');

$db->exec("
CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  category TEXT,
  tags TEXT,
  price REAL,
  product_url TEXT,
  image_url TEXT,
  created_at TEXT,
  updated_at TEXT
);
");

echo "OK\n";
