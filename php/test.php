<?php 
include "pbkdf2.php";
include "prompt.php";
$rounds = 1000;

$domain = readline('Realm: ');
$user = readline('User: ');
$salt = readline('Salt: ');
echo 'Password: ';
$password = _promptPassword();
echo "\n";

$key = $user.':'.$password.':'.$domain;

srand();
$salt = $salt ? $salt : base64_encode(openssl_random_pseudo_bytes(32));
print $salt."\n";

print str_replace('+', '.', base64_encode(pbkdf2('sha512', $key, $salt, $rounds, 64, true)))."\n";

