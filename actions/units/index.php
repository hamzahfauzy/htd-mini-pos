<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$datas = $db->all('units');

return compact('datas','success_msg');