<?php
/**
 * Centurion
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@centurion-project.org so we can send you a copy immediately.
 *
 * @category    Centurion
 * @package     Centurion_Contrib
 * @subpackage  Auth
 * @copyright   Copyright (c) 2008-2011 Octave & Octave (http://www.octaveoctave.com)
 * @license     http://centurion-project.org/license/new-bsd     New BSD License
 * @version     $Id$
 */

/**
 * @category    Centurion
 * @package     Centurion_Contrib
 * @subpackage  Auth
 * @copyright   Copyright (c) 2008-2011 Octave & Octave (http://www.octaveoctave.com)
 * @license     http://centurion-project.org/license/new-bsd     New BSD License
 * @author      Florent Messa <florent.messa@gmail.com>
 */

class Portal_Model_DbTable_AlertOrganization extends Centurion_Db_Table_Abstract
{
	protected $_name = 'portal_alert_organization';

	protected $_primary = array('alert_id', 'organization_id');

	protected $_meta = array('verboseName'   => 'alert_organization',
			'verbosePlural' => 'alert_organizations');

	protected $_rowClass = 'Portal_Model_DbTable_Row_AlertOrganization';

	protected $_referenceMap = array(
			'alert'   =>  array(
					'columns'       => 'alert_id',
					'refColumns'    => 'id',
					'refTableClass' => 'Portal_Model_DbTable_Alert',
					'onDelete'      => self::CASCADE,
					'onUpdate'      => self::RESTRICT
			),
			'organization'   =>  array(
					'columns'       => 'organization_id',
					'refColumns'    => 'id',
					'refTableClass' => 'Portal_Model_DbTable_Organization',
					'onDelete'      => self::CASCADE,
					'onUpdate'      => self::RESTRICT
			)
	);
}