<?php
define('BASE_PATH', dirname(__DIR__));

function e(?string $string): string{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
?>