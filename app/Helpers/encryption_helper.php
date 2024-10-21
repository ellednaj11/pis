<?php

use CodeIgniter\Encryption\Encryption;

function encrypt_id($id)
{
    $encrypter = \Config\Services::encrypter();
    return rtrim(strtr(base64_encode($encrypter->encrypt($id)), '+/', '-_'), '=');
}

function decrypt_id($encodedId)
{
    $encrypter = \Config\Services::encrypter();
    return $encrypter->decrypt(base64_decode(strtr($encodedId, '-_', '+/')));
}
