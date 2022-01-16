<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj typ samochodu';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/typecar/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Nazwa:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="name" value="<?=$edit->name;?>" class="form-control" placeholder="Wprowadź nazwę" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Czy użytkowany?</p>
                </div>
                <div class="col-md-9">
                    <select name="active" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?php if($edit->active == 0) echo 'selected'; ?>>Nie aktywny</option>
                        <option value="1" <?php if($edit->active == 1) echo 'selected'; ?>>Aktywny</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj typ samochodu</button>
            </div>

        </form>
    </div>

</div>
