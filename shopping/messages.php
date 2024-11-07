<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:login.php');
} else {
    // Code forProduct deletion from  wishlist	
    $wid = intval($_GET['del']);
    if (isset($_GET['del'])) {
        $query = mysqli_query($con, "delete from wishlist where id='$wid'");
    }


    if (isset($_GET['action']) && $_GET['action'] == "add") {
        $id = intval($_GET['id']);
        $query = mysqli_query($con, "delete from wishlist where productId='$id'");
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $sql_p = "SELECT * FROM products WHERE id={$id}";
            $query_p = mysqli_query($con, $sql_p);
            if (mysqli_num_rows($query_p) != 0) {
                $row_p = mysqli_fetch_array($query_p);

                $_SESSION['cart'][$row_p['id']] = array("quantity" => 1, "price" => $row_p['productPrice']);
                header('location:my-wishlist.php');
            } else {
                $message = "Product ID is invalid";
            }
        }
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keywords" content="MediaCenter, Template, eCommerce">
        <meta name="robots" content="all">

        <title>My Wishlist</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">

        <!-- Customizable CSS -->
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="assets/css/red.css">
        <link rel="stylesheet" href="assets/css/owl.carousel.css">
        <link rel="stylesheet" href="assets/css/owl.transitions.css">
        <!--<link rel="stylesheet" href="assets/css/owl.theme.css">-->
        <link href="assets/css/lightbox.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/animate.min.css">
        <link rel="stylesheet" href="assets/css/rateit.css">
        <link rel="stylesheet" href="assets/css/bootstrap-select.min.css">

        <!-- Demo Purpose Only. Should be removed in production -->
        <link rel="stylesheet" href="assets/css/config.css">

        <link href="assets/css/green.css" rel="alternate stylesheet" title="Green color">
        <link href="assets/css/blue.css" rel="alternate stylesheet" title="Blue color">
        <link href="assets/css/red.css" rel="alternate stylesheet" title="Red color">
        <link href="assets/css/orange.css" rel="alternate stylesheet" title="Orange color">
        <link href="assets/css/dark-green.css" rel="alternate stylesheet" title="Darkgreen color">
        <!-- Demo Purpose Only. Should be removed in production : END -->


        <!-- Icons/Glyphs -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">


        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />

        <!-- Fonts -->
        <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,700' rel='stylesheet' type='text/css'>
        <link rel="shortcut icon" href="assets/images/favicon.ico">

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

    <body class="cnt-home">
        <header class="header-style-1">

            <!-- ============================================== TOP MENU ============================================== -->
            <?php include('includes/top-header.php'); ?>
            <!-- ============================================== TOP MENU : END ============================================== -->
            <?php include('includes/main-header.php'); ?>
            <!-- ============================================== NAVBAR ============================================== -->
            <?php include('includes/menu-bar.php'); ?>
            <!-- ============================================== NAVBAR : END ============================================== -->

        </header>

        <!-- ============================================== HEADER : END ============================================== -->
        <div class="breadcrumb">
            <div class="container">
                <div class="breadcrumb-inner">
                    <ul class="list-inline list-unstyled">
                        <li><a href="home.html">Home</a></li>
                        <li class='active'>Message</li>
                    </ul>
                </div><!-- /.breadcrumb-inner -->
            </div><!-- /.container -->
        </div><!-- /.breadcrumb -->

        <div class="body-content outer-top-bd">
            <div class="container">
                <div class="my-wishlist-page inner-bottom-sm">
                    <div class="row">
                        <div class="col-md-12 my-wishlist">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>

                                    </thead>
                                    <tbody>

                                        <?php
                                        $sender_id = $_SESSION['id'];
                                        $sql = 'SELECT * FROM `admin`';
                                        $query_id = mysqli_query($con, $sql);
                                        $admin_id = [];
                                        if ($query_id) {
                                            foreach ($query_id as $row) {
                                                $admin_id = $row['id'];
                                            }
                                        } else {
                                            echo "Error: " . mysqli_error($con);
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

                                        function getChats($id_1, $id_2, $con)
                                        {

                                            // Escape the input parameters to prevent SQL injection
                                            $id_1 = $con->real_escape_string($id_1);
                                            $id_2 = $con->real_escape_string($id_2);

                                            // Construct the SQL query
                                            $sql = "SELECT * FROM chats WHERE (from_id = $id_1 AND to_id = $id_2) OR (to_id = $id_1 AND from_id = $id_2) ORDER BY chat_id ASC";
                                            $result  = $con->query($sql);
                                            if ($result->nums_rows > 0) {
                                                $chats = $result->fetch_all(MYSQLI_ASSOC);
                                                return $chats;
                                            } else {
                                                $chats = [];
                                                return $chats;
                                            }
                                        }

                                        $chats = getChats($_SESSION['id'], $admin_id, $con);


                                        opened($admin_id, $con, $chats);


                                        ?>
                                        <div style="max-width: 700px; width: 100%;">
                                            <section class="chat-area ">

                                                <div class="chat-box shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
                                                    <?php
                                                    if (!empty($chats)) {
                                                        foreach ($chats as $chat) {
                                                            if ($chat['from_id'] == $_SESSION['id']) { ?>
                                                                <p class="ltext border rounded p-2 mb-1">
                                                                    <?= $chat['message'] ?>
                                                                    <small class="d-block">
                                                                        <?= $chat['created_at'] ?>
                                                                    </small>
                                                                </p>
                                                            <?php } else { ?>
                                                                <p class="rtext align-self-end border rounded p-2 mb-1">
                                                                    <?= $chat['message'] ?>
                                                                    <small class="d-block">
                                                                        <?= $chat['created_at'] ?>
                                                                    </small>
                                                                </p>
                                                        <?php }
                                                        }
                                                    } else { ?>
                                                        <div class="alert alert-info  text-center">
                                                            <i class="fa fa-comments d-block fs-big"></i>
                                                            No messages yet, Start the conversation
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </section>



                                            <form class="typing-area" id="form_message">
                                                <input type="text" id="incoming_id" name="incoming_id" value="<?= $sender_id ?>" hidden>
                                                <!-- <input type="text" name="message" id="message" placeholder="Type a message here..." autocomplete="off"> -->
                                                <textarea name="message" id="message" class="form-control"></textarea>

                                                <button type="submit" id="sendBtn">Send</button>
                                            </form>
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.sigin-in-->
                <?php include('includes/brands-slider.php'); ?>
            </div>
        </div>

        <?php include('includes/footer.php'); ?>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const form_message = document.getElementById('form_message')
                form_message.addEventListener('submit', async (e) => {

                    e.preventDefault()
                    const message = document.querySelector('#message').value
                    const sender_id = document.querySelector('.incoming_id').value
                    const reciever_id = '<?= $admin_id ?>'
                    try {
                        const response = await fetch('http://localhost:5000/api/sendMessage', {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                message,
                                sender_id,
                                reciever_id
                            })
                        })
                        const data = await response.json();
                        document.querySelector('#message').value = ''
                    } catch (error) {
                        console.error(error)
                    }
                })
            })
        </script>

        <script src="assets/js/jquery-1.11.1.min.js"></script>

        <script src="assets/js/bootstrap.min.js"></script>

        <script src="assets/js/bootstrap-hover-dropdown.min.js"></script>
        <script src="assets/js/owl.carousel.min.js"></script>

        <script src="assets/js/echo.min.js"></script>
        <script src="assets/js/jquery.easing-1.3.min.js"></script>
        <script src="assets/js/bootstrap-slider.min.js"></script>
        <script src="assets/js/jquery.rateit.min.js"></script>
        <script type="text/javascript" src="assets/js/lightbox.min.js"></script>
        <script src="assets/js/bootstrap-select.min.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/scripts.js"></script>

        <!-- For demo purposes – can be removed on production -->

        <script src="switchstylesheet/switchstylesheet.js"></script>

        <!-- <script>
            $(document).ready(function() {
                $(".changecolor").switchstylesheet({
                    seperator: "color"
                });
                $('.show-theme-options').click(function() {
                    $(this).parent().toggleClass('open');
                    return false;
                });
            });

            $(window).bind("load", function() {
                $('.show-theme-options').delay(2000).trigger('click');
            });
        </script> -->

        <!-- <script>
            var scrollDown = function() {
                var chatBox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            };
            scrollDown();

            document.addEventListener('DOMContentLoaded', function() {
                var sendBtn = document.getElementById('sendBtn');
                sendBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    console.log('click');
                    var message = document.getElementById('message').value;
                    if (message === '') return;

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'ajax/insert.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            document.getElementById('message').value = '';
                            document.getElementById('chatBox').innerHTML += xhr.responseText;
                            scrollDown();
                        }
                    };

                    var data = 'message=' + encodeURIComponent(message) + '&to_id=' + encodeURIComponent(<?= $admin_id ?>);
                    xhr.send(data);
                });


                document.addEventListener("DOMContentLoaded", function() {
                    loadMessages();
                    fetchData();
                });

                let loadMessages = function() {
                    const id_2 = <?= $admin_id ?>;
                    const formData = new FormData();
                    formData.append("id_2", id_2);

                    fetch("ajax/getMessage.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("chatBox").innerHTML = data;
                            scrollDown();
                        })
                        .catch(error => console.error("Error fetching messages:", error));
                }

                let fetchData = function() {
                    const id_2 =  <?= $admin_id ?>;
                    const formData = new FormData();
                    formData.append("id_2", id_2);

                    fetch("ajax/getMessage.php", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById("chatBox").innerHTML += data;
                            if (data !== "") {
                                scrollDown();
                            }
                        })
                        .catch(error => console.error("Error fetching data:", error));
                }

            });
        </script> -->


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chatBox = document.getElementById('chatBox');
                const messageInput = document.getElementById('message');
                const sendBtn = document.getElementById('sendBtn');
                const incomingId = <?= $admin_id ?>;

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

    </html>
<?php } ?>