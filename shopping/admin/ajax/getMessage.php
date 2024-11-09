<?php 
session_start();

if (isset($_SESSION['id'])) {
    if (isset($_POST['id_2'])) {
        include "../includes/config.php";
        #include "../include/config.php";

        $id_1 = $_SESSION['id'];
        $id_2 = $_POST['id_2'];

        $sql = "SELECT * FROM chats WHERE (from_id = ? AND to_id = ?) OR (from_id = ? AND to_id = ?) ORDER BY chat_id ASC";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iiii", $id_1, $id_2, $id_2, $id_1);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($chat = $result->fetch_assoc()) {
                ?>
                <p class="ltext border rounded p-2 mb-1">
                    <?= htmlspecialchars($chat['message']) ?>
                    <small class="d-block"><?= $chat['created_at'] ?></small>
                </p>        
                <?php
            }
        } else {
            echo "<p>No messages yet. Start the conversation!</p>";
        }
    }
} else {
    exit;
}
?>
