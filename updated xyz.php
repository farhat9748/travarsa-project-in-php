Updated secure code for xyz.php

                                                            
<?php                                                           
session_start();

if (!isset($_SESSION['csrf_token'])) {                                      
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));           
}                                                                                                 
?>
<!DOCTYPE html>
<html>                          
<body>                      
<h2>Legitimate Form (travarsa.com)</h2>     
<form action="save.php" method="post">      
    <input type="text"                      
           name="name"                      
           placeholder="Enter Name">            
    <input type="hidden"                
           name="csrf_token"                    
           value="<?php echo $_SESSION['csrf_token']; ?>">
    <button type="submit">Submit</button> 
</form>
<hr>
<h3>For Teaching Only</h3>
<p>Current CSRF Token:</p>
<pre><?php echo $_SESSION['csrf_token']; ?></pre>
</body>
</html>