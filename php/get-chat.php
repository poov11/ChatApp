<?php 
 session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $strongKey = 'qkwjdiw239&&jdafweihbrhnan&^%$ggdnawhd4njshjwuuO';
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    // Retrieve and decrypt messages from the database
    $sql = "SELECT * FROM messages WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id}) 
            OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";

    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $encrypted_message = $row['msg'];
            $iv = $row['iv'];

            // Convert hex IV to binary
            //$iv = hex2bin($hex_iv);

            // Decrypt the message
            $decrypted_message = openssl_decrypt($encrypted_message, 'aes-256-cbc', $strongKey, 0, $iv);

            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>' .$decrypted_message . '</p>
                                </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                                <div class="details">
                                    <p>' . $decrypted_message . '</p>
                                </div>
                            </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send messages, they will appear here.</div>';
    }

    echo $output;
} else {
    header("location: ../login.php");
}
?>
    