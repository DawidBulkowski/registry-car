<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista logów';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$search = isset($_GET["szukaj"]) ? $_GET["szukaj"] : '';

?>

<div class="site-index">

  <div class="col-md-12 col-sm-12 buttons-nav">
      <form>
          <div class="col-md-3 col-sm-12 marg5">
              <input type="text" name="szukaj" value="<?=$search;?>" class="form-control input-sm" placeholder="Wprowadź frazę do wyszukania" autocomplete="off"/>
          </div>
          <button class="btn btn-sm btn-primary marg5">Szukaj</button>
          <a href="/log"><button type="button" class="btn btn-sm btn-danger marg5">Reset</button></a>
      </form>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col" class="talign-l">Data utworzenia</th>
          <th scope="col">Wartość</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($logs as $log) : $index++; ?>
            <tr>
              <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
              <td class="talign-l"><?=$log->created;?></td>
              <td class="talign-l"><?=$log->value;?></td>
              <td class="talign-r">
                  <?php if($log->details != '') { ?>
                  <a href="<?= Url::to(['/log/details', 'id' => $log->id]) ?>">
                      <button type="button" class="btn btn-primary btn-sm">Szczegóły</button>
                  </a>
                  <?php } ?>
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
