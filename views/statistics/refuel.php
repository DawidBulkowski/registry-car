<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Statystyka tankowania za rok '.$year;

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <form>
          <div class="col-md-2 marg5"><input type="number" name="year" class="form-control input-sm" placeholder="Wpisz rok (YYYY)" autocomplete="off" /></div>
          <button class="btn btn-primary btn-sm marg5">Szukaj</button>
          <a href="<?= Url::to(['/statistics/refuel']) ?>"><button type="button" class="btn btn-sm btn-danger marg5">Aktualny rok</button></a>
      </form>

  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Samochód</th>
          <th scope="col">Ilość zatankowanego paliwa (litry)</th>
          <th scope="col">Łączna cena za paliwo (netto)<br/><sm>Obliczone na podstawie VAT (<?=$vat;?>%)</sm></th>
          <th scope="col">Łączna cena za paliwo (brutto)</th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($cars as $car) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td><?=$car->getFullName();?></td>
          <td><?=round($car->refuelAmount, 2);?> litrów</td>
          <td><?=round($car->refuelPrice / (1 + ($vat / 100)), 2);?> zł</td>
          <td class="talign-c"><?=round($car->refuelPrice, 2);?> zł</td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  </div>


</div>
