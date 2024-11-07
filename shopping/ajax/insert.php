<?php 

session_start();

if (isset($_SESSION['id'])) {

    if (isset($_POST['message']) && isset($_POST['to_id'])) {
    
      
        include "../includes/config.php";

      
        $message = $_POST['message'];
        $to_id = $_POST['to_id'];

        $from_id = $_SESSION['id'];

     
        $sql = "INSERT INTO chats (from_id, to_id, message) VALUES ($from_id, $to_id, ?)";
        if ($stmt = $con->prepare($sql)) {
            $stmt->bind_param("s", $message); 
            $res = $stmt->execute();
            
    
            if ($res) {
            
                $sql2 = "SELECT * FROM conversations
                         WHERE (user_1 = $from_id AND user_2 = $to_id)
                         OR (user_2 = $from_id AND user_1 = $to_id)";
                $result2 = $con->query($sql2);

             
                define('TIMEZONE', 'Africa/Addis_Ababa');
                date_default_timezone_set(TIMEZONE);

                $time = date("h:i:s a");

                if ($result2->num_rows == 0) {
                  
                    $sql3 = "INSERT INTO conversations (user_1, user_2) VALUES ($from_id, $to_id)";
                    $con->query($sql3);
                }
                ?>

                <p class="rtext align-self-end border rounded p-2 mb-1">
                    <?=$message?>  
                    <small class="d-block"><?=$time?></small>      
                </p>

                <?php 
            }
        }
    }
} else {
  
}
?>
