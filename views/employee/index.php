<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista pracowników';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <a href="<?= Url::to(['/employee/csv']) ?>"><button class="btn btn-sm btn-primary">Zapisz do CSV</button></a>
      <a href="<?= Url::to(['/employee/add']) ?>"><button class="btn btn-sm btn-primary">Dodaj nowy</button></a>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Imię</th>
          <th scope="col">Nazwisko</th>
          <th scope="col">Login</th>
          <th scope="col">ID Kontrahenta</th>
          <th scope="col">Typ</th>
          <th scope="col">Czy pracuje?</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($employees as $employee) : $index++; ?>
        <tr>
          <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
          <td><?=$employee->name;?></td>
          <td><?=$employee->surname;?></td>
          <td><?=$employee->login;?></td>
          <td><?=$employee->kontrahent;?></td>
          <td><?= TypeUserListWidget::widget(['access' => $employee->access]); ?></td>
          <td><?= BooleanIconWidget::widget(['boolean' => $employee->works]); ?></td>
          <td>
              <a href="<?= Url::to(['/employee/edit', 'id' => $employee->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/employee/remove', 'id' => $employee->id]) ?>">
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
