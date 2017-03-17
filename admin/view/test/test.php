<?php

    use \Defuse\Crypto\Key;
    use \Defuse\Crypto\Crypto;
    use \Defuse\Crypto\KeyProtectedByPassword;
    //generate-key.php
//      $key = Key::createNewRandomKey();
//     $keyString = $key->saveToAsciiSafeString();
//     echo $keyString."<br>";

    $keyAscii = file_get_contents('./keys/Shared/shared.txt');
    $returnKey = Key::loadFromAsciiSafeString($keyAscii);
    $secret_data = "Admin";
    $ciphertext = Crypto::encrypt($secret_data, $returnKey);
    echo $ciphertext."<br>";
    $decrypt = Crypto::decrypt($ciphertext,$returnKey);
    echo $decrypt."<br>";
    $password = "root";
    $protected_key = KeyProtectedByPassword::createRandomPasswordProtectedKey($password);
    $protected_key_encoded = $protected_key->saveToAsciiSafeString();
    print_r($protected_key);
    echo "<br>";
    echo "protected_key_encoded: ".$protected_key_encoded."<br>";
    $protected_key2 = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);;
    // $returnKey = KeyProtectedByPassword::loadFromAsciiSafeString($protected_key_encoded);
    $returnKey = $protected_key2->unlockKey($password);
    $secret_data = "Admin";
    $ciphertext = Crypto::encrypt($secret_data, $returnKey);
    echo $ciphertext."<br>";
    $decrypt = Crypto::decrypt($ciphertext,$returnKey);
    echo $decrypt."<br>";

?>