<?php
exec('sudo /usr/sbin/asterisk -r -x "sip show peers"',$sip);


require_once('phones.php');

$count = count($sip);
unset($sip[0]);
unset($sip[$count-1]);
unset($sip[$count-2]);
unset($sip[$count-3]);
foreach($sip as $key => $line) {


    $ms = '';
    $status = '';
    $user = '';
	$status = trim(substr($line,105,150));
    if(substr($status,0,2) =='OK'){
        preg_match('#\((.*)\)#Umsi',$status,$res);
        $ms = $res[1];
        $status = 'OK';
    }
	
	$user = trim(substr($line,0,21));
	$user = substr($user,strpos($user,'/')+1);
	
    $sip[$key] = array(
        'ext' => $user,
        'user' => (array_key_exists($user,$names) ? $names[$user] : ''),
        'status'=> $status,
        'latency' => $ms,
    );

}


header('Content-Type: application/json');
echo json_encode(array('online' => $sip));


?>
