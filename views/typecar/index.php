<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista typów samochodów';

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <a href="<?= Url::to(['/car']) ?>"><button class="btn btn-primary btn-sm">Lista samochodów</button></a>
      <a href="<?= Url::to(['/typecar/add']) ?>"><button class="btn btn-primary btn-sm">Dodaj nowy</button></a>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nazwa</th>
          <th scope="col">Aktywne</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($typesCar as $type) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td><?=$type->name;?></td>
          <td><?= BooleanIconWidget::widget(['boolean' => $type->active ]); ?></td>
          <td>
              <a href="<?= Url::to(['/typecar/edit', 'id' => $type->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/typecar/remove', 'id' => $type->id]) ?>">
                  <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Usuń</button>
              </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>


  </div>


</div>
