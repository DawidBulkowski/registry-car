<?php

  use yii\helpers\Url;
  use kartik\date\DatePicker;

  $this->title = 'Dodaj tankowanie';

?>

<div class="site-index">

    <div class="col-md-6 col-sm-12 offset-md-1 addForm">

        <?php if($error != ''){ ?>
        <div class="row">
            <div class="col-md-12 alert alert-danger" role="alert">
                <?=$error;?>
            </div>
        </div>
        <?php } ?>

        <form action="<?= Url::to(['/refuel/add']) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-4">
                    <p class="talign-r">Unikalny identyfikator dokumentu</p>
                </div>
                <div class="col-md-8">
                    <input type="text" id="number" name="number" value="<?php echo isset($_POST["number"]) ? $_POST["number"] : ''; ?>" class="form-control" placeholder="Unikalny identyfikator dokumentu" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Samochód:</p>
                </div>
                <div class="col-md-9">
                    <select name="car" id="car" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($cars as $car) : ?>
                            <option data-symbol="<?=$car->symbol;?>" data-section="<?=$car->section_id;?>" value="<?=$car->id?>" <?= isset($_POST["car"]) && $_POST["car"] == $car->id ? 'selected' : '';?> ><?=$car->getFullName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Dział:</p>
                </div>
                <div class="col-md-9">
                    <select name="section" id="section" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($sections as $section) : ?>
                            <option value="<?=$section->id?>" <?= isset($_POST["section"]) && $_POST["section"] == $section->id ? 'selected' : '';?> ><?=$section->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Data tankowania</p>
                </div>
                <div class="col-md-9 talign-l">

                    <?=DatePicker::widget([
                        'name' => 'date',
                        'language' => 'pl',
                        'value' => isset($_POST["date"]) ? $_POST["date"] : '',
                        'options' => ['placeholder' => 'Wprowadź datę tankowania ...', 'required' => 'on', 'autocomplete' => 'off'],
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'todayHighlight' => true,
                        ]
                    ]);?>

                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Cena litra paliwa (brutto):</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="price_fuel" value="<?php echo isset($_POST["price_fuel"]) ? $_POST["price_fuel"] : ''; ?>" class="form-control" placeholder="Wprowadź cenę litra paliwa (brutto)" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Ilość paliwa (w litrach):</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="amount_fuel" value="<?php echo isset($_POST["amount_fuel"]) ? $_POST["amount_fuel"] : ''; ?>" class="form-control" placeholder="Wprowadź ilość paliwa (w litrach)" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Rabat (zł):</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="discount" value="<?php echo isset($_POST["discount"]) ? $_POST["discount"] : '0.00'; ?>" class="form-control" placeholder="Rabat (zł)" autocomplete="off" required>
                </div>
            </div>

            <h3 class="talign-c">Dodatkowe artykuły <button type="button" class="btn btn-sm btn-primary" onclick="addProduct();">Dodaj</button></h3>
            <div class="col-md-12" id="products">

            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Dodaj tankowanie</button>
            </div>

        </form>
    </div>

    <div class="col-md-4 col-sm-12">
        <center><p>Tabela pomocnicza</p></center>
        <div class="table-responsive listView">
            <table class="table table-sm" id="helpTable">

                <thead>
                  <tr>
                    <th scope="col">Samochód</th>
                    <th scope="col">Ilość paliwa</th>
                    <th scope="col">Cena</th>
                    <th scope="col">Inne</th>
                  </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function addProduct(){
        $("#products").append(`
            <div class="row product">
                <div class="col-md-4">
                    <input type="text" name="product[]" class="form-control" placeholder="Nazwa produktu" autocomplete="off" required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="product_price[]" class="form-control" placeholder="Cena brutto" autocomplete="off" required>
                </div>
                <div class="col-md-3">
                    <input type="number" max="100" min="0" name="vat[]" class="form-control" placeholder="VAT (%)" autocomplete="off" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeProduct(this);">Usuń</button>
                </div>
            </div>
        `);
    }

    function removeProduct(element){
        $(element).closest(".product").remove();
    }

    $("#symbol").keyup(function() {
        $('option[data-symbol]').removeProp("selected");
        $('option[data-symbol="' + $("#symbol").val() + '"]').prop("selected", "selected");
        updateSectionByCar();
    });

    $("#car").change(function(){
        updateSectionByCar();
    });

    function updateSectionByCar(){
        var selectedCar = $('#car option:selected').attr('data-section');
        $('#section option').removeProp("selected");
        $('#section option[value="' + selectedCar + '"]').prop("selected", "selected");
    }

    $("#number").keyup(function() {
        updateHelpTable($(this).val());
    });

    function updateHelpTable(value){
        $.get("/refuel/get-all?number=" + value, function(data) {
            $("#helpTable tbody").html('');
            var arrdata = JSON.parse(data);
            for(i = 0; i < arrdata.length; i++){
                $("#helpTable > tbody").append("<tr><td>" + arrdata[i].car + "</td><td>" + arrdata[i].fuel_amount + " litrów</td><td>" + arrdata[i].fuel_price + " zł</td><td>" + arrdata[i].other + " zł</td></tr>");
            }
        });
    }

</script>
