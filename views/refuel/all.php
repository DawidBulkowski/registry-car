<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista wszystkich tankowań';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 col-sm-12 buttons-nav">
      <form>
          <div class="col-md-2 col-sm-12 marg5">
              <select name="car" class="form-control-sm form-control input-sm">
                  <option value="">Wybierz samochód</option>
                  <?php foreach ($cars as $car) : ?>
                      <option value="<?=$car->id?>" <?php if(isset($_GET['car']) && $_GET['car'] == $car->id) echo 'selected';?> ><?=$car->register;?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="col-md-2 col-sm-12 marg5">
              <button class="btn btn-sm btn-primary">Szukaj</button>
              <a href="<?= Url::to(['/refuel/all']) ?>"><button type="button" class="btn btn-sm btn-danger">Reset</button></a>
          </div>
      </form>
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
          <th scope="col">Ilość paliwa</th>
          <th scope="col">Okres rozliczeniowy</th>
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
          <td><?=$refuel->fuel_price;?></td>
          <td><?=$refuel->fuel_amount;?></td>
          <td><?=$refuel->period_id;?></td>
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
