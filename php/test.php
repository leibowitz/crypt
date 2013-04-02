<?php 
include "pbkdf2.php";
$rounds = 1000;
$salt = 'abc';
$password = 'hello';
print base64_encode(PBKDF2($password, $salt, $rounds, 'sha512'))."\n";

