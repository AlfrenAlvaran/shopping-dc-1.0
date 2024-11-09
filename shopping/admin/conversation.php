<?php
session_start();
include('include/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    date_default_timezone_set('Asia/Kolkata');
    $currentTime = date('d-m-Y h:i:s A', time());

    if (isset($_GET['del'])) {
        mysqli_query($con, "delete from products where id = '" . $_GET['id'] . "'");
        $_SESSION['delmsg'] = "Product deleted !!";
    }


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin| Manage Users</title>
        <link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
        <link type="text/css" href="css/theme.css" rel="stylesheet">
        <link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>


        <style>
            .vh-100 {
                min-height: 100vh;
            }

            .w-400 {
                width: 400px;
            }

            .fs-xs {
                font-size: 1rem;
            }

            .w-10 {
                width: 10%;
            }

            a {
                text-decoration: none;
            }

            .fs-big {
                font-size: 5rem !important;
            }

            .online {
                width: 10px;
                height: 10px;
                background: green;
                border-radius: 50%;
            }

            .w-15 {
                width: 15%;
            }

            .fs-sm {
                font-size: 1.4rem;
            }

            small {
                color: #bbb;
                font-size: 0.7rem;
                text-align: right;
            }

            .chat-box {
                overflow-y: auto;
                overflow-x: hidden;
                max-height: 50vh;
            }

            .rtext {
                width: 65%;
                background: #f8f9fa;
                color: #444;
            }

            .ltext {
                width: 65%;
                background: #3289c8;
                color: #fff;
            }

            /* width */
            *::-webkit-scrollbar {
                width: 3px;
            }

            /* Track */
            *::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            /* Handle */
            *::-webkit-scrollbar-thumb {
                background: #aaa;
            }

            /* Handle on hover */
            *::-webkit-scrollbar-thumb:hover {
                background: #3289c8;
            }

            textarea {
                resize: none;
            }

            /*message_status*/
        </style>
    </head>

    <body>
        <?php include('include/header.php'); ?>

        <div class="wrapper">
            <div class="container">
                <div class="row">
                    <?php include('include/sidebar.php'); ?>
                    <div class="span9">
                        <div class="content">

                            <div class="module">
                                <div class="module-head">
                                    <h3>Manage Users</h3>
                                </div>

                                <div class="module-body table">
                                    <?php if (isset($_GET['del'])) { ?>
                                        <div class="alert alert-error">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>Oh snap!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?><?php echo htmlentities($_SESSION['delmsg'] = ""); ?>
                                        </div>
                                    <?php } ?>

                                    <br />


                                    <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
                                        <?php
                                        function getUser($id, $conn)
                                        {
                                            $sql = "SELECT * FROM users WHERE id=?";
                                            $stmt = mysqli_prepare($conn, $sql);

                                            if ($stmt) {
                                                mysqli_stmt_bind_param($stmt, "i", $id);
                                                mysqli_stmt_execute($stmt);
                                                $result = mysqli_stmt_get_result($stmt);

                                                if (mysqli_num_rows($result) === 1) {
                                                    $user = mysqli_fetch_assoc($result);
                                                    return $user;
                                                } else {
                                                    echo "No user found with id: " . htmlspecialchars($id);
                                                    return [];
                                                }
                                            } else {
                                                echo "Failed to prepare statement.";
                                                return [];
                                            }
                                        }

                                        function getChats($id_1, $id_2, $con)
                                        {

                                            // Escape the input parameters to prevent SQL injection
                                            $id_1 = $con->real_escape_string($id_1);
                                            $id_2 = $con->real_escape_string($id_2);

                                            // Construct the SQL query
                                            $sql = "SELECT * FROM chats WHERE (from_id = $id_1 AND to_id = $id_2) OR (to_id = $id_1 AND from_id = $id_2) ORDER BY chat_id ASC";
                                            $result  = $con->query($sql);
                                            if ($result->num_rows > 0) {
                                                $chats = $result->fetch_all(MYSQLI_ASSOC);
                                                return $chats;
                                            } else {
                                                $chats = [];
                                                return $chats;
                                            }
                                        }

                                        function opened($id_1, $conn, $chats)
                                        {
                                            foreach ($chats as $chat) {
                                                if ($chat['opened'] == 0) {
                                                    $opened = 1;
                                                    $chat_id = $chat['chat_id'];

                                                    $sql = "UPDATE chats SET opened = $opened WHERE from_id = $id_1 AND chat_id = $chat_id";

                                                    // Execute the query
                                                    if ($conn->query($sql) === TRUE) {
                                                    } else {

                                                        echo "Error: " . $sql . "<br>" . $conn->error;
                                                    }
                                                }
                                            }
                                        }

                                        $chatWith = getUser($_GET['id'], $con);
                                        $chats = getChats($_SESSION['id'], $chatWith['id'], $con);
                                        opened($chatWith['id'], $con, $chats);



                                        ?>
                                        <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" style="padding: 4;" id="chatBox">
                                            <?php
                                            if (!empty($chats)) {
                                                foreach ($chats as $chat) {
                                                    if ($chat['from_id'] == $_SESSION['id']) { ?>
                                                        <p class="rtext align-self-end border rounded p-2 mb-1">
                                                            <?= $chat['message'] ?>
                                                            <small class="d-block">
                                                                <?= $chat['created_at'] ?>
                                                            </small>
                                                        </p>
                                                    <?php } else { ?>
                                                        <p class="ltext border  rounded p-2 mb-1">
                                                            <?= $chat['message'] ?>
                                                            <small class="d-block">
                                                                <?= $chat['created_at'] ?>
                                                            </small>
                                                        </p>
                                            <?php }
                                                }
                                            }  ?>
                                        </div>

                                        <div class="input-group mb-3">
                                            <textarea cols="3"
                                                id="message"
                                                class="form-control"></textarea>
                                            <button class="btn btn-primary"
                                                id="sendBtn">
                                                <!-- <i class="fa fa-paper-plane"></i> -->
                                                send
                                            </button>
                                        </div>


                                    </table>
                                </div>
                            </div>
                        </div><!--/.content-->
                    </div><!--/.span9-->
                </div>
            </div><!--/.container-->
        </div><!--/.wrapper-->

        <?php include('include/footer.php'); ?>

        <script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
        <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
        <script src="scripts/datatables/jquery.dataTables.js"></script>
        <script>
            $(document).ready(function() {
                $('.datatable-1').dataTable();
                $('.dataTables_paginate').addClass("btn-group datatable-pagination");
                $('.dataTables_paginate > a').wrapInner('<span />');
                $('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
                $('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
            });
        </script>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatBox = document.getElementById('chatBox');
                const messageInput = document.getElementById('message');
                const sendBtn = document.getElementById('sendBtn');
                const incomingId = <?= $chatWith['id']  ?>;

                loadMessages();

                sendBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    const message = messageInput.value;
                    if (message === '') return;

                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'ajax/insert.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            messageInput.value = '';
                            chatBox.innerHTML += xhr.responseText;
                            scrollDown();
                        }
                    };

                    const data = 'message=' + encodeURIComponent(message) + '&to_id=' + encodeURIComponent(incomingId);
                    xhr.send(data);
                });

                function loadMessages() {
                    const formData = new FormData();
                    formData.append('id_2', incomingId);

                    fetch('ajax/getMessage.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            chatBox.innerHTML = data;
                            scrollDown();
                        })
                        .catch(error => console.error("Error fetching messages:", error));
                }

                function scrollDown() {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        </script>
    </body>
<?php } ?>