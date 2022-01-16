<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj dział';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/section/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Nazwa:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" value="<?= $edit->name; ?>" class="form-control" placeholder="Wprowadź nazwę" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="description" value="<?= $edit->description; ?>" class="form-control" placeholder="Wprowadź opis" autocomplete="off">
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj dział</button>
            </div>

        </form>
    </div>

</div>
