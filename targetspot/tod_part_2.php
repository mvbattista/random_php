<html>
<head>
	<title>Michael V. Battista - TargetSpot Assignment Part 2 (Play)</title>
</head>
<body>

<?php

	class AdPlayRequest {
		private $length;
		private $station;
		private $ad_ids;

		function __construct($in_line) {
			$in_line_parts = explode(':', $in_line);
			$this->length = (integer) $in_line_parts[0];
			$this->station = $in_line_parts[1];
			$this->ad_ids = $in_line_parts[2];	
		}

		function matches_request($req_station, $req_length) {
			return ($req_length === $this->length && $req_station === $this->station);
		}

		function get_ad_urls() {
			$result = array();
			$ad_array = explode(',', $this->ad_ids);
			foreach ($ad_array as $ad_id) {
				$html = "<h5><a href=\"./$ad_id.mp3\" >$ad_id</a><h5>\n";
				array_push($result, $html);
			}
			return $result;
		}

	}

	$length_in = isset($_POST['length']) ? $_POST['length'] : null;
	$station_in = isset($_POST['station']) ? $_POST['station'] : null;
	$self = $_SERVER['PHP_SELF'];

	# Could add more validation making sure length_in is numeric
	# and station_in has appropriate format, but not in requirements.
	$requests = array();
	if ($length_in != null && $station_in != null ) {
		# Open and read our request "database"
		if (file_exists('./requests.txt')) {
			# Read the file 
			$lines = file('./requests.txt');
			foreach ($lines as $line) {
				$line = str_replace("\n", '', $line);

				$ad_play_req_obj = new AdPlayRequest($line);
				array_push($requests, $ad_play_req_obj);
			}
		}

		# Check if the request matches a previous request
		$ad_links = array();
		foreach ($requests as $ad_req) {
			if ($ad_req->matches_request($station_in, (int) $length_in)) {
				$ad_links = $ad_req->get_ad_urls();
				break;
			}
		}

		# If matches request, return mp3 <a>s
		if (sizeof($ad_links) > 0) {
			foreach ($ad_links as $link) {
				echo $link;
			}
		}

		# No matching request, output "Sorry"
		else {
			echo "<h5>Sorry</h5>";
		}

	}

?>

	<h3>Ad Play Form</h3>
	<form action= "<?php $self ?>" method="post">
		Station: <input type="text" name="station"> <br />
		Length: <input type="text" name="length"> <br />
		<input type="submit" value="Submit">
	</form>


</body>
</html>
