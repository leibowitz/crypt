<?php
// from http://pear2.php.net/PEAR2_Console_CommandLine/files/PEAR2_Console_CommandLine-0.2.0/php/PEAR2/Console/CommandLine/Action/Password.php
function _promptPassword()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        @flock(STDIN, LOCK_EX);
        $passwd = fgets(STDIN);
        @flock(STDIN, LOCK_UN);
    } else {
        // disable echoing
        system('stty -echo');
        @flock(STDIN, LOCK_EX);
        $passwd = fgets(STDIN);
        @flock(STDIN, LOCK_UN);
        system('stty echo');
    }
    return trim($passwd);
}
