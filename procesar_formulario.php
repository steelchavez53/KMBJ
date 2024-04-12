<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $recaptcha_secret_key = "6Ld01zIpAAAAAKCHTW995O4gQJz74HKAj5uklnWB"; 
                                
    $recaptcha_response = $_POST['g-recaptcha-response'];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_data = array(
        'secret' => $recaptcha_secret_key,
        'response' => $recaptcha_response   
    );  

    $recaptcha_options = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($recaptcha_data)
        )
    );

    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_result = json_decode($recaptcha_result, true);

    // Verifica si reCAPTCHA fue exitoso
    if (!$recaptcha_result['success']) {
      
        header("Location: formulario_contacto.php?error=recaptcha");
        exit();
    }
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $company = $_POST['company'];
    $industry = $_POST['industry'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address1 = $_POST['address1'];
    $city = $_POST['city'];
    $stateprovince = $_POST['stateprovince'];
    $postal = $_POST['postal'];
    $message = $_POST['message'];
    $newsletter_signup = isset($_POST['newsletter_signup']) ? $_POST['newsletter_signup'] : 'No';
    $nearest_ims_location = $_POST['nearest_ims_location'];

    // Create a PHPMailer object
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'mail.kmbjironworks.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'sales@kmbjironworks.com'; 
        $mail->Password = 'F,hKNNy^4h6k'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('yefferson@kmbjironworks.com', 'kmbjironworks'); 
        $mail->addAddress('sales@kmbjironworks.com'); 
        $mail->addCC($email); 
    

        // Attach the file
        $file_attached = isset($_FILES['file']) ? $_FILES['file'] : null;

        if ($file_attached && $file_attached['error'] == UPLOAD_ERR_OK) {
            $file_name = $file_attached['name'];
            $file_tmp = $file_attached['tmp_name'];

            // Add the file as an attachment
            $mail->addAttachment($file_tmp, $file_name);
        }

        // Content
        $mail->isHTML(false); 
        $mail->Subject = 'New contact form';
        $mail->Body = "Name: $firstname $lastname\n" .
                      "Company: $company\n" .
                      //"Industria: $industry\n" .
                      "E-mail: $email\n" .
                      "Phone: $phone\n" .
                      "Address : $address1\n" .
                      "City: $city\n" .
                      "State: $stateprovince\n" .
                      "Postal Code: $postal\n" .
                      "Message: $message\n";
                      //"Suscripci贸n al bolet铆n: $newsletter_signup\n" .
                      //"Ubicaci贸n IMS m谩s cercana: $nearest_ims_location\n";

        // Send the email
        $mail->send();
    
 echo json_encode(["success" => true]);
exit();



} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $mail->ErrorInfo]);
    exit();
}
}
?>