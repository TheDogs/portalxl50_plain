<?php
/** 
*
* @name acp_portal_xl_banners.php [Italian]
* @package phpBB3 Portal XL 5.0
* @version $Id: acp_portal_xl_banners.php,v 1.3 2009/10/18 portalxl group Exp $
*
* @copyright (c) 2007, 2011 Portal XL Group
* @copyright (c) 2009, 2011 portalxl.eu - upgrade translation on 2011/12/07
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(   
	'TITLE_PARTNERS' 				=> 'Gestione banner 88x31px',
	'TITLE_PARTNERS_EXPLAIN'		=> 'Puoi creare/modificare/cancellare piani di visualizzazione banner di tuoi partners o affialiti.<br /> Il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/" per un link esterno. Links interni ai banners possono essere aggiunti come "portal/images/banners/88x31/yourdomain.com.gif". la dimensione massima consentita per le immagini è di 88x31 pixel, le immagini più grandi veranno ridimensionate automaticamente. Puoi aggiungere altri parametri, selezionare un limite casuale per visualizzare i tuoi banner.',
 
 	'TITLE_HO' 						=> 'Gestione banner orizzontali 400x60px',
	'TITLE_HO_EXPLAIN'				=> 'Puoi creare/modificare/cancellare piani di visualizzazione banner di tuoi partners o affialiti. <br /> Il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/", ad esempio "portal/images/banners/400x60/yourdomain.com.gif" per banner orizzontali. La dimensione massima consentita per le immagini è di 400x60px, le immagini più grandi saranno ridimensionate automaticamente. Puoi aggiungere altri parametri selezionare un limite casuale per visualizzare i tuoi banner.',

	'TITLE_VE' 						=> 'Gestione banners verticali 130x600px',
	'TITLE_VE_EXPLAIN'				=> 'Puoi creare/modificare/cancellare piani di visualizzazione banner di tuoi partners o affialiti. <br /> Il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/", ad esempio "portal/images/banners/130x600/yourdomain.com.gif" per banner verticali. La dimensione consentita per le immagini è di 130x600px, le immagini più grandi saranno ridimensionate automaticamente. Puoi aggiungere altri parametri selezionare un limite casuale per visualizzare i tuoi banner.',
	
	'PORTAL_PARTNERS_EDIT_HEADER'	=> 'Aggiungi o modifica Partner',
	'ACP_MANAGE_PARTNERS'			=> 'Aggiungi o modifica Partner',
	'ACP_PARTNERS'					=> 'Gestione Partner',
	'ACP_PARTNERS_EXPLAIN'			=> 'Aggiungi, modifica, cancella un Partner',
	'ADD_PARTNERS'					=> 'Aggiungi Partner',
	'MUST_SELECT_PARTNERS'			=> 'Seleziona Partner',
	'PARTNERS'						=> 'Partners',	
	'PARTNERS_ADDED'				=> 'Partner aggiunto',
	'PARTNERS_DESCRIPTION'			=> 'Descrizione',
	'PARTNERS_DESCRIPTION_EXPLAIN'	=> 'La descrizione partner deve rispettare le direttive del tema utilizzato.',
	'PARTNERS_IMG'			        => 'Logo url',
	'PARTNERS_IMG_EXPLAIN'			=> 'Partner Logo url, il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/"',
	'PARTNERS_LOGO'					=> 'Logo (88x31px)',
	'PARTNERS_REMOVED'				=> 'Partner eliminato',
	'PARTNERS_UPDATED'				=> 'Partner modificato',
	'PARTNERS_URL'					=> 'Url sito',
	'PARTNERS_URL_EXPLAIN'			=> 'Partner url sito, il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/"',
	'RESET_PARTNERS' 				=> 'Annulla',

	'PORTAL_PARTNERS_DISPLAY' 			=> 'Visualizza valori casuali',
	'PORTAL_PARTNERS_DISPLAY_EXPLAIN' 	=> 'Seleziona quanti banners vuoi mostrare nel blocco.',
	'PORTAL_PARTNERS_DISPLAY_VALUE' 	=> 'Visualizzati banners simultanei',
	
	'PORTAL_BANNERS_EDIT_HEADER'	=> 'Aggiungi o modifica ',
	'ACP_MANAGE_BANNERS'			=> 'Aggiungi o modifica banner',
	'ACP_BANNERS'					=> 'Gestione banners ',
	'ACP_BANNERS_EXPLAIN'			=> 'Aggiungi, modifica, cancella banner',
	'ADD_BANNERS'					=> 'Aggiungi banner',
	'MUST_SELECT_BANNERS'			=> 'Seleziona banner',
	'BANNERS'						=> 'Banners',
	'BANNERS_ADDED'					=> 'Banner aggiunto',
	'BANNERS_DESCRIPTION'			=> 'Descrizione',
	'BANNERS_DESCRIPTION_EXPLAIN'	=> 'La descrizione banner deve rispettare le direttive del tema utilizzato.',
	'BANNERS_IMG_EXPLAIN'			=> 'Banner logo url, il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/"',
	'BANNERS_REMOVED'				=> 'Banner eliminato',
	'BANNERS_UPDATED'				=> 'Banner modificato',
	'BANNERS_URL'					=> 'Url sito',
	'BANNERS_URL_EXPLAIN'			=> 'Banner url sito, il percorso è quello relativo alla root del forum, es. "http://www.yourdomain.com/"',
	'RESET_BANNERS' 				=> 'Annulla',
	
	'BANNERS_IMG_HO'			    => 'Url logo horizzontale 400x60px',
	'BANNERS_LOGO_HO'				=> 'Logo (400x60px)',

	'BANNERS_IMG_VE'			    => 'Url logo verticale 130x600px',
	'BANNERS_LOGO_VE'				=> 'Logo (130x600px)',
	
));

?>