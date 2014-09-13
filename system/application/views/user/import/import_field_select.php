<?php 
$this->load->view('layout/header', array('title' => "Import Configuration..."));
?>
<div id="head" class="clear"><h1>Import Configuration...</h1></div>

<form action="<?php echo site_url('user/import_action') ?>" method="post" enctype="multipart/form-data">
<label for="ignore_header">Ignore First Line(Header)</label> <input type="checkbox" name="ignore_header" id="ignore_header" value="1" /><br />

<table id="main">
<?php
$fields = array('Ignore','Name','Phone','Email');
print '<tr>';
for($i=0; $i<count($all_rows[0]); $i++) {
	print "<td><select name='field[]'>\n";
	$current_field_name = $fields[$i];
	
	$selected = 'Name';
	if(preg_match('/^[0-9\+\- ]+$/', $all_rows[2][$i])) $selected = 'Phone';
	if(preg_match('/^.+\@.+\..+$/', $all_rows[2][$i])) $selected = 'Email';
	if(preg_match('/^http\:/', $all_rows[2][$i])) $selected = 'URL';
	
	foreach($fields as $field_name) {
		$selected_code = ($field_name == $selected) ? " selected='selected'" : "";
		
		print "<option value='$field_name'$selected_code>$field_name</option>\n";
	}
	print "</select></td>\n";
}
print "</tr>\n\n";

foreach($all_rows as $row) {
	print '<tr>';
	foreach($row as $cell) {
		print "<td>$cell</td>\n";
	}
	print "</tr>\n";
}
?>
</table>

<input type="hidden" name="uploaded_file" value="<?php echo $_FILES['csv_file']['tmp_name'] ?>_saved" />
<input type="submit" name="action" value="Import Data" />
</form>

<?php $this->load->view('layout/footer');