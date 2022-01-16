<?php
    use app\widgets\LabelPeriod;
?>

<div class="content">

<div class="row">
    <div class="half">
        <p>Okres rozliczeniowy:<br/><?=LabelPeriod::widget();?></p>
    </div>
    <div class="half talignr">
        <p>Data wykonania wydruku:<br/><?=date('Y-m-d H:i:s');?></p>
    </div>
</div>

<p class="header">Zestawienie zakupów paliwa</h2>
<p class="header2">Dokument: <?=$document;?></p>

<table>
    <tr>
        <th colspan="2">Dział/działalność</th>
        <th colspan="2">Zakup paliwa</th>
        <th colspan="2">Zakup innych artykułów</th>
    </tr>
    <tr>
        <td colspan="2"></td>
        <td>Netto (VAT <?=$vat;?>%)</td>
        <td>Brutto</td>
        <td>Netto</td>
        <td>Brutto</td>
    </tr>
    <?php
          foreach ($list as $record) : if(sizeof($record->sections) > 0){
            $total_f_brutto = round($record->total_fuel_brutto, 2);
            $total_f_netto = $total_f_brutto / ((100 + $vat) / 100);
            $total_f_brutto = number_format($total_f_brutto, 2);
            $total_f_netto = number_format($total_f_netto, 2);
    ?>
      <tr>
          <th colspan="6" class="talign-l"><?=$record->name;?></th>
      </tr>
      <?php
          foreach ($record->sections as $section) :
              $fuel_brutto = $section->fuel_brutto == 0 ? '0.00' : round($section->fuel_brutto, 2);
              $fuel_netto = $fuel_brutto / ((100 + $vat) / 100);
              $fuel_brutto = number_format($fuel_brutto, 2);
              $fuel_netto = number_format($fuel_netto, 2);
      ?>
      <tr>
          <td class="talign-l" colspan="2"><?=$section->name;?></td>
          <td class="talign-r"><?=$fuel_netto;?> zł</td>
          <td class="talign-r"><?=$fuel_brutto;?> zł</td>
          <td class="talign-r"><?=$section->products_netto == 0 ? '0.00' : number_format(round($section->products_netto, 2), 2);?> zł</td>
          <td class="talign-r"><?=$section->products_brutto == 0 ? '0.00' : number_format(round($section->products_brutto, 2), 2) ;?> zł</td>
      </tr>
      <?php endforeach; ?>
      <tr>
          <th colspan="2" class="talign-r">Razem <?=$record->name;?>:</th>
          <td class="talign-r"><?=$total_f_netto;?> zł</td>
          <td class="talign-r"><?=$total_f_brutto;?> zł</td>
          <td class="talign-r"><?=number_format(round($record->total_product_netto, 2), 2);?> zł</td>
          <td class="talign-r"><?=number_format(round($record->total_product_brutto, 2), 2);?> zł</td>
      </tr>
    <?php } endforeach; ?>
</table>

</div>
