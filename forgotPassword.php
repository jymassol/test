<?php
    if (isset($_POST["forgotPass"])){
        
        /*
         echo "Test"; 
      
         $connection = mysql_connect("localhost", "root", "yoda");
        
        if($connection){
            echo "yes";
            mysql_select_db($connection, "si");
        } else {
            die("Fail : " . $connection->connect_error);
            
           
        }
        */
        

        $connection = new mysqli("localhost", "root", "yoda", "si_V2");

        if($connection){
        
            $email = $connection->real_escape_string($_POST["email"]);

            $data = $connection->query("SELECT ipk_users FROM users WHERE user_email = '$email'");

            $expiration = strtotime('+ 60 seconds');

            if($data->num_rows > 0) {
                $str = "0213etgttyhy".date('l-M-Y-H-i-s-u')."";
                $str = str_shuffle($str);
                $str = substr($str, 0, 20);
                $url = "http://localhost/password_process/resetPassword.php?token=$str";
                // echo $url;
                // echo $str;
                // mail($email, "reset Password", "To reset your password, please visit this : $url", "From: myemail@email.com\r\n");

                // $mail = mail("jymassol@netmessage.com", "reset Password", "To reset your password, please visit this : $url");

                $connection->query("UPDATE users SET user_token='$str', user_token_creation_date='$expiration' WHERE user_email='$email'");

                echo "An email have been sent";
                /*
                try{

                    if($mail){
                        echo "Please check your email";
                        echo "token inserted";
                    } else {
                        echo "token inserted";
                    }

                } catch (error $e) {
                    echo $e;
                }
                */

            } else {
                echo "Please check your inputs"; 

                // echo "data: $data";
            }
        } else {
            die("Fail : " . $connection->connect_error);  
        }
        
    }
?>

<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <form  method="post" action="">
        <input type="text" name="email" placeholder="Email" /><br>
        <input type="submit" name="forgotPass" value="Request password" />
    </form>
</body>
</html>