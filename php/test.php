<?php 
include "pbkdf2.php";
$rounds = 1000;
$salt = 'abc';
$password = 'hello';

print base64_encode(pbkdf2('sha512', $password, $salt, $rounds, 64, true))."\n";

