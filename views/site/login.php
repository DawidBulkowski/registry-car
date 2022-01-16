<?php

  use yii\helpers\Url;
  $this->title = 'Zaloguj do systemu';

?>

<div class="site-index">

    <div class="col-md-4 offset-md-4 login">

        <?php if($error != ''){ ?>
        <div class="row">
            <div class="col-md-12 alert alert-danger" role="alert">
                <?=$error;?>
            </div>
        </div>
        <?php } ?>

        <form action="<?= Url::to(['/site/login']) ?>" method="post">

            <?php if(isset($_GET['redirect'])) { ?><input type="hidden" name="redirect" value="<?=$_GET['redirect'];?>" /><?php } ?>

            <div class="form-group">
                <label for="loginForm">Login</label>
                <input type="text" name="login" class="form-control" id="loginForm" placeholder="Wprowadź login" required>
            </div>

            <div class="form-group">
                <label for="passwordForm">Hasło</label>
                <input type="password" name="password" class="form-control" id="passwordForm" placeholder="Wprowadź hasło" required>
            </div>

            <button type="submit" class="btn btn-primary">Zaloguj do systemu</button>

        </form>
    </div>

</div>
