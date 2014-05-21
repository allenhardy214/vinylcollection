<div id="controls">
	<form id="control_form">
		<fieldset>
			<legend>Options</legend>
			<ul>
				<li><button type="button" class="add-record"><span>Add Record</span></button></li>
				<li><button type="button" class="login"><span>Login</span></button></li>
			</ul>
		</fieldset>
	</form>
</div>

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
<div class="dialogues">

	<div id="edit_record_dialogue" class="dialogue">
    <div class="title">Edit Record</div>
    <form id="edit_record">
      <input type="hidden" name="record_id" value=""/>
      <fieldset>
        <ul>
          <li>
            <label for="record_title">Title</label>
            <input type="text" name="record_title" value="" />
          </li>
          <li>
            <label for="record_label">Label</label>
            <select name="record_label">
              <?=$data['selects']['label_id']?>
            </select>
          </li>
          <li>
            <label for="record_format">Format</label>
            <select name="record_format">
              <?=$data['selects']['format_id']?>
            </select>
          </li>
          <li>
            <label for="record_rpm">RPM</label>
            <select name="record_rpm">
              <?=$data['selects']['rpm_id']?>
            </select>
          </li>
          
          <li>
            <label for="record_condition">Condition</label>
            <select name="record_condition">
              <?=$data['selects']['condition_id']?>
            </select>
          </li>
          <li>
            <label for="record_released">Released</label>
            <input type="text" name="record_released" class="integer minlength4" value=""/>
          </li>
          <li>
            <label for="record_sides">Sides</label>
            <input type="text" name="record_sides" class="integer" value="" />
          </li>
          <li>
            <label for="record_size">Size</label>
            <select name="record_size">
              <?=$data['selects']['size_id']?>
            </select>
          </li>
          <li>
            <label for="record_location">Location</label>
            <select name="record_location">
              <?=$data['selects']['location_id']?>
            </select>
          </li>
        </ul>
      </fieldset>
      <div class="buttons">
        <button type="button" class="save"><span>Save</span></button>
        <button type="button" class="cancel"><span>Cancel</span></button>
      </div>
    </form>
  </div>
</div>
