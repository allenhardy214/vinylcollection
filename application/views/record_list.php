<?if(count($data['records'])>0):?>
  <table>
    <tr>
      <?foreach($data['field_titles'] as $t):?>
        <th><?=$t?></th>
      <?endforeach;?>
    </tr>
    <?foreach($data['records'] as $record):?>
      <tr>
        <td><?=$record->id;?></td>
        <td><?=$record->title;?></td>
        <td><?=$record->artist;?></td>
        <td><?=$record->format;?></td>
        <td><?=$record->sides;?></td>
        <td><?=$record->type;?></td>
      </tr>
    <?endforeach;?>
  </table>
<?endif;?>
