<html>
<head>
	<title>Michael V. Battista - TargetSpot Assignment Part 1 (Download)</title>
</head>
<body>

<?php
/*
	Ad Breaks can consist of multiple ads. 
	Different Ad Breaks can contain the same file.
	Each Ad Break request is a mapping of available ads for that selection.

*/
	class AudioFile {
		private $tod_id;
		private $media_uri;

		function __construct($in_tod_id, $in_media_uri) {
			$this->tod_id = $in_tod_id;
			$this->media_uri = $in_media_uri;			
		}

		function local_url() {
			$save_location = './' . $this->tod_id . '.mp3';
			return $save_location;
		}

		function download_url() {
			$save_location = $this->local_url();
			# Media may have updated since then, so always save latest version.
			# Otherwise, use Singleton design pattern and download only if not there.
			file_put_contents($save_location , fopen($this->media_uri, 'r'));
		}

	}

	class TODRequest {
		private $length;
		private $station;

		function __construct($in_length, $in_station, $in_ads_csv) {
			$this->length = $in_length;
			$this->station = $in_station;

			# Update the data file
			# Normally, this would be stored in a database (insert or update).
			$lines_to_write = array();
			$request_for = array();
			if (file_exists('./requests.txt')) {
				# Read the file 
				$lines = file('./requests.txt');
				$updating = FALSE;
				foreach ($lines as $line) {
					$line = str_replace("\n", '', $line);
					$line_parts = explode(':', $line);

					# Check if the request matches a previous request
					if ( (int)$line_parts[0] === $this->length && 
						$line_parts[1] === $this->station) {
						$line_parts[2] = $in_ads_csv;
						$updating = TRUE;
					}
					$str = implode(':', $line_parts);
					array_push($lines_to_write, $str);
				}

				# We have not made this request before, so we add it.
				if ($updating == FALSE) {
					$str = $this->length . ':' . $this->station . ':' . $in_ads_csv;
					array_push($lines_to_write, $str);
				}
			}

			# We need to create the log file with the first entry.
			else {
				$str = $this->length . ':' . $this->station . ':' . $in_ads_csv;
				array_push($lines_to_write, $str);
			}

			# Write the updated file
			$fp = fopen("./requests.txt", "w");
			$res = implode("\n", $lines_to_write);
			fwrite($fp, $res);
			fclose($fp);
		}

	}

	$length_in = isset($_POST['length']) ? $_POST['length'] : null;
	$station_in = isset($_POST['station']) ? $_POST['station'] : null;
	$self = $_SERVER['PHP_SELF'];

	# Could add more validation making sure length_in is numeric
	# and station_in has appropriate format, but not in requirements.
	if ($length_in != null && $station_in != null ) {

		# Make the request to the TOD server
		$tod_url = 'http://demo.targetspot.com/tod.php?station=' . $station_in .
		 '&length=' . $length_in;
		$string = file_get_contents($tod_url);
		$json_in = json_decode($string,true);

		# Pull the mp3s
		$ad_set = array();
		echo '<h4>Ad Break consists of ' . $json_in['TOD']['AdBreaks']['AdBreak'][0]['adCount'] . " ads.</h4>";
		foreach ($json_in['TOD']['AdBreaks']['AdBreak'][0]['Ad'] as $ad) {

			# Create new AudioFile object which downloads mp3
			$audio_file_obj = new AudioFile($ad['id'], $ad['MediaFile']['uri']);
			$audio_file_obj->download_url();
			array_push($ad_set, $ad['id']);
		}

		# Create new TODRequest object which updates the log/cache
		$tod_request_obj = new TODRequest((int)$length_in, $station_in, implode(',', $ad_set));
	}
?>
	<h3>TOD Request Form</h3>
	<form action= "<?php $self ?>" method="post">
		Station: <input type="text" name="station"> <br />
		Length: <input type="text" name="length"> <br />
		<input type="submit" value="Submit">
	</form>


</body>
</html>

