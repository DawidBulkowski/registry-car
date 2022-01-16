<?php

  use yii\helpers\Url;
  $this->title = 'Edytuj samochód';

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

        <form action="<?= Url::to(['/car/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Numer rejestracyjny:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="register" value="<?=$edit->register;?>" class="form-control" placeholder="Wprowadź numer rejestracyjny" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Symbol:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="symbol" value="<?=$edit->symbol;?>" class="form-control" placeholder="Wprowadź symbol" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracownik:</p>
                </div>
                <div class="col-md-9">
                    <select name="employee" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($employees as $employee) : ?>
                            <option value="<?=$employee->id?>" <?php if($edit->employee_id == $employee->id) echo 'selected'; ?>><?=$employee->surname." ".$employee->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Dział:</p>
                </div>
                <div class="col-md-9">
                    <select name="section" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($sections as $section) : ?>
                            <option value="<?=$section->id?>" <?php if($edit->section_id == $section->id) echo 'selected'; ?>><?=$section->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="description" value="<?=$edit->description;?>" class="form-control" placeholder="Wprowadź opis" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Rodzaj paliwa</p>
                </div>
                <div class="col-md-9">
                    <select name="fuel" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="Benzyna" <?php if($edit->fuel == 'Benzyna') echo 'selected'; ?>>Benzyna</option>
                        <option value="Diesel" <?php if($edit->fuel == 'Diesel') echo 'selected'; ?>>Diesel</option>
                        <option value="Elektryczny" <?php if($edit->fuel == 'Elektryczny') echo 'selected'; ?>>Elektryczny</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Typ</p>
                </div>
                <div class="col-md-9">
                    <select name="type" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($typesCar as $type) : ?>
                            <option value="<?=$type->id?>" <?= $edit->type_id == $type->id ? 'selected' : '';?> ><?=$type->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Norma emisji spalin</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="norm" value="<?=$edit->norm;?>" class="form-control" placeholder="Wprowadź normę emisji spalin" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Czy użytkowany?</p>
                </div>
                <div class="col-md-9">
                    <select name="used" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?php if($edit->used == 0) echo 'selected'; ?>>Nie użytkowany</option>
                        <option value="1" <?php if($edit->used == 1) echo 'selected'; ?>>Użytkowany</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj samochód</button>
            </div>

        </form>
    </div>

</div>
