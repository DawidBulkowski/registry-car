<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj ustawienie';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/settings/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis:</p>
                </div>
                <div class="col-md-9">
                    <p><?= $edit->description; ?></p>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Wartość:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="value" value="<?= $edit->value; ?>" class="form-control" placeholder="Wprowadź wartość" autocomplete="off" required>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj ustawienie</button>
            </div>

        </form>
    </div>

</div>
