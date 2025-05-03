<?php
$plainPassword = 'buksuadmin';
$hash = password_hash($plainPassword, PASSWORD_DEFAULT);
echo $hash;
?>
