<?php

unset($_COOKIE['ckNegocio']);
unset($_COOKIE['ckLocal']);

setcookie('ckNegocio', "", time() - 3600, '/');
setcookie('ckLocal', "", time() - 3600, '/');

header("location: index.html");
?>