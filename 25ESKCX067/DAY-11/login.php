<?php
include("header.php");
include("db_connect.php");
include("checkloginError.php");
?>
<div class="container mt-5" style="max-width:400px;">
    <form action="" method="post">
        <h3 class="mb-3">login</h3>
     
        <input type="email" class="form-control mb-3" name="email" placeholder="email" value ="<?=$email?>" >
       
        <input type="password" class="form-control mb-3" name="password" placeholder="password" value ="<?=$password?>" >
        
       
      
        <button class="btn btn-primary w-100">Login</button>
    </form>
    
</div>    
<?php
include("footer.php");
?>