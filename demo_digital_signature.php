<?php
/**
 * Example of digital signature using OpenSSL and Mcrypt
 *
 * @author Enrico Zimuel (enrico@zimuel.it)
 */


echo "Example of digital signature in PHP\n";
echo "-----------------------------------\n";

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

echo "Reading the private key\n";

// read the private key
$privateKey = openssl_pkey_get_private('file:///' . __DIR__ . '/private.key', $passphrase);

$data = file_get_contents(__FILE__);

echo "Computing the signature\n";

// compute signature with SHA-265
openssl_sign($data, $signature, $privateKey, "sha256");

printf("Signature : %s\n", base64_encode($signature));

$publicKey = openssl_pkey_get_public('file://' . __DIR__ . '/public.key');

// verify the signature
$result = openssl_verify($data, $signature, $publicKey, "sha256");

echo $result === 1 ? 'Signature verified' : 'Signature not valid';

