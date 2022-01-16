<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Lista wyjazdów';
$page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>

<div class="site-index">

  <div class="col-md-12 col-sm-12 buttons-nav">
      <div class="col-md-4 col-sm-12">
          <div class="col-md-12 col-sm-12 marg5">
              <a href="<?= Url::to(['/trace/csv']) ?>"><button class="btn btn-sm btn-primary">Zapisz do CSV</button></a>
              <a href="<?= Url::to(['/trace/add']) ?>"><button class="btn btn-sm btn-primary">Dodaj nowy</button></a>
          </div>
      </div>

      <div class="col-md-8 col-sm-12">
          <form>
              <div class="col-md-4 col-sm-12">
                  <select name="car" class="form-control-sm form-control marg5 input-sm">
                      <option value="">Wybierz samochód</option>
                      <?php foreach ($cars as $car) : ?>
                          <option value="<?=$car->id?>" <?php if(isset($_GET['car']) && $_GET['car'] == $car->id) echo 'selected';?> ><?=$car->register;?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="col-md-4 col-sm-12">
                  <select name="employee" class="form-control-sm form-control marg5 input-sm">
                      <option value="">Wybierz pracownika</option>
                      <?php foreach ($employees as $employee) : ?>
                          <option value="<?=$employee->id?>" <?php if(isset($_GET['employee']) && $_GET['employee'] == $employee->id) echo 'selected';?> ><?=$employee->getFullName();?></option>
                      <?php endforeach; ?>
                  </select>
              </div>
              <div class="col-md-4 col-sm-12">
                  <button class="btn btn-sm btn-primary marg5">Szukaj</button>
                  <a href="<?= Url::to(['/trace']) ?>"><button type="button" class="btn btn-sm btn-danger marg5">Reset</button></a>
              </div>
          </form>
      </div>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Data wyjazdu</th>
          <th scope="col">Data powrotu</th>
          <th scope="col">Opis</th>
          <th scope="col">Pracownik</th>
          <th scope="col">Samochód</th>
          <th scope="col">Służbowe (km)</th>
          <th scope="col">Prywatne (km)</th>
          <th scope="col">Licznik (km)</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($traces as $trace) : $index++; ?>
        <tr>
          <td scope="row"><?=($page-1) * $pageSize + $index;?></th>
          <td><?=date('d/m/Y H:i', $trace->start_date);?></td>
          <td>
              <?=$trace->end_date ? date('d/m/Y H:i', $trace->end_date) : 'Nie określono';?>
          </td>
          <td><?=$trace->description;?></td>
          <td><?=$trace->getEmployee();?></td>
          <td><?=$trace->getCar();?></td>
          <td><?=$trace->km_corp;?></td>
          <td><?=$trace->km_priv;?></td>
          <td><?=$trace->km_final;?></td>
          <td>
              <a href="<?= Url::to(['/trace/edit', 'id' => $trace->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
              <a href="<?= Url::to(['/trace/remove', 'id' => $trace->id]) ?>">
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
