package gmoschini.crypt.password;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.security.NoSuchAlgorithmException;
import java.security.InvalidKeyException;
import java.security.SecureRandom;

import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class PBKDF2
{

    /**
     * Core Password Based Key Derivation Function 2.
     * 
     * @see <a href="http://tools.ietf.org/html/rfc2898">RFC 2898 5.2</a>
     * @param prf
     *            Pseudo Random Function (i.e. HmacSHA1)
     * @param S
     *            Salt as array of bytes. <code>null</code> means no salt.
     * @param c
     *            Iteration count (see RFC 2898 4.2)
     * @param dkLen
     *            desired length of derived key.
     * @return internal byte array
     */
    protected static byte[] PBKDF2(Mac mac, byte[] S, int c, int dkLen)
    {
        if (S == null)
        {
            S = new byte[0];
        }
        int hLen = mac.getMacLength();
        int l = ceil(dkLen, hLen);
        int r = dkLen - (l - 1) * hLen;
        byte T[] = new byte[l * hLen];
        int ti_offset = 0;
        for (int i = 1; i <= l; i++)
        {
            _F(T, ti_offset, mac, S, c, i);
            ti_offset += hLen;
        }
        if (r < hLen)
        {
            // Incomplete last block
            byte DK[] = new byte[dkLen];
            System.arraycopy(T, 0, DK, 0, dkLen);
            return DK;
        }
        return T;
    }

    /**
     * Integer division with ceiling function.
     * 
     * @see <a href="http://tools.ietf.org/html/rfc2898">RFC 2898 5.2 Step 2.</a>
     * @param a
     * @param b
     * @return ceil(a/b)
     */
    protected static int ceil(int a, int b)
    {
        int m = 0;
        if (a % b > 0)
        {
            m = 1;
        }
        return a / b + m;
    }

    /**
     * Function F.
     * 
     * @see <a href="http://tools.ietf.org/html/rfc2898">RFC 2898 5.2 Step 3.</a>
     * @param dest
     *            Destination byte buffer
     * @param offset
     *            Offset into destination byte buffer
     * @param prf
     *            Pseudo Random Function
     * @param S
     *            Salt as array of bytes
     * @param c
     *            Iteration count
     * @param blockIndex
     */
    protected static void _F(byte[] dest, int offset, Mac mac, byte[] S, int c,
            int blockIndex)
    {
        int hLen = mac.getMacLength();
        byte U_r[] = new byte[hLen];

        // U0 = S || INT (i);
        byte U_i[] = new byte[S.length + 4];
        System.arraycopy(S, 0, U_i, 0, S.length);
        INT(U_i, S.length, blockIndex);

        for (int i = 0; i < c; i++)
        {
            U_i = mac.doFinal(U_i);
            xor(U_r, U_i);
        }
        System.arraycopy(U_r, 0, dest, offset, hLen);
    }

    /**
     * Block-Xor. Xor source bytes into destination byte buffer. Destination
     * buffer must be same length or less than source buffer.
     * 
     * @param dest
     * @param src
     */
    protected static void xor(byte[] dest, byte[] src)
    {
        for (int i = 0; i < dest.length; i++)
        {
            dest[i] ^= src[i];
        }
    }

    /**
     * Four-octet encoding of the integer i, most significant octet first.
     * 
     * @see <a href="http://tools.ietf.org/html/rfc2898">RFC 2898 5.2 Step 3.</a>
     * @param dest
     * @param offset
     * @param i
     */
    protected static void INT(byte[] dest, int offset, int i)
    {
        dest[offset + 0] = (byte) (i / (256 * 256 * 256));
        dest[offset + 1] = (byte) (i / (256 * 256));
        dest[offset + 2] = (byte) (i / (256));
        dest[offset + 3] = (byte) (i);
    }

    public static String hash(String algo, String password, String _salt, int iterations, int keyLen) throws IOException,
            NoSuchAlgorithmException, RuntimeException
    {
        byte[] salt = new byte[8];
        if("".equals(_salt))
        {
            SecureRandom sr = SecureRandom.getInstance("SHA1PRNG");
            sr.nextBytes(salt);
        }
        else
        {
            salt = _salt.getBytes();
        }

        Mac mac = Mac.getInstance(algo);
        try
        {
            mac.init(new SecretKeySpec(password.getBytes(), algo));
        }
        catch (InvalidKeyException e)
        {
            throw new RuntimeException(e);
        }
        byte[] hash = PBKDF2(mac, salt, iterations, keyLen);
        return Base64.encode(hash);
    }
}
