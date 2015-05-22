<?php
class Portal_View_Helper_DropDownCheckList extends Zend_View_Helper_Abstract
{

	
	public function DropDownCheckList($Opref)
	{
		$script ="<script type='text/javascript'>
					$(document).ready(function() {";
		$script .= "$('.".$Opref->_ParamName_UserLocation."').dropdownchecklist( { icon: {}, width: 150 ,firstItemChecksAll: true } );";
		$script .= "$('.".$Opref->_ParamName_UserYear."').dropdownchecklist( { icon: {}, width: 150 ,firstItemChecksAll: true } );";
		$script .= "});</script>";
	
		echo $script;
	}
	
	
}