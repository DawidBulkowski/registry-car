<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;
use yii\data\Pagination;
use yii\widgets\LinkPager;

$this->title = 'Statystyka samochodów';

?>

<div class="site-index">

  <div class="col-md-12 buttons-nav">

  </div>

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Samochód</th>
          <th scope="col">Średnie spalanie w BOR</th>
          <th scope="col">Średnie spalanie w OU</th>
          <th scope="col">Ilość km przejechanych w BOR</th>
          <th scope="col">Średnia cena paliwa w BOR (brutto)</th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($cars as $car) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td><?=$car->getFullName();?></td>
          <td><?=round($car->averageBOR, 2);?> litrów</td>
          <td><?=round($car->averageOU, 2);?> litrów</td>
          <td><?=round($car->distanceBOR, 2);?></td>
          <td class="talign-c"><?=round($car->averagePriceFuelBOR, 2);?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  </div>


</div>
