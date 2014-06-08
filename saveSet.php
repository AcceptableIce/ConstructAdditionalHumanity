<?
$name = $_POST['name'];
if(isset($_POST['image']) && trim($_POST['image']) != 'undefined') {
	$icon = $_POST['icon'];
} else {
	$icon = '';
}
header("Content-Type: application/octet-stream");
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=\"".$name.".json\"");

echo json_encode(array('name' => $name, 'icon' => $icon, 'cards' => json_decode($_POST['cards'])));



?>