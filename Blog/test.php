<?php
$array = array(
		"foo" => "dGVzdA==",
		"bar" => "dGVzdA==",
);
echo base64_decode($array)["foo"];
