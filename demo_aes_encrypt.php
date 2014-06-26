<?php
require_once 'AES.php';

echo "AES encryption/deceyption + HMAC-SHA256 example\n";
echo "-----------------------------------------------\n";

$text = 'Hello World!';
$key  = 'Super secret key';

printf("Text to encrypt     : %s\n", $text);
$ciphertext = AES_encrypt($text, $key);
printf("Ciphertext in Base64: %s\n", base64_encode($ciphertext));

$plaintext = AES_decrypt($ciphertext, $key);
printf("Decrypted text      : %s\n", $plaintext);

if ($plaintext !== $text) {
    echo "ERROR: the decrypted text is different from the original one!\n";
}

