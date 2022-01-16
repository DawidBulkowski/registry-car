<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj pracownika';

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

        <form action="<?= Url::to(['/employee/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Imię:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" value="<?=$edit->name;?>" class="form-control" placeholder="Wprowadź imię" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Nazwisko:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="surname" value="<?=$edit->surname;?>" class="form-control" placeholder="Wprowadź nazwisko" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Kontrahent w PIFirma:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" name="kontrahent" value="<?=$edit->kontrahent;?>" class="form-control" placeholder="Wprowadź ID kontrahent PiFirma" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Login:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="login" value="<?=$edit->login;?>" class="form-control" placeholder="Wprowadź login" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Hasło:</p>
                </div>
                <div class="col-md-9">
                    <input type="password" name="pass" class="form-control" placeholder="Wprowadź hasło" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Typ:</p>
                </div>
                <div class="col-md-9">
                    <select name="type" class="form-control">
                        <option value="0" <?php if($edit->access == 0) echo 'selected'; ?> >Kierowca</option>
                        <option value="1" <?php if($edit->access == 1) echo 'selected'; ?> >Menadżer</option>
                        <option value="2" <?php if($edit->access == 2) echo 'selected'; ?> >Administrator</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracuje?:</p>
                </div>
                <div class="col-md-9">
                    <select name="work" class="form-control">
                        <option value="0" <?php if($edit->works == 0) echo 'selected'; ?> >Nie pracuje</option>
                        <option value="1" <?php if($edit->works == 1) echo 'selected'; ?> >Pracuje</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj pracownika</button>
            </div>

        </form>
    </div>

</div>
