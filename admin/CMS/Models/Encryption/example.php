<?php

use \Defuse\Crypto\Crypto;
use \Defuse\Crypto\Exception as Ex;

require_once 'Models/Encryption/autoload.php';

try {
/*
    $key = Crypto::createNewRandomKey();
    // WARNING: Do NOT encode $key with bin2hex() or base64_encode(),
    // they may leak the key to the attacker through side channels.
    // Use the provided functions to do this Crypto::binToHex , Crypto hexTobin. Best to use on,
    // ciphertext only
    
    echo 'new key created: '. $key.'<br />';
	// write key to file so we can reuse it to encrypt messages.
    $file = fopen('new_key.txt','w');
    fwrite($file, $key);
    fclose($file);
*/
    /* 
     * to reuse te key, don't create a new one but fetch it from the file:
     */
    $key = file_get_contents('../../new_key.txt');
    
} catch (Ex\CryptoTestFailedException $ex) {
    die('Cannot safely create a key');
} catch (Ex\CannotPerformOperationException $ex) {
    die('Cannot safely create a key');
}

$message = "Vivamus fermentum semper porta. Nunc diam velit, adipiscing ut tristique vitae, sagittis vel odio. Maecenas convallis ullamcorper ultricies. Curabitur ornare, ligula semper consectetur sagittis, nisi diam iaculis velit, id fringilla sem nunc vel mi. Nam dictum, odio nec pretium volutpat, arcu ante placerat erat, non tristique elit urna et turpis. Quisque mi metus, ornare sit amet fermentum et, tincidunt et orci.";
try {
    $ciphertext = Crypto::encrypt($message, $key);
    echo 'encrypted message: '.$ciphertext.'<br />';
    $dbc = mysqli_connect('localhost','root','root','nerdcms_db') or die('error 1');
    $storeMe = Crypto::binTohex($ciphertext);
    // to be able to safe larger chunks of data, convert bin2hex, and when decrypting hex2bin.
    //otherwise you will only be able to store small amounts of data.
    // also try BLOBS.
    $query = "INSERT INTO crypto(text) VALUES('$storeMe')";
    mysqli_query($dbc,$query) or die('error 2') ;
} catch (Ex\CryptoTestFailedException $ex) {
    die('Cannot safely perform encryption');
} catch (Ex\CannotPerformOperationException $ex) {
    die('Cannot safely perform encryption');
}


// save the encryoted text as type VARBINARY in database.
try {
	 $dbc = mysqli_connect('localhost','root','root','nerdcms_db') or die('error');
	 $query = "SELECT * FROM crypto WHERE id = 69";
	 $data = mysqli_query($dbc,$query) or die ('Error');
	 while($row = mysqli_fetch_array($data)){
		  // WARNING: Do NOT encode $key with bin2hex() or base64_encode(),
		  // they may leak the key to the attacker through side channels.
		// $key = $row['token'];
		 $ciphertext = $row['text'];
		 $ciphertext = Crypto::hexTobin($ciphertext);
	 } 
	 $decrypted = Crypto::decrypt($ciphertext, $key);
	 echo 'decrypted message from database: '.$decrypted.'<br />';
} catch (Ex\InvalidCiphertextException $ex) { // VERY IMPORTANT
    // Either:
    //   1. The ciphertext was modified by the attacker,
    //   2. The key is wrong, or
    //   3. $ciphertext is not a valid ciphertext or was corrupted.
    // Assume the worst.
    die('DANGER! DANGER! The ciphertext has been tampered with!');
} catch (Ex\CryptoTestFailedException $ex) {
    die('Cannot safely perform decryption');
} catch (Ex\CannotPerformOperationException $ex) {
    die('Cannot safely perform decryption');
}
?>