<?php

declare(strict_types=1);
session_start();

$host = "localhost";
$dbname = "Blog_db";
$port = "5432";
$user = "postgres";
$pass = "";

function pdo(): PDO {
    global $host, $dbname, $port, $user, $pass;
    
    static $pdo = null;

        if ($pdo === null){
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,              
            ];

            try{
                $pdo = new PDO($dsn, $user, $pass, $options);
            }catch (\PDOException $e){
                exit('koneksi database gagal' . $e->getMessage());
            }
        }   
    return $pdo;
}

require_once __DIR__ . '/token_csrf.php';
require_once __DIR__ . '/helpers.php';
