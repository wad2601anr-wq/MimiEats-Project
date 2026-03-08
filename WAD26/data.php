<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Data asli 100% tetap sama, hanya pindah format ke PHP
$foodData = [
    ["name" => "Nasi Goreng", "shop" => "Kedai Rasa Sayang", "base" => 25000, "fee" => 12000, "time" => "20 min", "rating" => 4.8],
    ["name" => "Nasi Goreng", "shop" => "Waroeng Berkah", "base" => 28000, "fee" => 8000, "time" => "25 min", "rating" => 4.5],
    ["name" => "Nasi Goreng", "shop" => "Mimi Kitchen", "base" => 24000, "fee" => 6000, "time" => "30 min", "rating" => 4.9],
    ["name" => "Ayam Bakar", "shop" => "Ayam Bakar Madu Solo", "base" => 35000, "fee" => 10000, "time" => "15 min", "rating" => 4.7],
    ["name" => "Ayam Bakar", "shop" => "Penyetan Mas Jago", "base" => 30000, "fee" => 9000, "time" => "25 min", "rating" => 4.6],
    ["name" => "Mie Ayam", "shop" => "Mie Ayam Wonogiri", "base" => 15000, "fee" => 12000, "time" => "10 min", "rating" => 4.4],
    ["name" => "Mie Ayam", "shop" => "Bakso Idola", "base" => 18000, "fee" => 5000, "time" => "15 min", "rating" => 4.8],
    ["name" => "Seblak", "shop" => "Seblak Enjoy", "base" => 17500, "fee" => 6000, "time" => "30 min", "rating" => 4.7],
    ["name" => "Nasi Padang", "shop" => "RM Sinar Minang", "base" => 22000, "fee" => 5000, "time" => "15 min", "rating" => 4.9],
    ["name" => "Sate Ayam", "shop" => "Sate Madura Cak Edi", "base" => 20000, "fee" => 7000, "time" => "20 min", "rating" => 4.7],
    ["name" => "Bakso Sapi", "shop" => "Bakso Solo Baru", "base" => 18000, "fee" => 5000, "time" => "12 min", "rating" => 4.6]
];

echo json_encode($foodData);
?>