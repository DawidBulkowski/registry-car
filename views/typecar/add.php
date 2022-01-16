<?php

  use yii\helpers\Url;
  $this->title = 'Dodaj typ samochodu';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/typecar/add']) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Nazwa:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" value="<?php echo isset($_POST["name"]) ? $_POST["name"] : ''; ?>" class="form-control" placeholder="Wprowadź nazwę" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Aktywne?</p>
                </div>
                <div class="col-md-9">
                    <select name="active" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?= isset($_POST["active"]) && $_POST["active"] == '0' ? 'selected' : '';?> >Nie aktywne</option>
                        <option value="1" <?= isset($_POST["active"]) && $_POST["active"] == '1' ? 'selected' : '';?> >Aktywne</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj typ samochodu</button>
            </div>

        </form>
    </div>

</div>
