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

<form action="/mvc/account/save" method="post">
  <div class="form-group">
    <label for="email">Email: </label>
    <input type="email" class="form-control" name="email">
  </div>
  <div class="form-group">
    <label for="name">FullName: </label>
    <input type="text" class="form-control" id="name" name="name">
  </div>
  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" id="password" name="password">
  </div>
  <div class="form-group">
    <label for="confirmPassword">Confirm Password</label>
    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
  </div>

  <br>
  <button type="submit" class="btn btn-primary">Register</button>
</form>
</div>

<?php
include_once 'app/views/share/footer.php'
?>