<?php
/**
 * iTop's Portal
 *
 * iTop's Portal is free software; you can redistribute it and/or modify	
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * iTop's portal is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License.
 * If not, see <http://www.gnu.org/licenses/> 
 *  
 * @copyright   Copyright (c) 2015 Emmaunel Lozachmeur (http://emmanuel.synology.me)
 * @version     $Id$
 * @author      Emmanuel Lozachmeur <emmanuel.lozachmeur@gmail.com>
 */

final class Portal_Version
{
	const VERSION = '1.2-beta';
	
	//Return an Array with the name of iTop
	// and the version of iTop (2.3.1.4567 for example)
	public function getItopVersion(){
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$AiTopVersion = $webservice->getiTopVersion();
		return $AiTopVersion;
	}
	
	public static function getItopVersionNumber(){
		$webservice = Zend_Controller_Action_HelperBroker::getStaticHelper('ItopWebservice');
		$AiTopVersion = $webservice->getiTopVersion();
		return $AiTopVersion['version'];
	}
	
	/**
	 * Compare the specified iTop version string $version
	 * with the current iTop version the portal is using.
	 *
	 * @param  string  $version  A version string (e.g. "0.7.1").
	 * @return int           -1 if the $version is older,
	 *                           0 if they are the same,
	 *                           and +1 if $version is newer.
	 *
	 */
	public static function compareItopVersion($version)
	{
		$version = strtolower($version);
		$version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
		$currentItop = Portal_Version::getItopVersionNumber();
		//Zend_Debug::dump($version);
		//Zend_Debug::dump($currentItop);
		return version_compare($version, $currentItop);
	}
	
	
	//Since iTop 2.3.1, the log can contain Html with pictures ...
	public function hasHtmlLog() {
		//Zend_Debug::dump(Portal_Version::getItopVersionNumber());
		//Zend_Debug::dump(Portal_Version::compareItopVersion('2.3.1.0000'));
		// if 2.3.1.0000 is older than our current iTop Version, we may have Html content
		if ( Portal_Version::compareItopVersion('2.3.1.0000') < 0) {
			return TRUE;
		}
		else { return FALSE;}
	}
	
}