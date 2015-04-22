<?php

try {
    $conn = new PDO('mysql:host=127.0.0.1; dbname=world', 'root', 'password');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}