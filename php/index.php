<?php 

$post = false;
$login      = null;
$name       = null;
$password   = null;
$realm      = null;
$hash       = null;

if(isset($_POST) && $_POST)
{
    $post = true;

    $name       = $_POST['inputName'];
    $login      = $_POST['inputLogin'];
    $password   = $_POST['inputPassword'];
    $realm      = $_POST['inputRealm'];

    if( $password &&
        $realm)
    {
        include "pbkdf2.php";
        $rounds = 1000;

        $encode = implode(':', array($name, $login, $password, $realm));
        $salt = 'abc';

        $hash = base64_encode(pbkdf2('sha512', $encode, $salt, $rounds, 64, true));
    }

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Hash Generator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class="container">
        <h1>Hash My Password</h1>
        <form class="form-horizontal" method="post">
            <fieldset>
                <legend></legend>
  <div class="control-group">
    <label class="control-label" for="inputLogin">Login</label>
    <div class="controls">
      <input type="text" name="inputLogin" placeholder="Login" value="<?php echo htmlentities($login); ?>" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputName">Name</label>
    <div class="controls">
      <input type="text" name="inputName" placeholder="Name" value="<?php echo htmlentities($name); ?>" />
    </div>
  </div>
  <div class="control-group <?php if($post && !$password) echo 'error' ?>">
    <label class="control-label" for="inputPassword">Password</label>
    <div class="controls">
      <input type="password" name="inputPassword" placeholder="Password" value="<?php //echo htmlentities($password);?>" />
    </div>
  </div>
  <div class="control-group <?php if($post && !$realm) echo 'error' ?>">
    <label class="control-label" for="inputRealm">Website or App</label>
    <div class="controls">
      <input type="text" name="inputRealm" placeholder="facebook.com" value="<?php echo htmlentities($realm); ?>" />
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
            <button type="submit" class="btn">Generate</button>
    </div>
  </div>
            </fieldset>
                <?php if($hash): ?>
                <fieldset>
                <legend>Your Hash for <?php echo $realm; ?></legend>
       <input type="text" name="hash" class="input-xxlarge" value="<?php echo htmlentities($hash); ?>" />
            </fieldset>
                <?php endif ?>
        </form>
    </div>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.js"></script>
    <script type="text/javascript">
    $('input[name="hash"]').select();
    </script>
  </body>
</html>
