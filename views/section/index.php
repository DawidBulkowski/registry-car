<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista działów';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <a href="<?= Url::to(['/section/csv']) ?>"><button class="btn btn-sm btn-primary">Zapisz do CSV</button></a>
      <a href="<?= Url::to(['/section/add']) ?>"><button class="btn btn-sm btn-primary">Dodaj nowy</button></a>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nazwa</th>
          <th scope="col">Opis</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($sections as $section) : $index++; ?>
        <tr>
          <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
          <td><?=$section->name;?></td>
          <td><?=$section->description;?></td>
          <td>
              <a href="<?= Url::to(['/section/edit', 'id' => $section->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/section/remove', 'id' => $section->id]) ?>">
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
