<?php
/********************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of coreBOS CRM.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 ********************************************************************************
 *  Module       : coreBOS IFrame URL.
 *  Version      : 1.0
 *  Author       : JPL TSolucio, S. L.   Joe Bordes
 *  Example      : http://your_server/your_corebos/index.php?action=index&module=cbifurl&load=http%3A%2F%2Fserver%2Ffile.php%3Funame=$users-user_name$%26uid=$users-id$
 ********************************************************************************/
use Firebase\JWT\JWT;

global $currentModule,$current_user;

$embedtype = '';
if (!empty($_REQUEST['params'])) {
	$params = json_decode($_REQUEST['params'], true);
	$embedtype = empty($params['embedtype']) ? '' : vtlib_purify($params['embedtype']);
}
$Metabase_Embed_Secret = GlobalVariable::getVariable('Metabase_Embed_Secret', '');

if (!empty($embedtype) && $embedtype== 'metabase' && $Metabase_Embed_Secret) {
	$payload = array(
		"resource"=>array('dashboard'=>vtlib_purify($_REQUEST['load'])),
		"params" => (object)array(),
		"exp" => round(time() + (10 * 60))
	);

	$token = JWT::encode($payload, $Metabase_Embed_Secret);
	$ifpage=$params['dashboard-url']."/embed/dashboard/".$token;
} else {
	$ifpage = vtlib_purify($_REQUEST['load']);
}

	$ifpage = getMergedDescription($ifpage, $current_user->id, 'Users');

if (!empty($ifpage)) {
	echo '<iframe width="100%" height="600" src="'.$ifpage.'"></iframe>';
}
?>