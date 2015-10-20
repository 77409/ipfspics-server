<?php
/*
    Displays pictures with the hash and manages the cache
    Copyright (C) 2015 IpfsPics Team

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as
    published by the Free Software Foundation, either version 3 of the
    License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
if ( !isset($_GET['id']) ) {
	$id = "QmX6kHmFXsadTqLDMMnuV5dFqcGQAfNeKAArStw1BKqFW7";
} else {
	if( preg_match('/^([A-z0-9])+$/', $_GET['id']) ) {
		$id = $_GET['id'];
	} else {
		// security problem
		exit("wrong hash");
	}
}

$imageSize = `curl localhost:8090/$id?size`;  
if ($imageSize > 7866189) {
	exit("image is too big");
}

$imageContent = `curl localhost:8080/ipfs/$id`;

if ($_GET['dl'] == 1) {
header('Content-Type: image/jpeg');
header('Content-Disposition: attachment;filename="test.jpg"');
fpassthru($imageContent);
fclose($imageContent);
} else {
	session_cache_limiter('none');
	header("Content-type: image/png");
	header('Cache-control: max-age='.(60*60*24*365));
	header('Expires: '.gmdate(DATE_RFC1123,time()+60*60*24*365));
	header('Last-Modified: Thu, 01 Dec 1983 20:00:00 GMT');
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
	   header('HTTP/1.1 304 Not Modified');
	   die();
	}
}
echo $imageContent;

?>

