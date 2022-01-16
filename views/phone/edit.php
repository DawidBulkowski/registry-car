<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj telefony';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/phone/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracownik:</p>
                </div>
                <div class="col-md-9">
                    <p><?=$employee->getFullName();?></p>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Wartość netto:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" min="0" step="0.01" name="value" value="<?= $edit->value; ?>" class="form-control" placeholder="Wprowadź wartość netto" autocomplete="off">
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj dział</button>
            </div>

        </form>
    </div>

</div>
