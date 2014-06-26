<?php
/**
 * Example of hybrid cryptosystem in PHP using OpenSSL and Mcrypt
 *
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
include "AES.php";


echo "Example of Hybrid cryptosystem in PHP\n";
echo "-------------------------------------\n";

echo "Generating public and private keys\n";
// Generate public and private keys
$keys = openssl_pkey_new(array(
    "private_key_bits" => 4096,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));

// Store the private key to file
$passphrase = 'test';
openssl_pkey_export_to_file($keys, 'private.key', $passphrase);

// Store the public key to file
$details   = openssl_pkey_get_details($keys);
$publicKey = $details['key'];
file_put_contents('public.key', $publicKey);

echo "Generating random encryption key\n";

// Generate a random key of 256 bit
$key = mcrypt_create_iv(32, MCRYPT_DEV_URANDOM);

// Encrypt the key using the public key
openssl_public_encrypt($key, $encryptedKey, $publicKey); 

$message = 'This is the secret message';

echo "Encrypting the message with AES\n";

$ciphertext = AES_encrypt($message, $key);

printf("Encrypted key (Base64): %s\n", base64_encode($encryptedKey));
printf("Size of encrypted key : %d\n", strlen($encryptedKey));
printf("Encrypted message (Base64): %s\n", base64_encode($ciphertext));

$fileOut = 'encrypted.msg';
printf("Store the encrypted message in %s\n", $fileOut);
// store the encrypted message
file_put_contents($fileOut, $encryptedKey . $ciphertext);

echo "Decrypting the message\n";
// read the encrypted message
$msg = file_get_contents($fileOut);

$encryptedKey = substr($msg, 0, 512);
$msg = substr($msg, 512);

//read the private key
$privateKey = openssl_pkey_get_private('file:///' . __DIR__ . '/private.key', $passphrase);

// decrypt key
openssl_private_decrypt($encryptedKey, $key, $privateKey);

// decrypt message
$result = AES_decrypt($msg, $key);

printf("Result: %s\n", $result);

