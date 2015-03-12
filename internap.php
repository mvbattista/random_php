<?php

class rest_call {
	protected $type;
	protected $url;
	protected $args;

	# Construct takes optional token info. If token is passed, add to arguments.
	function __construct($in_type, $in_url, $in_arguments, $in_token=null) {
		if ((isset($in_token))) {
			$in_arguments['token'] = $in_token;
		}
		$this->url = $in_url;
		$this->type = $in_type;
		$this->args = $in_arguments;
	}

	# Make API call using curl
	protected function send() {
		
		# Add arguments to URL
		$url = $this->url;
		if (count($this->args)) {
			$url = $this->url . "?" . http_build_query($this->args);
		}

		# Make curl call and store info for error handling.
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($this->type === 'PUT' or $this->type === 'DELETE') {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->type);
		}
		$curl_response = curl_exec($ch);
		$curl_info = curl_getinfo($ch);
		$curl_code = $curl_info['http_code'];
		curl_close($ch);

		# Just in case it's disconnected.
		if ($curl_response === false) {
		    die("ERROR ON curl_exec: " . var_export($curl_info));
		}

		# Decode and handle error messages
		$decoded = json_decode($curl_response);
		if (isset($decoded->status) 
			&& $decoded->status != 'ok') {
		    echo 'error: ' . $curl_code . ' '. $decoded->msg . "\n";
		}

		return $decoded;

	}
}

class api_interface extends rest_call {
	protected $api_base = 'http://hiringapi.dev.voxel.net/';
	protected $api_version = 'v1/';
	private $token;

	# If username and password are passed, uses v2 API
	function __construct( $in_username=null, $in_password=null) {
		if (!(is_null($in_username) && is_null($in_password))) {
			$this->api_version = 'v2/';
			$this->token = $this->get_token($in_username, $in_password);
		}
	}

	# Lists keys from API
	public function api_list() {
		$list_url = $this->api_base . $this->api_version . 'list';
		$list_call = new rest_call('GET', $list_url, array(), $this->token);
		$response = $list_call->send();
		if ($response->status === 'ok') {
			foreach ($response->keys as $val) {
				echo "$val ";
			}
			echo "\n";
		}
	}

	# Stores or replaces key value in API
	public function api_set($in_key, $in_value) {
		$set_url = $this->api_base . $this->api_version . 'key';
		$set_args = array('key' => $in_key, 'value' => $in_value);
		$set_call = new rest_call('PUT', $set_url, $set_args, $this->token);
		$response = $set_call->send();
		if ($response->status === 'ok') {
			echo $response->status . "\n";
		}
	}

	# Gets key value from API
	public function api_get($in_key) {
		$set_url = $this->api_base . $this->api_version . 'key';
		$set_args = array('key' => $in_key);
		$set_call = new rest_call('GET', $set_url, $set_args, $this->token);
		$response = $set_call->send();
		if ($response->status === 'ok') {
			echo $response->$in_key . "\n";
		}
	}

	# Removes key from API
	public function api_delete($in_key) {
		$delete_url = $this->api_base . $this->api_version . 'key';
		$delete_args = array('key' => $in_key);
		$delete_call = new rest_call('DELETE', $delete_url, $delete_args, $this->token);
		$response = $delete_call->send();
		if ($response->status === 'ok') {
			echo $response->status . "\n";
		}
	}

	# Make auth call for v2 API
	private function get_token($in_username, $in_password) {
		$auth_url = $this->api_base . $this->api_version . 'auth';
		$auth_call = new rest_call('GET', $auth_url, 
			array('user' => $in_username, 'pass' => $in_password));
		$response = $auth_call->send();	
		if ($response->status === 'ok') {
			echo $response->status . "\n";
		}
		return $response->token;
	}

}


if (empty($argv) || count($argv) < 2) {
  die("Incorrect number of arguments\n");
}
$file = $argv[1];
if (file_exists($f)) {
	# Read the file 
	$lines = file($f);

	# The first line of a V2 file has "auth".
	# If it doesn't, treat as V1 interface.
	$api_obj = null;
	if (preg_match('/^auth /', $lines[0])) {
		$auth_line = array_shift($lines);
		$auth_line = str_replace("\n", '', $auth_line);
		$auth_line_parts = explode(' ', $auth_line);
		$api_obj = new api_interface($auth_line_parts[1], $auth_line_parts[2]);
	} 
	else {
		$api_obj = new api_interface();
	}
	# Dispatch for each of the following commands.
	foreach ($lines as $line) {
		$line = str_replace("\n", '', $line);
		$line_parts = explode(' ', $line);
		switch ($line_parts[0]) {
			case 'list':
				$api_obj->api_list();
				break;
			case 'get':
				$api_obj->api_get($line_parts[1]);
				break;
			case 'set':
				$api_obj->api_set($line_parts[1], $line_parts[2]);
				break;
			case 'delete':
				$api_obj->api_delete($line_parts[1]);
				break;
			default:
				die("Invalid command");
				break;
		}

	}
}

?>