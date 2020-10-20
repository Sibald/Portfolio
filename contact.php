<?php

// Show all errors (for educational purposes)
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// Constanten (connectie-instellingen databank)
define ('DB_HOST', 'localhost');
define ('DB_USER', 'contact');
define ('DB_PASS', 'Groenten98');
define ('DB_NAME', 'contact');

date_default_timezone_set('Europe/Brussels');

// Verbinding maken met de databank
try {
    $db = new PDO('mysql:host=' . DB_HOST .';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Verbindingsfout: ' .  $e->getMessage();
    exit;
}



$name = isset($_POST['name']) ? (string) $_POST['name'] : '';
$email = isset($_POST['email']) ? (string) $_POST['email'] : '';
$message = isset($_POST['message']) ? (string) $_POST['message'] : '';
$msgName = '*';
$msgMessage = '*';

// form is sent: perform formchecking!
if (isset($_POST['btnSubmit'])) {

	$allOk = true;

	// name not empty
	if (trim($name) === '') {
		$msgName = 'Gelieve een naam in te voeren';
		$allOk = false;
	}
    if (trim($email) === '') {
        $msgMessage = 'Gelieve uw email in te voeren';
        $allOk = false;
    }
	if (trim($message) === '') {
		$msgMessage = 'Gelieve een boodschap in te voeren';
		$allOk = false;
	}



	// end of form check. If $allOk still is true, then the form was sent in correctly
	if ($allOk) {
		// build & execute prepared statement
		$stmt = $db->prepare('INSERT INTO messages (sender, email, message, added_on) VALUES (?, ?, ?, ?)');
		$stmt->execute(array($name, $email, $message, (new DateTime())->format('Y-m-d H:i:s')));

		// the query succeeded, redirect to this very same page
		if ($db->lastInsertId() !== 0) {
			header('Location: formchecking_thanks.php?name=' . urlencode($name));
			exit();
		}

		// the query failed
		else {
		    echo 'Databankfout.';
		    exit;
		}		

	}

}

?><!DOCTYPE html>
<html>
<head>
	<title>Testform</title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="assets/css/contact_style.css" />
</head>
<header><?php include 'navbar.php' ?></header>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

		<fieldset>

			<h2>Contact Me!</h2>

			<dl class="clearfix">

				<dt><label for="name">Name</label></dt>
				<dd class="text">
					<input type="text" id="name" name="name" value="<?php echo htmlentities($name); ?>" class="input-text" />
					<span class="message error"><?php echo $msgName; ?></span>
				</dd>
                <dt><label for="email">Email</label></dt>
                <dd class="text">
                    <input type="email" id="email" name="email" value="<?php echo htmlentities($email); ?>" class="input-text" />
                    <span class="message error"><?php echo $msgName; ?></span>
                </dd>

				<dt><label for="message">Message</label></dt>
				<dd class="text">
					<textarea name="message" id="message" rows="5" cols="40"><?php echo htmlentities($message); ?></textarea>
					<span class="message error"><?php echo $msgMessage; ?></span>
				</dd>



			</dl>
		</fieldset>
        <section class="form-elem">
            <h2>How did you find me?</h2>
            <ul>
                <li>
                    <div>
                        <input type="checkbox" name="findmeradio" value="gquery">
                        <label for="gquery">Google query</label>
                    </div>
                </li>

                <li>
                    <div>
                        <input type="checkbox" name="findmeradio" value="socialmedia">
                        <label for="socialmedia">Social media</label>
                    </div>
                </li>

                <li>
                    <div>
                        <input type="checkbox" name="findmeradio" value="friends">
                        <label for="friends">Friends</label>
                    </div>
                </li>

                <li>
                    <div>
                        <input type="checkbox" name="findmeradio" value="other">
                        <label for="other">Other</label>
                    </div>
                </li>
            </ul>
        </section>
        <dt class="full clearfix" id="lastrow">
            <input type="submit" id="btnSubmit" name="btnSubmit" value="Send" />
        </dt>

	</form>


</body>
</html>