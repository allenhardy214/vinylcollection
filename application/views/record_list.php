<?if(count($data['records'])>0):?>
  <table>
    <tr>
      <?foreach($data['field_titles'] as $t):?>
        <th><?=$t?></th>
      <?endforeach;?>
      
      <?if($data['records'][0]->option_count>0):?>
		<th colspan="<?=$data['records'][0]->option_count?>">Options</th>
      <?endif;?>
    </tr>
    <?foreach($data['records'] as $record):?>
      <tr>
        <td><?=$record->id;?></td>
        <td><?=$record->title;?></td>
        <td><?=$record->artist;?></td>
        <td><?=$record->label?></td>
        <td><?=$record->size;?></td>
        <td><?=$record->type;?></td>
        <td><?=$record->sides;?></td>
        <td><?=$record->release;?></td>
        <td><?=$record->condition;?></td>
        
        <?if($record->option_count>0):?>
			<?foreach($record->options as $option):?>
				<td class="option">
					<button type="button" class="<?=$option['action']?>" data-url="<?=$option['url']?>" data-id="<?=$record->id?>"><span><?=ucfirst($option['action'])?></span></button>
				</td>
			<?endforeach;?>
        <?endif;?>
      </tr>
    <?endforeach;?>
  </table>
<?endif;?>
