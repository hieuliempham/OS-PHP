<?php
include_once 'app/views/share/header.php'
?>

<div class="row">

    <h1>
        <?php if(isset($errors))
            {
                echo '<ul>';
                foreach($errors as $error){
                    echo '<li class="text-danger">'.$error.'</li>';
                }
                echo '</ul>';
            }
            ?>
    </h1>

<form action="/mvc/account/checkLogin" method="post">
  <div class="form-group">
    <label for="email">Email: </label>
    <input type="email" class="form-control" name="email">
  </div>
  
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  

  <br>
  <button type="submit" class="btn btn-primary">Login</button>
</form>
</div>

<?php
include_once 'app/views/share/footer.php'
?>