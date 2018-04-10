<?php


    if(isset($_GET["token"])) {

        $connection = new mysqli("localhost", "root", "yoda", "si_V2");

        if($connection){

            $token = $connection->real_escape_string($_GET["token"]);

            $data = $connection->query("SELECT ipk_users FROM users WHERE user_token = '$token'");

            $expiration = 60*60; // 60 secondes

            $token_date = $connection->query("SELECT user_token_creation_date FROM users WHERE user_token = '$token'");


            $newpassword = $connection->real_escape_string($_POST["newpassword"]);

            $confirmpassword = $connection->real_escape_string($_POST["confirm-password"]);

            $pass = $connection->query("SELECT user_password FROM users WHERE user_token = '$token'");


            $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/";

            $success = preg_match($pattern, $newpassword);


            if($data->num_rows > 0) {

                if (isset($_POST["resetPass"])){

                    while($row_token_creation_date = $token_date->fetch_assoc()){

                        if($row_token_creation_date['user_token_creation_date'] > time()){

                            while($row_password = $pass->fetch_assoc()){

                                if($row_password["user_password"] !== $newpassword){


                                    if ($newpassword === $confirmpassword) {


                                        if($newpassword){ // en attendant la regex

                                            if($success){


                                                while($row = $data->fetch_assoc()){

                                                    $connection->query("UPDATE users SET user_token='', user_password='$newpassword' WHERE ipk_users=".$row["ipk_users"]."");

                                                    echo "Change has been made";
                                                }
                                        
                                            } else {
                                               echo "Erreur le mot de passe doit avoir au minimum une longueur de 8 caractères et contenir une lettre minuscule(a-z), une majuscule (A-Z), un chiffre (0-9), un caratère spécial (!,@,#,$,%,^,&,*) ";
                                            }


                                        } else {
                                            "you must enter a password";
                                        }
                                    
                                    } else {
                                        echo "password  and confirm password must be the same";
                                    }

                                } else {
                                    echo "new password cannot be the previous password";
                                }
                                
                            }

                        } else {

                            echo "your sesion expired";
                        }
                    }
                }

            } else {
                echo "Please check your link!!"; 
            }

        } else {
            die("Fail : " . $connection->connect_error);  
        }
    
    } else {
        header("Location: login.php");
        exit();
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
        <input type="text" name="newpassword" placeholder="Password" /><br>
        <input type="text" name="confirm-password" placeholder="Confirm Password" /><br>
        <input type="submit" name="resetPass" value="Change password" />
    </form>
</body>
</html>