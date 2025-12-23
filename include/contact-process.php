<!-- <?php

$recipient = 'info@shelayuen.site';
$name = $_POST['name'];
$email = $_POST['email'];
$subject = $_POST['subject'];
$message = $_POST['message'];

if (isset($_POST['email'])) {	
	if (preg_match('(\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,})', $_POST['email'])) {
		$msg = 'E-mail address is valid';
	} else {
		$msg = 'Invalid email address';
	}

  $ip = getenv('REMOTE_ADDR');
  $host = gethostbyaddr($ip);	
  $mess = "Name: ".$name."\n";
  $mess .= "Email: ".$email."\n";
  $mess .= "Message: ".$message."\n\n";
  $mess .= "IP:".$ip." HOST: ".$host."\n";
  
  $headers = "From: <".$email.">\r\n"; 
  
   if(isset($_POST['url']) && $_POST['url'] == ''){

       $sent = mail($recipient, $subject, $mess, $headers); 
} 

} else {
	die('Invalid entry!');
}
?> -->


<?php
// 1. Setup configuration
$recipient = 'info@shelayuen.site';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 2. Honeypot Check (Spam Filter)
    // Ensure 'url' is present in your HTML but hidden with CSS
    if (!isset($_POST['url']) || $_POST['url'] !== '') {
        die("Spam detected."); 
    }

    // 3. Time Gate (Spam Filter)
    // Bots submit forms in milliseconds. Humans take seconds.
    $load_time = $_POST['form_load_time'] ?? 0;
    if (time() - $load_time < 3) {
        die("Form submitted too fast. Are you a bot?");
    }

    // 4. Sanitize and Validate Inputs (Injection Protection)
    // strip_tags removes HTML; trim removes whitespace
    $name    = strip_tags(trim($_POST['name'] ?? ''));
    $email   = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST['subject'] ?? ''));
    $message = strip_tags(trim($_POST['message'] ?? ''));

    // Validate Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // 5. Prevent Header Injection
    // Remove newlines from inputs that go into headers
    $name = str_replace(array("\r", "\n"), '', $name);
    $email = str_replace(array("\r", "\n"), '', $email);

    if (empty($errors)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $host = gethostbyaddr($ip); 
        
        $mess = "Name: $name\n";
        $mess .= "Email: $email\n";
        $mess .= "Message:\n$message\n\n";
        $mess .= "IP: $ip\nHOST: $host\n";
        
        // Secure Headers
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        if (mail($recipient, $subject, $mess, $headers)) {
            echo "Message sent successfully!";
        } else {
            echo "Server error. Please try again later.";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
} else {
    die('Invalid request method.');
}
?>