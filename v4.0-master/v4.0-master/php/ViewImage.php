<?php
/**
 * @file        ViewImage.php
 * @brief       View image from database
 * @copyright   Copyright (c) 2018 GOautodial Inc.
 * @author		Christopher P. Lomuntad 
 *
 * @par <b>License</b>:
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

	require_once('CRMDefaults.php');
	require_once('DbHandler.php');

	$db 										= new \creamy\DbHandler();
	$uid 										= $_REQUEST['user_id'];
	$image 										= $db->getUserAvatar($uid);

	if (is_array($image)) {
		ob_clean();
		
		header('Content-type: '.$image['type']);
		echo base64_decode($image['data']);
	} else {
		$img = '../img/avatars/default/defaultAvatar.png';
		header('Content-Type: image/png');
		readfile($img);
	}
?>