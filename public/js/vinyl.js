$(document).ready(function(){
	
	//buttons
	$('.add-record,.edit').on('click',showEditRecordDialogue);
	
	//Prepare dialogues
	$( '#edit_record_dialogue' ).dialog( { 'autoOpen': false } );
});

function showEditRecordDialogue(){
	$( '#edit_record_dialogue' ).dialog('open');
}
