<?php


    /**
     * Password-Based Key Derivation Function using PBKDF2
     * as described by RSA's PKCS #5: https://www.ietf.org/rfc/rfc2898.txt
     * Note: You will want to run base64_encode on the output of this method to use it
     * as text as the output is binary.
     * @static
     * @param string $password The plaintext password to hash.
     * @param string $salt A salt that is unique to the password.
     * @param int $keyLength The length of the derived key in bytes.
     * @param string $iterations The number of times to hash the password before returning.
     * @param string $algorithm The hash algorithm to use.
     * @return Binary string of $keyLength bytes, derived from the provided $password and $salt.
     */
    function PBKDF2($password, $salt, $iterations = 10000, $algorithm = 'sha256', $keyLength = 64)
    {
        $algorithm = strtolower($algorithm);

        // Determine the length of the specified hash
        $hashLength = strlen(hash($algorithm, null, true));

        // The number of iterations of the hash necessary to fill $keyLength characters
        // IE: If $keyLength is 256 but $hashLength is only 128, we'd need 2 blocks
        // to fill our $keyLength. If $keyLength was 128 and $hashLength is 256, we'd just
        // take a subset of $output when we're done.
        $blockCount = ceil($keyLength / $hashLength);

        $output = '';

        for($i = 1; $i <= $blockCount; $i++)
        {
            // Beginning hash for this block/iteration
            $iterate = $block = hash_hmac($algorithm, $salt . pack('N', $i), $password, true);

            // Hash each block the specified number of times
            for($j = 1; $j < $iterations; $j++)
            {
                // XOR each iterate
                $iterate ^= ($block = hash_hmac($algorithm, $block, $password, true));
            }
            // Block is completed, append to the output and move on to the next
            $output .= $iterate;
        }

        // Return up to $keyLength characters
        return substr($output, 0, $keyLength);
    }
