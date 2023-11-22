  <?php 
    /*session_start();
    if(isset($_SESSION['unique_id'])){
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            message_hash = password_hash("$message",PASSWORD_DEFAULT);
             $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ({$incoming_id}, {$outgoing_id}, '{$message_hash}')") or die();
        }
    }else{
        header("location: ../login.php");
    }

    <?php*/
session_start();

if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $strongKey = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO';
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";
    $message = mysqli_real_escape_string($conn, $_POST['message']);

        // Encrypt the message
        $iv = openssl_random_pseudo_bytes(16); // Use a fixed length of 16 bytes
        $encrypted_message = openssl_encrypt($message, 'aes-256-cbc', $strongKey, 0, $iv);

        if (!empty($encrypted_message)) {
            // Insert the encrypted message and IV into the database
//            $hex_iv = bin2hex($iv);
            $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, iv)
                    VALUES ({$incoming_id}, {$outgoing_id}, '{$encrypted_message}', '{$iv}')";
            $query = mysqli_query($conn, $sql);

            if (!$query) {
                die(mysqli_error($conn));
            }
        }
    }


?>