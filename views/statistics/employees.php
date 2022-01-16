<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Statystyka kierowców';

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">
      <a href="<?= Url::to(['/statistics/pif']) ?>"><button class="btn btn-sm btn-primary">Zapisz PIF Przejazdy</button></a>
  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col" class="talign-l">Pracownik</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($employees as $employee) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td class="talign-l"><?=$employee->getFullName();?></td>
          <td class="talign-l">
               <?php if(sizeof($employee->listOfCars) == 0){ ?> Brak <?php } else { ?>
               <table class="table table-sm table-internal">
                   <thead>
                     <tr>
                       <th scope="col">Samochód</th>
                       <th scope="col">Koszt za 1km</th>
                       <th scope="col">Ilość km przejechanych</th>
                       <th scope="col">Łącznie</th>
                     </tr>
                   </thead>
                    <tbody>
                          <?php foreach ($employee->listOfCars as $car) : ?>
                              <tr>
                                  <td><?=$car->carName;?></td>
                                  <td><?=$car->costBruttoForKilometer;?> zł</td>
                                  <td><?=$car->distancePrivate;?> km</td>
                                  <td><?=$car->finalCost;?> zł</td>
                              </tr>
                          <?php endforeach; ?>
                    </tbody>
               </table>
               <?php } ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  </div>


</div>
