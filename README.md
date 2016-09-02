# Cross Site Request Forgery (CSRF) protection class


## Author
This repository is created by <ande@evilzone.org>  
  
Feel free to use it as you want  


## Introduction
This is a class to easily protect against CSRF attacks.  
  
It uses the PHP session engine to store a randomly generated token string of 100 characters.
  
This token is then placed in your POST form or in the URL and verifies that the request came from your server.  
  
Each generated token can only be used once.

### GET requests caution
Make sure the token is not stolen. If it is, anyone could use it. Be careful when using this in GET requests because of the referer header in the HTTP protocol when loading remote resources on a page.


## Function overview
### Public
* public static function init()
* public static function validatePost()
* public static function validateGet()
* public static function tokenInput()
* public static function getToken()

### Private
* private static function generateNewToken()
* private static function validateToken($token)


## Usage
### Initializing
You must call the init function somewhere in your code before using the class.
```PHP
CSRF::init();
```

### POST requests
#### HTML
```HTML
<form action="" method="POST">

  <input type="text" name="username">
  <input type="password" name="password">
  <input type="submit" value="Login">
  
  <?php print CSRF::tokenInput(); ?>
  
</form>
```
#### PHP
```PHP
if(isset($_POST['username']) && isset($_POST['password'])) {

  if(!CSRF::validatePost()) {
    die('Invalid CSRF token');
  }
  
  // Logic

}
```

### GET requests
#### HTML
```HTML
<a href="index.php?page=logout&token=<?php print CSRF::getToken(); ?>">Logout</a>
```
#### PHP
```PHP
// Is logged in logic

if(CSRF::validateGet()) {
    // Logout user and redirect
} else {
    die('Token out of balance');
}
```


## Future plans
- Add support for timeout and make the token one-time-use an option
