<?php

  use yii\helpers\Url;
  use kartik\datetime\DateTimePicker;

  use app\controllers\LogController;

  $this->title = 'Szczegóły log '.$log->created;

?>

<div class="site-index">

    <div class="col-md-6 offset-md-3 addForm">
          <h4><?=$log->value;?></h4>
          <div class="row">
              <?php if($log->details_prev) { ?>
              <div class="col-md-6">
                  <p>Przed zmianą</p>
                  <p><?=nl2br(LogController::convertJsonToString($log->details_prev));?></p>
              </div>
              <?php } ?>
              <div class="col-md-6">
                  <p>Po zmianie</p>
                  <p><?=nl2br(LogController::convertJsonToString($log->details));?></p>
              </div>
          </div>
    </div>

</div>
