<?php
// only server - error_reporting(0);
header('Content-type: aplication/json');
session_start();
session_regenerate_id();

require_once 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$pdo = new PDO('mysql:host=localhost;dbname=neurim_page;charset=utf8', 'root', '');

$email = $_POST['email'] ?? false;
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
$email = trim($email);

$phone = $_POST['phone'] ?? false;
$phone = filter_var($phone, FILTER_SANITIZE_STRING);
$phone = trim($phone);

if($email){

    $sql_email = $pdo->quote($email);
    $sql_phone = $pdo->quote($phone);
    $isset_day_email = $pdo->query("SELECT email FROM lid WHERE (email = $sql_email OR phone = $sql_phone) AND date >= DATE_SUB(NOW(),INTERVAL 1 DAY)")
                           ->fetch(PDO::FETCH_ASSOC);

}

if(empty($isset_day_email['email'])){

    if(isset($_POST['name']) && $email){

        if(isset($_POST['type']) && $phone){
            $valid_types = [
                'זוגי',
                'משפחתי',
                'קוטג'
            ];
            $phoneRegExp = "/^0[2-9]\d{7,8}$/";
            
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $name = trim($name);
            
            $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
            $type = trim($type);
            
            if(mb_strlen($name) > 2 && mb_strlen($name) < 70){
                
                if( $email ){
                    
                    if( preg_match($phoneRegExp, $phone) ){
    
                        if( in_array($type, $valid_types) ){
    /*
                            try{
                            $mail = new PHPmailer(true);
                            $mail->CharSet = 'UTF-8';
                            $mail->setFrom('Advertisingpage@neurim.co.il', 'Advertising page');
                            $mail->addAddress('proyektym@gmail.com', 'proyektym');
                            $mail->Subject = 'ליד חדש הגיע מדף הפרסום';
                            $mail->isHTML(true);
                            $mail->Body = "<h1>פרטי המעוניין: </h1>
                                           <p>שם: $name</p>
                                           <p>אימייל: $email</p>
                                           <p>טלפון: $phone</p>
                                           <p>סוג חדר: $type</p>
                                           ";
    
                            if($mail->send()){
*/
                                $_SESSION['email'] = $email;
                                $_SESSION['date'] = date('Ymd His', time() + 60 * 60);
                                $sql = "INSERT INTO lid VALUES(null, ?, $sql_email, $sql_phone, ?,NOW())";
                                $query = $pdo->prepare($sql);
                                $res = $query->execute([
                                    $name, 
                                    $type
                                    ]);
    
                                if($res){

                                    $response = [
                                        'status' => 'success',
                                    ];
                            
                                    echo json_encode($response);
                                    exit;

                                }
    
                            }
                           /* 
                            }catch(Exception $e){
                                
                                $response = ['status' => 'error 2'];
                                echo json_encode($response);
                                exit;
                            
                            }
                        }
                        */
    
                    }
    
                }
    
            }
    
        }
    
    }

}else{

    $response = [
        'status' => 'error 1',
        'text_error' => 'The email was sent today'
    ];
    
    echo json_encode($response);
    exit;

}

$response = [
    'status' => 'failure'
];

echo json_encode($response);