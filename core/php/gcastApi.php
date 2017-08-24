<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
if (!jeedom::apiAccess(init('apikey'), 'gcast')) {
	echo __('Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action (gcast)', __FILE__);
	die();
}
$content = file_get_contents('php://input');
$json = json_decode($content, true);
$id = init('id');
$eqLogic = gcast::byId($id);
if (!is_object($eqLogic)) {
	echo json_encode(array('text' => __('Id inconnu : ', __FILE__) . init('id')));
	die();
}
$query = utf8_encode(init('query'));
log::add('gcast', 'debug', 'Demande reçu ' . $query);
$parameters['plugin'] = 'gcast';
$cmd = $eqLogic->getCmd(null, 'parle');
if (is_object($cmd) && $cmd->askResponse($query)) {
	log::add('gcast', 'debug', 'Répondu à un ask en cours');
	die();
}
$reply = interactQuery::tryToReply(trim($query), $parameters);
log::add('gcast', 'debug', 'Interaction ' . print_r($reply, true));
$parlecmd = $eqLogic->getCmd(null,'parle');
$parlecmd->execCmd(array('message' => str_replace(array('[',']') , ' ', $reply['reply'])));
die();
