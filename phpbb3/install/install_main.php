<?php
/**
*
* @package install_main.php
* @package Modification Installer for phpBB3 Portal XL
* @version $Id: install_main.php,v 1.1.1.1 2009/05/15 04:03:28 portalxl group Exp $
*
* @copyright (c) 2007, 2013 PortalXL Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
* @some code borrowed from phpBB's installer
* @copyright (c) 2005 phpBB Group
*/

/**
*/

if ( !defined('IN_INSTALL') )
{
	// Someone has tried to access the file direct. This is not a good idea, so exit
	exit;
}

if (!empty($setmodules))
{
	$module[] = array(
		'module_type'		=> 'install',
		'module_title'		=> 'OVERVIEW',
		'module_filename'	=> substr(basename(__FILE__), 0, -strlen($phpEx)-1),
		'module_order'		=> 0,
		'module_subs'		=> array('INTRO', 'LICENSE', 'SUPPORT'),
		'module_stages'		=> '',
		'module_reqs'		=> ''
	);
}

/**
* Main Tab - Installation
* @package install
*/
class install_main extends module
{
	function install_main(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($mode, $sub)
	{
		global $lang, $template, $language;

		switch ($sub)
		{
			case 'intro' :
				$title = $lang['PORTAL_INSTALL_INTRO'];
				$body = $lang['PORTAL_OVERVIEW_BODY'];
			break;

			case 'license' :
				$title = $lang['GPL'];
				$body = implode("<br/>\n", file('docs/COPYING'));
			break;

			case 'support' :
				$title = $lang['PORTAL_SUB_SUPPORT'];
				$body = $lang['PORTAL_SUPPORT_BODY'];
			break;
		}

		$this->tpl_name = 'install_main';
		$this->page_title = $title;

		$template->assign_vars(array(
			'TITLE'		=> $title,
			'BODY'		=> $body,

			'S_LANG_SELECT'	=> '<select id="language" name="language">' . $this->p_master->inst_language_select($language) . '</select>',
		));
	}
}

?>
