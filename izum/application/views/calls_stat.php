<div id="container">
  <p>
    <a href="http://izum.wde/calls/add/1">Добавить звонок Хельги Браун </a><br />
    <a href="http://izum.wde/calls/add/2">Добавить звонок Барака Обама </a><br />
    <a href="http://izum.wde/calls/add/3">Добавить звонок Дениса Козлов </a><br />
  </p>
</div>
<div id="container">
  <h1>Статистика начислений бонусов</h1>
  <p>
    <table class="table">
     <thead>
       <tr>
         <th>Дата</th>
         <th>ФИО</th>
         <th>Количество звонков</th>
         <th>Начисляемый бонус</th>
       </tr>
     </thead>
     <tbody>
      <?php foreach($history_bonus as $history): ?>
      <tr>
        <td><?php echo $history->date ; ?></td>
        <td><?php echo $history->fio ; ?></td>
        <td><?php echo $history->count_call ; ?></td>
        <td><?php echo $history->bonus ; ?></td>
      </tr>
      <?php endforeach; ?>
     </tbody>
   </table>
  </p>
</div>
<div id="container">
  <h1>Общая статистика с начислениями</h1>
  <p>
    <table class="table">
     <thead>
       <tr>
         <th>Дата</th>
         <th>ФИО</th>
         <th>Количество звонков</th>
         <th>Начисляемый бонус</th>
       </tr>
     </thead>
     <tbody>
      <?php foreach($total_stat as $stat): ?>
      <tr>
        <td><?php echo $stat->date ; ?></td>
        <td><?php echo $stat->fio ; ?></td>
        <td><?php echo $stat->salary ; ?></td>
        <td><?php echo $stat->count_call ; ?></td>
        <td><?php echo $stat->bouns ; ?></td>
        <td><?php echo $stat->total_summ ; ?></td>
      </tr>
      <?php endforeach; ?>
     </tbody>
   </table>
  </p>
</div>
<div id="container">
  <h1>Статистика звонков</h1>
  <p>
    <table class="table">
     <thead>
       <tr>
         <th>Дата</th>
         <th>ФИО</th>
         <th>Количество звонков</th>
       </tr>
     </thead>
     <tbody>
      <?php foreach($stat_calls as $stat_call): ?>
      <tr>
        <td><?php echo $stat_call->date ; ?></td>
        <td><?php echo $stat_call->fio ; ?></td>
        <td><?php echo $stat_call->count_call ; ?></td>
      </tr>
      <?php endforeach; ?>
     </tbody>
   </table>
  </p>
</div>