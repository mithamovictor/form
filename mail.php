<?php
$message = '';
$messageClass='';
$name = '';
$email = '';
$comment = '';
$error = false;
$price1 = '';
$price2 = '';
$price3 = '';
$price4 = '';
$price5 = '';
$price6 = '';
$price7 = '';
$price8 = '';
$price9 = '';
$price10 = '';
$numPriceFields = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if(isset($_POST['submitForm'])){
    $name = strip_tags(trim($_POST["name"]));
    $name = str_replace(array("\r","\n"),array(" "," "),$name);
    $numPriceFields = $_POST['numPriceFields'];
    for($i=1; $i<=$numPriceFields; $i++){
      ${'price'.$i} = $_POST['price'.$i];
    }
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $comment = trim($_POST["comment"]);
    $error = false;
    $message = '';
    $messageClass = 'success';

    if ( empty( $name ) ) {
      $error = true;
      $nameError = '<div class="err">This field cannot be left empty</div>';
    }

    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
      $error = true;
      $emailError = '<div class="err">This field cannot be left empty</div>';
    }

    if ( empty( $comment ) ) {
      $error = true;
      $commentError = '<div class="err">This field cannot be left empty</div>';
    }

    if( !$error ){
      $recipient = "victor@orrasis.com";
      $subject = "New message from $name";
      $email_content = "Name: $name\n";
      $email_content .= "Email: $email\n\n";
      for($i=1; $i<=$numPriceFields; $i++){
        $email_content .= "price " . $i . ": ${'price'.$i}";
      }
      $email_content .= "Message:\n$comment\n";
      $email_headers = "From: $name <$email>";

      // Send the email.
      $sendMail = mail($recipient, $subject, $email_content, $email_headers);


      if ($sendMail) {
        // http_response_code(200);
        $message      = "Thank You! Your message has been sent.";
        $messageClass = 'success';
        $name         = '';
        $email        = '';
        $comment      = '';
        for($i=1; $i<=$numPriceFields; $i++){
          ${'price'.$i} = '';
        }
      } else {
        // http_response_code(500);
        $message = "Oops! Something went wrong and we couldn't send your message.";
        $messageClass='error';
      }
    } else {
      $message = "Oops! There was a problem with your submission. Please complete the form and try again.";
      $messageClass='error';
      $error = true;
    }
  } else if(isset($_POST['addPrice'])){
    $numPriceFields = $_POST['numPriceFields'];
    $maxFields = 10;
    if ( $numPriceFields < $maxFields ) {
      for($i=1; $i<=$numPriceFields; $i++){
        ${'price'.$i} = $_POST['price'.$i];
      }
      $numPriceFields++;
    }
  } else if(isset($_POST['removePrice'])){
    $numPriceFields = $_POST['numPriceFields'];
    for($i=1; $i<=$numPriceFields; $i++){
      if($i >= $_POST['removePrice'] && 
         $i !== $numPriceFields){
        ${'price'.$i} = $_POST['price'.($i+1)];
      } else  if($i < $_POST['removePrice']){
        ${'price'.$i} = $_POST['price'.$i];
      }
    }
    $numPriceFields--;
  }
} else {
  $message        = '';
  $messageClass   = '';
  $name           = '';
  $email          = '';
  $comment        = '';
  $error          = false;
  for($i=1; $i<=$numPriceFields; $i++){
    ${'price'.$i} = '';
  }
  $numPriceFields = 1;
} ?>

<form id="contact-form" method="post" action="/" novalidate>
  <div id="form-messages" class="<?php echo $messageClass; ?>">
    <?php echo $message; ?>
  </div>
  <div class="wrapper">
    <div class="input-container">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" value="<?php echo $name; ?>" required><?php
      if ( $error ) :
        echo $nameError; 
      endif; ?>
    </div>
    <div class="input-container">
      <label for="email">Email</label>
      <input type="text" name="email" id="email" value="<?php echo $email; ?>" required><?php
      if ( $error ) :
        echo $emailError; 
      endif; ?>
    </div>
    <div class="price-container"><?php
      for($i=1; $i<=$numPriceFields; $i++){ ?>
        <div class="input-container price-input">
          <label for="price<?php echo $i; ?>">
            Price <?php echo $i;
            if($i > 1){
              echo ' ( <button id="remove-button-' . $i . '" class="button remove-button" type="submit" name="removePrice" value="'. $i . '">X</button> )';
            } ?>
          </label>
          <input id="price<?php echo $i; ?>" class="price-input" type="number" min="0.00" max="10000000.00" step="0.01" placeholder="0.00" name="price<?php echo $i; ?>" value="<?php echo ${'price'.$i}; ?>"/>
        </div><?php
      } ?>
      
      <input type="hidden" value="<?php echo $numPriceFields; ?>" name="numPriceFields">
    </div>
    <div class="button-container">
      <input id="add-button" class="button add-button" type="submit" name="addPrice" value="Add Price">
    </div>
    
    <div class="input-container">
      <label for="comments">Comments</label>
      <textarea rows="10" name="comment" id="comment" required><?php echo $comment; ?></textarea>
    </div><?php
      if ( $error ) :
        echo $commentError; 
      endif; ?>
    <div class="button-container">
      <input id="submitForm" type="submit" name="submitForm" value="Submit" class="button">
    </div>
  </div>
</form>