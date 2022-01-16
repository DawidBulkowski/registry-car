<?php

  use yii\helpers\Url;
  use kartik\datetime\DateTimePicker;

  $this->title = 'Dodaj wyjazd';

?>

<div class="site-index">

    <div class="col-md-8 col-lg-6 offset-md-2 offset-lg-3 addForm">

        <?php if($error != ''){ ?>
        <div class="row">
            <div class="col-md-12 alert alert-danger" role="alert">
                <?=$error;?>
            </div>
        </div>
        <?php } ?>

        <form action="<?= Url::to(['/trace/add']) ?>" method="post">

            <?php if(!$isDriver){ ?>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracownik:</p>
                </div>
                <div class="col-md-9">
                    <select name="employee" id="selectEmployees" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($employees as $employee) : ?>
                            <option value="<?=$employee->id?>" <?= isset($_POST["employee"]) && $_POST["employee"] == $employee->id ? 'selected' : '';?> ><?=$employee->getFullName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php } ?>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Samochód:</p>
                </div>
                <div class="col-md-9">
                    <select name="car" id="selectCars" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($cars as $car) : ?>
                            <option value="<?=$car->id?>" <?= isset($_POST["car"]) && $_POST["car"] == $car->id ? 'selected' : '';?> ><?=$car->register." ".$car->getType()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis trasy / cel wyjazdu:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="description" value="<?php echo isset($_POST["description"]) ? $_POST["description"] : ''; ?>" class="form-control" placeholder="Wprowadź opis trasy / cel wyjazdu" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Data wyjazdu</p>
                </div>
                <div class="col-md-9 talign-l">
                    <?=DateTimePicker::widget([
                        'name' => 'start_date',
                        'value' => isset($_POST["start_date"]) ? $_POST["start_date"] : '',
                        'language' => 'pl-PL',
                        'options' => ['placeholder' => 'Wprowadź datę wyjazdu ...', 'required' => 'on', 'autocomplete' => 'off'],
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy HH:ii',
                            'todayHighlight' => true,
                            'startDate' => '01-01-2018 00:00'
                        ]
                    ]);?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Data przyjazdu</p>
                </div>
                <div class="col-md-9 talign-l">
                    <?=DateTimePicker::widget([
                        'name' => 'end_date',
                        'value' => isset($_POST["end_date"]) ? $_POST["end_date"] : '',
                        'language' => 'pl',
                        'options' => ['placeholder' => 'Wprowadź datę wyjazdu ...', 'autocomplete' => 'off'],
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy HH:ii',
                            'todayHighlight' => true,
                            'startDate' => '01-01-2018 00:00'
                        ]
                    ]);?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Ilość kilometrów służbowe:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="km_corp" value="<?php echo isset($_POST["km_corp"]) ? $_POST["km_corp"] : ''; ?>" class="form-control" placeholder="Wprowadź ilość kilometrów służbowych" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Ilość kilometrów prywatne:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="km_priv" value="<?php echo isset($_POST["km_priv"]) ? $_POST["km_priv"] : ''; ?>" class="form-control" placeholder="Wprowadź ilość kilometrów prywatnych" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Stan licznika po powrocie:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="liczn" value="<?php echo isset($_POST["liczn"]) ? $_POST["liczn"] : ''; ?>" class="form-control" placeholder="Wprowadź stan licznika" autocomplete="off" required>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj wyjazd</button>
            </div>

        </form>
    </div>

</div>
