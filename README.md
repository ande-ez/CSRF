# Cross Site Request Forgery (CSRF) protection class

## Author
This repository is created by <ande@evilzone.org>  
Feel free to use it as you want

## Introduction
This is a class to easily protect against CSRF attacks.

## Usage
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
