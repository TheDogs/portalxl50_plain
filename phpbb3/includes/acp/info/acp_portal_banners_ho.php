<?php
/** 
*
* @name acp_portal_banners_ho.php
* @package phpBB3 Portal XL 5.0
* @version $Id: acp_portal_banners_ho.php,v 1.2 2009/05/15 22:13:03 portalxl group Exp $
*
* @copyright (c) 2007, 2013 Portal XL Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @package module_install
*/

class acp_portal_banners_ho_info
{
	function module()
	{
		return array(
			'filename'	=> 'acp_portal_banners_ho',
			'title'		=> 'ACP_PORTAL_CAT_BANNERS_HO',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'banners_ho'		=> array('title' => 'ACP_PORTAL_ADMIN_BANNERS_HO', 'auth' => 'acl_a_board', 'cat' => array('ACP_PORTAL', 'ACP_PORTAL_ADMIN')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}

?>
