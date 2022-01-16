<?php

  use yii\helpers\Url;
  $this->title = 'Dodaj telefony';

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">

        <form action="<?= Url::to(['/phone/add']) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracownik:</p>
                </div>
                <div class="col-md-9">
                    <select name="employee" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($employees as $employee) : ?>
                            <option value="<?=$employee->id?>" <?= isset($_POST["employee"]) && $_POST["employee"] == $employee->id ? 'selected' : '';?> ><?=$employee->getFullName();?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Wartość netto:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" min="0" step="0.01" name="value" value="<?php echo isset($_POST["value"]) ? $_POST["value"] : ''; ?>" class="form-control" placeholder="Wprowadź wartość netto" autocomplete="off">
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj telefony</button>
            </div>

        </form>
    </div>

</div>
