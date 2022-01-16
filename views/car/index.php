<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista samochodów';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <a href="<?= Url::to(['/car/csv']) ?>"><button class="btn btn-sm btn-primary marg5">Zapisz do CSV</button></a>
      <a href="<?= Url::to(['/typecar']) ?>"><button class="btn btn-sm btn-primary marg5">Lista typów</button></a>
      <a href="<?= Url::to(['/car/add']) ?>"><button class="btn btn-sm btn-primary">Dodaj nowy</button></a>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Numer rejestracyjny</th>
          <th scope="col">Symbol</th>
          <th scope="col">Pracownik</th>
          <th scope="col">Dział</th>
          <th scope="col">Rodzaj paliwa</th>
          <th scope="col">Typ</th>
          <th scope="col">Norma emisji spalin</th>
          <th scope="col">Czy użytkowany?</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($cars as $car) : $index++; ?>
        <tr>
          <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
          <td><?=$car->register;?></td>
          <td><?=$car->symbol;?></td>
          <td><?=$car->getEmployee();?></td>
          <td><?=$car->getSection();?></td>
          <td><?=$car->fuel;?></td>
          <td><?=$car->type;?></td>
          <td><?=$car->norm;?></td>
          <td><?= BooleanIconWidget::widget(['boolean' => $car->used ]); ?></td>
          <td>
              <a href="<?= Url::to(['/trace/index', 'car' => $car->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Wyjazdy</button>
              </a>
              <a href="<?= Url::to(['/car/edit', 'id' => $car->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/car/remove', 'id' => $car->id]) ?>">
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
