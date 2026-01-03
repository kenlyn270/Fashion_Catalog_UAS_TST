<?php
$db = new PDO('sqlite:/var/www/html/writable/fashion_catalog.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("BEGIN");

$db->exec("
CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  category TEXT,
  tags TEXT,
  color TEXT,
  style TEXT,
  price REAL,
  product_url TEXT,
  image_url TEXT,
  brand TEXT,
  gender TEXT,
  created_at TEXT
);
");

$db->exec("
INSERT INTO products
(id, name, category, tags, color, style, price, product_url, image_url, brand, gender, created_at)
VALUES
(1, 'Sweatshirt V Neck', 'top',
 'sweater, v_neck, knitwear, layering, casual, clean, smart',
 'grey', 'clean, casual, smart', 99900,
 'https://id.shp.ee/2WsXFkR',
 'https://down-id.img.susercontent.com/file/id-11134207-7qul5-lfmwfr2cavky08@resize_w450_nl.webp',
 'BasicWear', 'unisex', datetime('now')),

(2, 'Kemeja Panjang Kotak-Kotak', 'top',
 'shirt, checkered, plaid, layering, casual, preppy',
 'blue', 'clean, casual, smart', 137440,
 'https://id.shp.ee/hNRvyrH',
 'https://down-id.img.susercontent.com/file/id-11134207-8224q-mittyxu3accg2d.webp',
 'Okstylee', 'unisex', datetime('now')),

(3, 'Celana Panjang Bahan', 'bottom',
 'trousers, tailored, formal, minimalist, smart casual',
 'grey', 'clean, formal, smart', 84750,
 'https://id.shp.ee/47SRr1t',
 'https://down-id.img.susercontent.com/file/b65f13ab27f97d05f92c2a5080bc984a.webp',
 'huwannur99', 'man', datetime('now')),

(4, 'Kaos Polos Oversize', 'top',
 't_shirt, oversize, cotton, basic, streetwear, casual',
 'black', 'casual, street, clean', 165760,
 'https://id.shp.ee/UqMudM1',
 'https://down-id.img.susercontent.com/file/id-11134207-7ra0i-mcfr9osvgngl01',
 'VibeVision', 'unisex', datetime('now')),

(5, 'Cardigan Rajut', 'top',
 'cardigan, knitwear, outerwear, layering, casual, korean',
 'blue', 'casual, clean, korean', 201960,
 'https://id.shp.ee/CvikNF7',
 'https://down-id.img.susercontent.com/file/sg-11134201-7renw-m25nv1u0i82eb3',
 'Metromoda', 'man', datetime('now')),

(6, 'Celana Jeans Loose', 'bottom',
 'jeans, denim, loose_fit, baggy, wide_leg, casual',
 'blue', 'casual, street', 81500,
 'https://id.shp.ee/fhtNLuT',
 'https://down-id.img.susercontent.com/file/id-11134207-8224u-miv1jbh12ww636',
 '677YU', 'man', datetime('now')),

(7, 'Tas Selempang Kulit', 'bag',
 'sling_bag, crossbody, leather, minimalist, casual',
 'black', 'casual, minimalist', 182999,
 'https://id.shp.ee/mB96hgv',
 'https://down-id.img.susercontent.com/file/id-11134207-7ras9-m26qjf16w602be',
 'Leather Concept', 'man', datetime('now')),

(8, 'Sweater V Neck', 'top',
 'sweater, v_neck, knitwear, layering, casual, smart, classic',
 'burgundy', 'classic, smart, casual', 189900,
 'https://id.shp.ee/81x4hMQ',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-lw1g1p2x2q5p8e',
 'ClassicWear', 'man', datetime('now')),

(9, 'Kemeja Garis Panjang', 'top',
 'shirt, striped, long_sleeve, layering, clean, classic',
 'white', 'clean, classic, smart', 149900,
 'https://id.shp.ee/g9jwFMk',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98r-lw1g1qz1y8xv3d',
 'UrbanBasic', 'man', datetime('now')),

(10, 'Celana Bahan Panjang', 'bottom',
 'trousers, tailored, wide_leg, minimalist, formal, smart',
 'beige', 'classic, smart, formal', 219900,
 'https://id.shp.ee/D8ehcji',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98q-lw1g1r5n2e0c6f',
 'ModernFit', 'man', datetime('now')),

(11, 'Kemeja Panjang Polos', 'top',
 'shirt, long_sleeve, layering, clean, casual, minimalist',
 'light_blue', 'clean, casual, minimalist', 139900,
 'https://id.shp.ee/mnsy9GD',
 'https://down-id.img.susercontent.com/file/id-11134207-7qul8-lk0x1s2p3r4t5u',
 'BasicWear', 'unisex', datetime('now')),

(12, 'Celana Panjang Hitam', 'bottom',
 'trousers, straight_fit, minimalist, casual, smart',
 'black', 'clean, casual, smart', 179900,
 'https://id.shp.ee/hUdtWXF',
 'https://down-id.img.susercontent.com/file/id-11134207-7qul6-lk0x1u9v8y7w6x',
 'UrbanBasic', 'unisex', datetime('now')),

(13, 'Polo Rugby Garis', 'top',
 'polo, rugby_shirt, striped, long_sleeve, casual, classic',
 'green_white', 'casual, classic, preppy', 159900,
 'https://id.shp.ee/cJvdBKr',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-lx0g9p2r3s4t5u',
 'ClassicDaily', 'unisex', datetime('now')),

(14, 'Celana Panjang Chino', 'bottom',
 'chino, trousers, straight_fit, casual, minimalist, classic',
 'beige', 'casual, clean, classic', 189900,
 'https://id.shp.ee/aAWm1p4',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98r-lx0g9q8w7e6d5c',
 'UrbanWear', 'unisex', datetime('now')),

(15, 'Kemeja Panjang Polos', 'top',
 'shirt, long_sleeve, layering, casual, clean, minimalist',
 'pink', 'clean, casual, minimalist', 149900,
 'https://id.shp.ee/awy8C4G',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98p-lyxk2m3n4b5v6c',
 'VARO', 'unisex', datetime('now')),

(16, 'Celana Panjang Bahan', 'bottom',
 'trousers, wide_leg, relaxed_fit, minimalist, casual, clean',
 'cream', 'clean, casual, minimalist', 189900,
 'https://id.shp.ee/dx3Cspt',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98q-lyxk2q8w7e6d5c',
 'VARO', 'unisex', datetime('now')),

(17, 'Kemeja Garis Panjang', 'top',
 'shirt, striped, long_sleeve, clean, casual, classic',
 'beige', 'clean, classic, casual', 159900,
 'https://id.shp.ee/YAGj9AV',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-lz3gk2m4n5b6v7',
 'UrbanClassic', 'woman', datetime('now')),

(18, 'Celana Panjang Bahan', 'bottom',
 'trousers, wide_leg, tailored, minimalist, smart, casual',
 'brown', 'classic, smart, casual', 209900,
 'https://id.shp.ee/WoGzf8W',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98q-lz3gk9w8e7d6c5',
 'UrbanClassic', 'woman', datetime('now')),

(19, 'Blazer Crop Pendek', 'top',
 'blazer, cropped, short_sleeve, casual, clean, feminine',
 'light_blue', 'clean, casual, feminine', 169900,
 'https://id.shp.ee/vxBBQ1o',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-lzzk2m4n5b6v7',
 'SoftWear', 'woman', datetime('now')),

(20, 'Kaos Polos Inner', 'top',
 't_shirt, basic, innerwear, casual, minimalist',
 'white', 'clean, casual, minimalist', 89900,
 'https://id.shp.ee/SEsAas5',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98p-lzzk9w8e7d6c5',
 'BasicDaily', 'woman', datetime('now')),

(21, 'Celana Jeans Straight', 'bottom',
 'jeans, denim, straight_fit, casual, daily, minimalist',
 'light_blue', 'casual, clean', 219900,
 'https://id.shp.ee/58cSVVe',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98q-lzzk2q8w7e6d5c',
 'UrbanDenim', 'woman', datetime('now')),

(22, 'Cardigan Rajut', 'top',
 'cardigan, knitwear, outerwear, casual, clean, minimalist',
 'olive', 'clean, casual, minimalist', 159900,
 'https://id.shp.ee/aueaS5r',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-m1a2b3c4d5e6f',
 'SoftDaily', 'woman', datetime('now')),

(23, 'Rok Panjang Polos', 'bottom',
 'skirt, long_skirt, minimalist, casual, clean',
 'black', 'clean, casual, minimalist', 179900,
 'https://id.shp.ee/igAn54D',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98p-m1f6e5d4c3b2a',
 'UrbanBasic', 'woman', datetime('now')),

(24, 'Jumpsuit Denim', 'top',
 'jumpsuit, denim, long_sleeve, casual, classic, street',
 'dark_blue', 'classic, casual, street', 259900,
 'https://id.shp.ee/bDaZVkw',
 'https://down-id.img.susercontent.com/file/id-11134207-7r98o-denimjumpsuitplaceholder',
 'UrbanDenim', 'woman', datetime('now')),

(25, 'Dress Bunga Panjang', 'top',
 'dress, floral, long_dress, short_sleeve, feminine, casual',
 'white', 'feminine, casual, summer', 139900,
 'https://id.shp.ee/1y7i37G',
 'https://down-id.img.susercontent.com/file/id-11134207-dressfloralplaceholder',
 'SweetDaily', 'woman', datetime('now')),

(26, 'Sweater Garis Panjang', 'top',
 'sweater, striped, long_sleeve, knitwear, casual, classic',
 'navy_white', 'clean, classic, casual', 159900,
 'https://id.shp.ee/yJEhxLK',
 'https://down-id.img.susercontent.com/file/id-11134207-stripedknitplaceholder',
 'ClassicDaily', 'woman', datetime('now')),

(27, 'Celana Panjang Putih', 'bottom',
 'trousers, straight_fit, minimalist, casual, clean',
 'white', 'clean, casual, minimalist', 189900,
 'https://id.shp.ee/bm4kA4X',
 'https://down-id.img.susercontent.com/file/id-11134207-whitetrousersplaceholder',
 'UrbanBasic', 'woman', datetime('now')),

(28, 'Dress Panjang Ruffle', 'top',
 'dress, long_dress, ruffle, feminine, casual, romantic',
 'pink', 'feminine, romantic, casual', 199900,
 'https://id.shp.ee/WMpg18v',
 'https://down-id.img.susercontent.com/file/id-11134207-pinkruffledressplaceholder',
 'SweetGarden', 'woman', datetime('now')),

(29, 'Cardigan Tipis Polos', 'top',
 'cardigan, knitwear, lightweight, layering, casual, feminine',
 'ivory', 'feminine, casual, clean', 129900,
 'https://id.shp.ee/38KHB1v',
 'https://down-id.img.susercontent.com/file/id-11134207-ivorycardiganplaceholder',
 'SoftDaily', 'woman', datetime('now'));
");

$db->exec("COMMIT");

echo "OK\n";
