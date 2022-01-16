<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista tankowań';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <div class="col-md-6 col-sm-6">
          <a href="<?= Url::to(['/refuel/csv']) ?>"><button class="btn btn-sm btn-primary marg5">Zapisz do CSV</button></a>
          <a href="<?= Url::to(['/refuel/add']) ?>"><button class="btn btn-sm btn-primary marg5">Dodaj nowy</button></a>
      </div>
      <div class="col-md-6 col-sm-6">
          <form action="/refuel/pdf">
              <div class="col-md-9 col-sm-6">
                  <input type="text" name="document" class="form-control form-control-sm marg5 input-sm" placeholder="Wpisz nazwę dokumentu" autocomplete="off" required />
              </div>
              <div class="col-md-3 col-sm-6 talign-r">
                  <button class="btn btn-sm btn-primary btn-block marg5">Generuj zestawienie</button>
              </div>
          </form>
      </div>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Data tankowania</th>
          <th scope="col">Dokument</th>
          <th scope="col">Samochód</th>
          <th scope="col">Dział</th>
          <th scope="col">Cena litra paliwa (brutto)</th>
          <th scope="col">Cena litra paliwa po rabacie (brutto)</th>
          <th scope="col">Ilość paliwa</th>
          <th scope="col">Rabat</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($refuels as $refuel) : $index++; ?>
        <tr>
          <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
          <td><?=date('d/m/Y', $refuel->created);?></td>
          <td><?=$refuel->number;?></td>
          <td><?=$refuel->getCar();?></td>
          <td><?=$refuel->getSection();?></td>
          <td><?=$refuel->fuel_price_basic;?></td>
          <td><?=round($refuel->fuel_price, 2);?></td>
          <td><?=$refuel->fuel_amount;?></td>
          <td><?=$refuel->discount;?></td>
          <td>
              <a href="<?= Url::to(['/refuel/edit', 'id' => $refuel->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/refuel/remove', 'id' => $refuel->id]) ?>">
                  <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Usuń</button>
              </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  <center>
  <?=LinkPager::widget([
    'pagination' => $pagination,
  ]);?>
  </center>

  </div>


</div>
