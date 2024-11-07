<?php
session_start();

if (isset($_SESSION['id'])) {
    include('includes/config.php');
    $outgoing_id = $_SESSION['id'];
    $incoming_id = mysqli_real_escape_string($con, $_POST['incoming_id']);

    $output = "";

    $sql = "SELECT 
        messages.*, 
        users.*, 
        COALESCE(admin.id, 0) AS admin_id, 
        COALESCE(admin.username, '') AS admin_name,
        users.name AS sender_name
    FROM messages
    LEFT JOIN users ON users.id = messages.sender_id
    LEFT JOIN admin ON admin.id = messages.receiver_id
    WHERE (messages.sender_id = {$outgoing_id} AND messages.receiver_id = {$incoming_id}) 
       OR (messages.sender_id = {$incoming_id} AND messages.receiver_id = {$outgoing_id});
    ";

    $query  = mysqli_query($con, $sql);

    if (!$query) {
        die("Error executing query: " . mysqli_error($con));
    }

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['sender_id'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                <div class="details">
                    <p>' . $row['message'] . '</p>
                </div>
                </div>';
            } else {
                $output .= '<div class="chat incoming">
                <div class="details">
                    <p>' . $row['message'] . '</p>
                </div>
                </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }

    echo $output;
}
?>
