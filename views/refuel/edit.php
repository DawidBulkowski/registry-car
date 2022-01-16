<?php

  use yii\helpers\Url;
  use kartik\datetime\DateTimePicker;

  $this->title = 'Edytuj tankowanie';

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

        <form action="<?= Url::to(['/refuel/edit', 'id' => $edit->id]) ?>" method="post">

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Unikalny identyfikator dokumentu</p>
                </div>
                <div class="col-md-9">
                    <input type="text" name="number" value="<?=$edit->number;?>" class="form-control" placeholder="Unikalny identyfikator dokumentu" autocomplete="off" required>
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
                            <option value="<?=$section->id?>" <?php if($edit->section_id == $section->id) echo 'selected';?> ><?=$section->name?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Samochód:</p>
                </div>
                <div class="col-md-9">
                    <select name="car" class="form-control" required>
                        <option value="">Wybierz</option>
                        <?php foreach ($cars as $car) : ?>
                            <option data-symbol="<?=$car->symbol;?>" value="<?=$car->id?>" <?php if($edit->car_id == $car->id) echo 'selected';?> ><?=$car->getFullName()?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Data tankowania</p>
                </div>
                <div class="col-md-9 talign-l">
                    <?=DateTimePicker::widget([
                        'name' => 'date',
                        'value' => date('d-m-Y H:i', $edit->created),
                        'language' => 'pl',
                        'options' => ['placeholder' => 'Wprowadź datę tankowania ...', 'required' => 'on', 'autocomplete' => 'off'],
                        'convertFormat' => false,
                        'pluginOptions' => [
                            'format' => 'dd-mm-yyyy H:i',
                            'todayHighlight' => true
                        ]
                    ]);?>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Cena litra paliwa (brutto):</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="price_fuel" value="<?=$edit->fuel_price_basic;?>" class="form-control" placeholder="Wprowadź cenę litra paliwa (brutto)" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Ilość paliwa (w litrach):</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="amount_fuel" value="<?=$edit->fuel_amount;?>" class="form-control" placeholder="Wprowadź ilość paliwa (w litrach)" autocomplete="off" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <p class="talign-r">Rabat:</p>
                </div>
                <div class="col-md-9">
                    <input type="number" step="0.01" name="discount" value="<?=$edit->discount;?>" class="form-control" placeholder="Rabat (zł)" autocomplete="off" required>
                </div>
            </div>

            <h3 class="talign-c">Dodatkowe artykuły</h3>
            <div class="col-md-12" id="products">
                <?php foreach ($products as $product) : ?>
                    <div class="row product">
                        <div class="col-md-5">
                            <small>Nazwa produktu</small>
                            <input type="text" value="<?=$product->name;?>" name="product[]" class="form-control" placeholder="Nazwa produktu" autocomplete="off" disabled>
                        </div>
                        <div class="col-md-4">
                            <small>Cena brutto</small>
                            <input type="number" value="<?=$product->price;?>" step="0.01" name="product_price[]" class="form-control" placeholder="Cena netto" autocomplete="off" disabled>
                        </div>
                        <div class="col-md-3">
                            <small>VAT</small>
                            <input type="number" value="<?=$product->vat;?>" max="100" min="0" name="vat[]" class="form-control" placeholder="VAT (%)" autocomplete="off" disabled>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-md-12 talign-r">
                <button type="submit" class="btn btn-primary">Edytuj tankowanie</button>
            </div>

        </form>
    </div>

    <script>
    $("#symbol").keyup(function() {
        $('option[data-symbol]').removeProp("selected");
        $('option[data-symbol="' + $("#symbol").val() + '"]').prop("selected", "selected");
    });
    </script>

</div>
