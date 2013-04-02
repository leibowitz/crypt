<?php 
include "pbkdf2.php";
include "prompt.php";
$rounds = 1000;

$domain = readline('Domain: ');
$login = readline('Login: ');
$name = readline('Name: ');
echo 'Password: ';
$password = _promptPassword();
echo "\n";

$salt = 'abc';

print base64_encode(pbkdf2('sha512', $password, $salt, $rounds, 64, true))."\n";

