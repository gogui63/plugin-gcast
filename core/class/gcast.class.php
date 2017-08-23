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

/* * ***************************Includes********************************* */

class gcast extends eqLogic {
	/*     * *************************Attributs****************************** */

	private $_collectDate = '';
	public static $_widgetPossibility = array('custom' => true);

	/*     * ***********************Methode static*************************** */


	/*     * *********************Methode d'instance************************* */

	public function postSave() {
		$parle = $this->getCmd(null, 'parle');
		if (!is_object($parle)) {
			$parle = new gcastcmd();
			$parle->setLogicalId('parle');
			$parle->setIsVisible(1);
			$parle->setName(__('Parle', __FILE__));
		}
		$parle->setType('action');
		$parle->setSubType('message');
		$parle->setEqLogic_id($this->getId());
		$parle->setDisplay('title_disable', 1);
		$parle->setDisplay('message_placeholder', __('Phrase', __FILE__));
		$parle->save();
		
		$volplus = $this->getCmd(null, 'volup');
		if (!is_object($volplus)) {
			$volplus = new gcastcmd();
			$volplus->setLogicalId('volup');
			$volplus->setIsVisible(1);
			$volplus->setName(__('Vol+', __FILE__));
		}
		$volplus->setType('action');
		$volplus->setSubType('other');
		$volplus->setEqLogic_id($this->getId());
		$volplus->save();
		
		$volmoins = $this->getCmd(null, 'voldown');
		if (!is_object($volmoins)) {
			$volmoins = new gcastcmd();
			$volmoins->setLogicalId('voldown');
			$volmoins->setIsVisible(1);
			$volmoins->setName(__('Vol-', __FILE__));
		}
		$volmoins->setType('action');
		$volmoins->setSubType('other');
		$volmoins->setEqLogic_id($this->getId());
		$volmoins->save();
		
		$volume = $this->getCmd(null, 'volume');
		if (!is_object($volume)) {
			$volume = new gcastcmd();
			$volume->setLogicalId('volume');
			$volume->setIsVisible(1);
			$volume->setName(__('Volume', __FILE__));
		}
		$volume->setType('action');
		$volume->setSubType('slider');
		$volume->setEqLogic_id($this->getId());
		$volume->save();
	}

	/*     * **********************Getteur Setteur*************************** */

}

class gcastCmd extends cmd {
	/*     * *************************Attributs****************************** */

	/*     * ***********************Methode static*************************** */

	/*     * *********************Methode d'instance************************* */

	public function execute($_options = null) {
		if ($this->getType() == '') {
			return '';
		}
		$gcast = $this->getEqLogic();
		$action= $this->getLogicalId();
		$ip = $gcast->getConfiguration('addr');
		if ($action == 'parle') {
				$phrase=$_options['message'];
				$tts=$phrase;
				$moteur = $gcast->getConfiguration("moteurtts", 'picotts');
				$jeedompath=network::getNetworkAccess('internal');
				if ($moteur == 'picotts'){
					$options=$gcast->getConfiguration('picovoice','fr-FR');
				} else if ($moteur == 'ttswebvoice') {
					$options=$gcast->getConfiguration('ttswebvoice','com.google.android.tts');
				} else {
					$options=$gcast->getConfiguration('googlevoice','fr');
				}
				$cmd = '/usr/bin/python ' .dirname(__FILE__) . '/../../resources/action.py ' . $action . ' ' . $ip . ' "'.$tts.'" "'.$jeedompath.'" ' . $options . ' ' . $moteur;
		} else {
			$cmd = '/usr/bin/python ' .dirname(__FILE__) . '/../../resources/action.py ' . $action . ' ' . $ip;
		}
		log::add('gcast','debug',$cmd);
		$result=shell_exec($cmd . ' > /dev/null 2>&1 &');
	}

	/*     * **********************Getteur Setteur*************************** */
}
