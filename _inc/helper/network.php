<?php define('STOCK_CHECK',false);

function checkInternetConnection($domain = 'www.google.com')  
{
	if($socket =@ fsockopen($domain, 80, $errno, $errstr, 30)) {
		fclose($socket);
		return true;
	}
	return false;
}

function url_exists($url) {
    $ch = @curl_init($url);
    @curl_setopt($ch, CURLOPT_HEADER, TRUE);
    @curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    @curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($ch) , $status);
    curl_close($ch);
    return (isset($status[1]) && ($status[1] == 200 || $status[1] == 422));
}

function checkValidationServerConnection($url = 'http://www.aznetsolutions.com.my')  
{
    if(url_exists($url)) {
        return true;
    }
    return false;
}


function checkOnline($domain) 
{
	return checkInternetConnection($domain);
}

function checkDBConnection() 
{
	global $sql_details;
	$host = $sql_details['host'];
	$db = $sql_details['db'];
	$user = $sql_details['user'];
	$pass = $sql_details['pass'];
	$port = $sql_details['port'];
	try {
		$conn = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8",$user,$pass);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
	}
	catch(PDOException $e) {
		return false;
	}
}

function isLocalhost() 
{
    $whitelist = array('localhost','127.0.0.1','::1');
    return in_array( $_SERVER['REMOTE_ADDR'], $whitelist);
}




function get_real_ip() {
    if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
            $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($addr[0]);
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }
}

function getMAC()
{
	ob_start();
	system('ipconfig /all');
	$mycom=ob_get_contents();
	ob_clean();
	$mac = array();
	foreach(preg_split("/(\r?\n)/", $mycom) as $line) {
		if(strstr($line, 'Physical Address')) {
			$mac[] = substr($line,39,18);
		}
	}
	return $mac;
}



function denied_ips()
{
	return DENIED_IPS;
}

function allowed_only_ips()
{
	return ALLOWED_ONLY_IPS;
}

function replace_lines($file, $new_lines, $source_file = null) 
{
    $response = 0;
    $tab = chr(9);
    $lbreak = chr(13) . chr(10);
    if ($source_file) {
        $lines = file($source_file);
    }
    else {
        $lines = file($file);
    }
    foreach ($new_lines as $key => $value) {
        // $lines[--$key] = $tab . $value . $lbreak;
        $lines[--$key] = $value . $lbreak;
    }
    $new_content = implode('', $lines);
    if ($h = fopen($file, 'w')) {
        if (fwrite($h, trim($new_content))) {
            $response = 1;
        }
        fclose($h);
    }
    return $response;
}

function hash_generate($string = null)
{
	
	return base64_encode(hash_hmac('sha1', $string, root_url(), 1));
}

function hash_compare($a, $b) { 
	if (!is_string($a) || !is_string($b)) { 
	    return false; 
	} 

	$len = strlen($a); 
	if ($len !== strlen($b)) { 
	    return false; 
	} 

	$status = 0; 
	for ($i = 0; $i < $len; $i++) { 
	    $status |= ord($a[$i]) ^ ord($b[$i]); 
	} 
	return $status === 0; 
}
