<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;

$this->title = 'Lista okresów rozliczeniowych';

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">

      <?php if(isset($_GET['error'])){ ?>
      <div class="row">
          <div class="col-md-12 alert alert-danger" role="alert">Nie możesz zamknąć bieżącego okresu rozliczeniowego - będzie to możliwe dopiero <?=$_GET['date'];?></div>
      </div>
      <?php } ?>

      <form method="post" action="<?= Url::to(['/period/close']) ?>">

      <div class="row">
          <div class="col-md-3 col-xs-12 marg5">
              <?=DatePicker::widget([
                  'name' => 'date',
                  'language' => 'pl',
                  'value' => isset($_GET["to"]) ? $_GET["to"] : '',
                  'options' => ['placeholder' => 'Wprowadź datę zamknięcia ...', 'class' => 'input-sm', 'autocomplete' => 'off'],
                  'convertFormat' => false,
                  'pluginOptions' => [
                      'format' => 'yyyy-mm-dd',
                      'todayHighlight' => true,
                  ]
              ]);?>
          </div>

          <div class="col-md-3 col-xs-12 marg5">
              <center><button class="btn btn-sm btn-primary">Zamknij bieżący okres rozliczeniowy</button></center>
          </div>
      </div>

      <?php if(isset($_GET['accept'])){ ?>
      <div class="row">
          <div class="col-md-12 alert alert-warning" role="alert">
              <center><p>Czy jesteś pewien, że chcesz zamknąć okres rozliczeniowy od <?=$_GET["from"];?> do <?=$_GET["to"];?>?</p>
              <input type="hidden" name="close" value="1" ?>
              <button class="btn btn-sm btn-primary">TAK, zamknij okres</button>
              <a href="<?= Url::to(['/period']) ?>"><button type="button" class="btn btn-sm btn-danger">Anuluj</button></center></a>
          </div>
      </div>
      <?php } ?>

      </form>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Data rozpoczęcia</th>
          <th scope="col">Data zakończenia</th>
          <th scope="col">Zakończony</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($periods as $period) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td><?=$period->created_date;?></td>
          <td><?=$period->end_date;?></td>
          <td><?= BooleanIconWidget::widget(['boolean' => $period->completed]); ?></td>
          <td><?=$current == $period->id ? '<span class="badge badge-primary">wybrany</span>' : '';?></td>
          <td>
              <a href="<?= Url::to(['/period/change', 'id' => $period->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Przełącz</button>
              </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  </div>


</div>
