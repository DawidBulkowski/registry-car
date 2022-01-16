<?php

  use yii\helpers\Url;
  $this->title = 'Dodaj samochód';

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

        <form action="<?= Url::to(['/car/add']) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Numer rejestracyjny:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="register" value="<?php echo isset($_POST["register"]) ? $_POST["register"] : ''; ?>" class="form-control" placeholder="Wprowadź numer rejestracyjny" autocomplete="off" maxlength="50" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Symbol:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="symbol" value="<?php echo isset($_POST["symbol"]) ? $_POST["symbol"] : ''; ?>" class="form-control" placeholder="Wprowadź symbol" maxlength="100" autocomplete="off">
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
                            <option value="<?=$employee->id?>" <?= isset($_POST["employee"]) && $_POST["employee"] == $employee->id ? 'selected' : '';?> ><?=$employee->surname." ".$employee->name?></option>
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
                            <option value="<?=$section->id?>" <?= isset($_POST["section"]) && $_POST["section"] == $section->id ? 'selected' : '';?> ><?=$section->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="description" value="<?php echo isset($_POST["description"]) ? $_POST["description"] : ''; ?>" class="form-control" placeholder="Wprowadź opis" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Rodzaj paliwa</p>
                </div>
                <div class="col-md-9">
                    <select name="fuel" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="Benzyna" <?= isset($_POST["fuel"]) && $_POST["fuel"] == 'Benzyna' ? 'selected' : '';?> >Benzyna</option>
                        <option value="Diesel" <?= isset($_POST["fuel"]) && $_POST["fuel"] == 'Diesel' ? 'selected' : '';?> >Diesel</option>
                        <option value="Elektryczny" <?= isset($_POST["fuel"]) && $_POST["fuel"] == 'Elektryczny' ? 'selected' : '';?> >Elektryczny</option>
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
                            <option value="<?=$type->id?>" <?= isset($_POST["type"]) && $_POST["type"] == $type->id ? 'selected' : '';?> ><?=$type->name;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Norma emisji spalin</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="norm" maxlength="20" value="<?php echo isset($_POST["norm"]) ? $_POST["norm"] : ''; ?>" class="form-control" placeholder="Wprowadź normę emisji spalin" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Czy użytkowany?</p>
                </div>
                <div class="col-md-9">
                    <select name="used" class="form-control" required>
                        <option value="">Wybierz</option>
                        <option value="0" <?= isset($_POST["used"]) && $_POST["used"] == '0' ? 'selected' : '';?> >Nie użytkowany</option>
                        <option value="1" <?= isset($_POST["used"]) && $_POST["used"] == '1' ? 'selected' : '';?> >Użytkowany</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj samochód</button>
            </div>

        </form>
    </div>

</div>
