<?php

use yii\helpers\Url;
use app\widgets\TypeUserListWidget;
use app\widgets\BooleanIconWidget;

$this->title = 'Lista ustawień';

?>

<div class="site-index">

  <div class="table-responsive listView">
  <table class="table table-sm">

      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Opis</th>
          <th scope="col">Wartość</th>
          <th scope="col"></th>
        </tr>
      </thead>

      <tbody>
        <?php $index = 0; foreach ($settings as $setting) : $index++; ?>
        <tr>
          <td scope="row"><?=$index;?></th>
          <td class="talign-l"><?=$setting->description;?> (<?=$setting->format;?>)</td>
          <td><?=$setting->value;?></td>
          <td>
              <a href="<?= Url::to(['/settings/edit', 'id' => $setting->id]) ?>">
                  <button type="button" class="btn btn-primary btn-sm">Edytuj</button>
              </a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
  </table>

  </div>


</div>
