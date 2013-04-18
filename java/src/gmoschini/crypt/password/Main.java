package gmoschini.crypt.password;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.security.NoSuchAlgorithmException;
import java.security.InvalidKeyException;
import java.security.SecureRandom;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class Main 
{
    public static void main(String[] args) throws IOException,
            NoSuchAlgorithmException, RuntimeException
    {
        String password = "password";
        String salt = "";

        if (args.length >= 1)
        {
            password = args[0];
        }
        
        if (args.length >= 2)
        {
            salt = args[1];
        }

        System.out.println(PBKDF2.hash("HmacSHA512", password, salt, 1000, 64));
    }
}
