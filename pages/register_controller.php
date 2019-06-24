<?php

session_start();

$errors = array();
/**
 * This controller concerns registration 
 * 
 */
if (!isset($_SESSION["user"]["name"])) {

    if (isset($_GET['action']) == false) {

        header("Location: ./register_controller.php?action=login");
    } else {
        switch ($_GET['action']) {
            case 'register':

                require_once '../view/register_view.php';
                break;
            case 'login':
               // $_GET['success']='success';
                require_once '../view/login_view.php';

                break;
            case 'registration':

                $firstname = filter_input(INPUT_POST, "firstname");
                $lastname = filter_input(INPUT_POST, "lastname");
                $email = filter_input(INPUT_POST, "email");
                $password = filter_input(INPUT_POST, "pwd");
                $ComfirmPassword = filter_input(INPUT_POST, "confirmPwd");
                /* var_dump($ComfirmPassword);
                  var_dump($password);
                  var_dump($email);
                  var_dump($lastname);
                  var_dump($firstname); */
                include '../model/Form_Process.php';


                // $format_name = Form_Process::format_name($firstname, $lastname);
                if (empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($ComfirmPassword)) {

                    $errors['empty'] = 'Please fill out all fields completely. ';
                } else {

                    $errors = Form_process::register_format($firstname, $lastname, $password, $ComfirmPassword);

                    if (count($errors) === 0) {

                        try {
                            include '../model/Mail_process.php';
                            include '../model/PersonDao.php';
                            $token = Mail_process::str_random(45);
                            $user_id = PersonDao::insert($firstname, $lastname, $email, $password, $token);


                            if (!isset($token) || strlen($token) != 45 || !isset($user_id)) {

                                $ex = "token or user not define";
                                $errors['usertoken'] = $ex;
                                include '../view/error_view.php';
                            } else {

                                //$_GET['success'] = 'success';

                                //  mail($email, 'Confirmation de votre compte', "Afin de valider votre compte, merci de cliquer sur ce lien\n\n http://
                                header("Location: ./login-success");
                            }
                        } catch (PDOException $ex) {
                            $errors['mailexisting'] = "Your e-mail address is already used by another account!";
                        }
                    }
                }
                /*   foreach ($errors as $er){
                  //echo $er;
                  } */

                //var_dump($errors);
                require_once '../view/register_view.php';
                break;

            case 'registered':

                $person_id = $_GET['id'];
                $token = $_GET['token'];
                //echo "$person_id";
                //echo "<br> $token<br>";
                if (!isset($token) || strlen($token) != 45 || !isset($person_id)) {
                    //echo'Votre lien n\'est plus valide ';
                    $errors['tokenorid'] = 'Your link is not valid anymore';
                    include '../view/error_view.php';
                } else {
                    try {
                        include '../model/PersonDao.php';
                        $valid = PersonDao::valid($person_id, $token);
                        if ($valid == 1) {
                            PersonDao::valid($person_id, $token);
                            
                            // can be in buble/pop-up
                            echo 'Your account was validated. You can login now.';
                            include '../view/login_view.php';
                        } else {
                            //echo 'Votre lien n\'est plus valide dao';
                            $errors['invalidlink'] = 'Your link is not valid anymore';
                            include '../view/error_view.php';
                        }
                    } catch (PDOException $ex) {
                        $errors['PDOException'] = 'Your link is not valid anymore' . $ex;
                        include '../view/error_view.php';
                    }
                }

                break;
            default:
                //header("Location: ./");
                //echo 'The page you have requested cannot be found, You are going be redirected automaticaly to the Home page';
                header("Location: ./");
                break;
        }
    }
} else {

    header("Refresh: 2;URL=./");
}



