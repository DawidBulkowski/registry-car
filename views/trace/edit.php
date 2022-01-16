<?php

  use yii\helpers\Url;
  use kartik\datetime\DateTimePicker;

  $this->title = 'Edytuj wyjazd';

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

        <form action="<?= Url::to(['/trace/edit', 'id' => $edit->id]) ?>" method="post">

            <?php if(!$isDriver){ ?>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Pracownik:</p>
                </div>
                <div class="col-md-9">
                    <select name="employee" id="selectEmployees" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($employees as $employee) : ?>
                            <option value="<?=$employee->id?>" <?php if($edit->employee_id == $employee->id) echo 'selected';?> ><?=$employee->surname." ".$employee->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <script>
                $("#selectEmployees").change(function(){
                    var emp_id = $("#selectEmployees option:selected").val();
                    if(emp_id != '')
                      $.ajax({url: "/car/get-all-by-employee?employee_id=" + emp_id, success: function(result){
                          $('#selectCars').empty().append('<option value="">Wybierz</option>')
                          var arrCars = JSON.parse(result);
                          for(var i = 0; i < arrCars.length; i++){
                              $('#selectCars').append('<option value="' + arrCars[i]['id'] + '">' + arrCars[i]['register'] + '</option>')
                          }
                      }});
                    else $('#selectCars').empty().append('<option value="">Wybierz</option>')
                });
            </script>

            <?php } ?>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Samochód:</p>
                </div>
                <div class="col-md-9">
                    <select name="car" id="selectCars" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($cars as $car) : ?>
                            <option value="<?=$car->id?>" <?php if($edit->car_id == $car->id) echo 'selected';?> ><?=$car->register." ".$car->type?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Opis trasy / cel wyjazdu:</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="description" value="<?=$edit->description;?>" class="form-control" placeholder="Wprowadź opis trasy / cel wyjazdu" autocomplete="off">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Data wyjazdu</p>
                </div>
                <div class="col-md-9 talign-l">
                    <?=DateTimePicker::widget([
                        'name' => 'start_date',
                        'value' => date('d-m-Y H:i', $edit->start_date),
                        'language' => 'pl',
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
                        'value' => date('d-m-Y H:i', $edit->end_date),
                        'language' => 'pl',
                        'options' => ['placeholder' => 'Wprowadź datę przyjazdu ...', 'autocomplete' => 'off'],
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
                    <input type="number" step="0.01" name="km_corp" value="<?=$edit->km_corp;?>" class="form-control" placeholder="Wprowadź ilość kilometrów służbowych" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Ilość kilometrów prywatne:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="km_priv" value="<?=$edit->km_priv;?>" class="form-control" placeholder="Wprowadź ilość kilometrów prywatnych" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Stan licznika po powrocie:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="liczn" value="<?=$edit->km_final;?>" class="form-control" placeholder="Wprowadź stan licznika" autocomplete="off" required>
                </div>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj wyjazd</button>
            </div>

        </form>
    </div>

</div>
