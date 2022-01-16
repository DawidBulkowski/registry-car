<?php

  use yii\helpers\Url;
  $this->title = 'Dodaj pracownika';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <?php if($error != ''){ ?>
        <div class="row">
            <div class="col-md-12 alert alert-danger" role="alert">
                <?=$error;?>
            </div>
        </div>
        <?php } ?>

        <form action="<?= Url::to(['/employee/add']) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Imię:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>" class="form-control" placeholder="Wprowadź imię" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Nazwisko:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="surname" value="<?php echo isset($_POST["surname"]) ? $_POST["surname"] : ''; ?>" class="form-control" placeholder="Wprowadź nazwisko" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Kontrahent w PIFirma:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" name="kontrahent" value="<?php echo isset($_POST["kontrahent"]) ? $_POST["kontrahent"] : ''; ?>" class="form-control" placeholder="Wprowadź ID kontrahent PiFirma" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Login:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="login" value="<?php echo isset($_POST["login"]) ? $_POST["login"] : ''; ?>" class="form-control" placeholder="Wprowadź login" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Hasło:</p>
                </div>
                <div class="col-md-9">
                    <input type="password" name="pass" class="form-control" placeholder="Wprowadź hasło" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Typ:</p>
                </div>
                <div class="col-md-9">
                    <select name="type" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?= isset($_POST["type"]) && $_POST["type"] == '0' ? 'selected' : '';?>>Kierowca</option>
                        <option value="1" <?= isset($_POST["type"]) && $_POST["type"] == '1' ? 'selected' : '';?>>Menadżer</option>
                        <option value="2" <?= isset($_POST["type"]) && $_POST["type"] == '2' ? 'selected' : '';?>>Administrator</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracuje?:</p>
                </div>
                <div class="col-md-9">
                    <select name="work" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?= isset($_POST["work"]) && $_POST["work"] == '0' ? 'selected' : '';?> >Nie pracuje</option>
                        <option value="1" <?= isset($_POST["work"]) && $_POST["work"] == '1' ? 'selected' : '';?> >Pracuje</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj pracownika</button>
            </div>

        </form>
    </div>

</div>
