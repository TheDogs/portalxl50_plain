<?php
/*
*
* @name center_announcements_index.php
* @package phpBB3 Portal XL
* @version $Id: center_announcements_index.php,v 1.4 2011/10/10 portalxl group Exp $
*
* @copyright (c) 2007, 2013 PortalXL Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
*/

/*
* Start session management
*/
$user->setup('viewtopic');

/**
* initalise some global variables
*/
global $portal_config, $config, $phpbb_root_path, $phpEx, $db, $str_where, $total_announcements, $display_notice;

/**
* Aannouncements pagination
*/
$announcement = request_var('announcement_article', -1);
$announcement = ($announcement > $portal_config['portal_number_of_announcments'] -1) ? -1 : $announcement;  

$start = request_var('anc', 0);
$start = ($start < 0) ? 0 : $start;
$limit_announcement = $portal_config['portal_number_of_announcments']; // number of announcements allowed to show for pagination
$portal_announcement_repository = ($portal_config['portal_announcement_repository']) ? true : false;
//$portal_announcements_permissions = ($portal_config['portal_announcements_permissions']) ? true : false; // Not in use at moment
$portal_show_all_announcements = ($portal_config['portal_show_all_announcements']) ? true : false; // count in all topic types
$portal_announcements_length = ($announcement < 0) ? $portal_config['portal_announcments_length'] : 0;

/*
* Fetch Posts for announcements from portal/includes/functions.php
*/
$fetch_news = phpbb_fetch_news($portal_config['portal_global_announcments_forum'], $limit_announcement, $portal_announcements_length, $portal_config['portal_announcments_day'], 'announcements', $start);

/*
* Any announcements present? If not terminate here.
*/
if (sizeof($fetch_news) == 0)
{
	$template->assign_block_vars('announcments_index_row', array(
		'S_NO_TOPICS'	=> true,
		'S_NOT_LAST'	=> false
		));

	$template->assign_var('S_CAN_READ', false);
}
else
{
	/*
	* Generate pagination
	*/
	if ($portal_announcement_repository)
	{
		if ($auth->acl_get_list($user->data['user_id'], 'f_read', true))
		{
			$str_where = '';
			$time = ( $portal_config['portal_announcments_day'] == 0 ) ? 0 : $portal_config['portal_announcments_day'];
			$post_time = ($time == 0) ? '' : 'AND topic_time > ' . (time() - $time * 86400);
			$topic_type = '(( topic_type = ' . POST_ANNOUNCE . ') OR ( topic_type = ' . POST_GLOBAL . '))';
			$str_where = ( strlen($str_where) > 0 ) ? 'AND (' . trim(substr($str_where, 0, -4)) . ')' : '';
		
			$sql = 'SELECT COUNT(topic_id) AS num_topics
				FROM ' . TOPICS_TABLE . '
				WHERE ' . $topic_type . '
					AND topic_approved = 1
					AND topic_moved_id = 0
					' . $post_time . '
					' . $str_where;
				$result = $db->sql_query($sql);
				$total_announcements = (int) $db->sql_fetchfield('num_topics');
				$result = $db->sql_query_limit($sql, $limit_announcement, $start);
		}
	}
	$db->sql_freeresult($result);
			
	$topic_tracking_info = (get_portal_tracking_info($fetch_news));

	if($announcement < 0)
	{
		for ($i = 0; $i < count($fetch_news); $i++)
		{
			if( isset($fetch_news[$i]['striped']) && $fetch_news[$i]['striped'] == true )
			{
				$open_bracket = '[ ';
				$close_bracket = ' ]';
				$read_full = $user->lang['READ_FULL'];
			}
			else
			{
				$open_bracket = '';
				$close_bracket = '';
				$read_full = '';
			}
			
			switch($fetch_news[$i]['user_rank'])
			{
				case '0': 
					$poster_image_icon = $phpbb_root_path . 'portal/images/user.png'; 
				break;
			
				case '1':	
					$poster_image_icon = $phpbb_root_path . 'portal/images/founder.png'; 
				break;

			
				case '2': 
					$poster_image_icon = $phpbb_root_path . 'portal/images/mod.png'; 
				break;
			
				default:
					$poster_image_icon = $phpbb_root_path . 'portal/images/user.png';
				break;
			}	

			$folder_img = $folder_alt = $topic_type = $folder = $folder_new = '';
			switch ($fetch_news[$i]['topic_type'])
			{
				case POST_GLOBAL:
					$folder = 'global_read';
					$folder_new = 'global_unread';
				break;
				case POST_ANNOUNCE:
					$folder = 'announce_read';
					$folder_new = 'announce_unread';
				break;
				default:
					$folder = 'topic_read';
					$folder_new = 'topic_unread';
					if ($config['hot_threshold'] && $replies >= $config['hot_threshold'] && $fetch_news[$i]['topic_status'] != ITEM_LOCKED)
					{
						$folder .= '_hot';
						$folder_new .= '_hot';
					}
				break;
			}

			if ($fetch_news[$i]['topic_status'] == ITEM_LOCKED)
			{
				$folder .= '_locked';
				$folder_new .= '_locked';
			}
			if ($fetch_news[$i]['topic_type'] == POST_GLOBAL)
			{
				$global_announce_list[$fetch_news[$i]['topic_id']] = true;
			}
			if ($fetch_news[$i]['topic_posted'])
			{
				$folder .= '_mine';
				$folder_new .= '_mine';
			}
			$folder_img = ($unread_topic) ? $folder_new : $folder;
			$folder_alt = ($unread_topic) ? 'NEW_POSTS' : (($fetch_news[$i]['topic_status'] == ITEM_LOCKED) ? 'TOPIC_LOCKED' : 'NO_NEW_POSTS');

			$forum_id = $fetch_news[$i]['forum_id'];
			$topic_id = $fetch_news[$i]['topic_id'];
			
			// topic tracking
			$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id, $global_announce_list = false);
			$unread_topic = (isset($topic_tracking_info[$topic_id]) && $fetch_news[$i]['topic_last_post_time'] > $topic_tracking_info[$topic_id]) ? true : false;
	
			// last comments
			$center_announcements_index_last_post_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $fetch_news[$i]['forum_id'] . '&amp;t=' . $fetch_news[$i]['topic_id'] . '&amp;start=' . $fetch_news[$i]['topic_last_post_id']) . '#p' . $fetch_news[$i]['topic_last_post_id'];
			// topic id
			$center_announcements_index_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $fetch_news[$i]['forum_id'] . '&amp;t=' . $fetch_news[$i]['topic_id']) . '#p' . $fetch_news[$i]['post_id'];
		
			$read_full_url = (isset($_GET['anc'])) ? 'anc='. $start . '&amp;announcement_article=' . $i . '#announcetop' : 'announcement_article=' . $i . '#announcetop=' . $i;
			$real_forum_id = ( $forum_id == 0 ) ? $fetch_news[$i]['global_id']: $forum_id;
	
			// Replies
			$replies = ($auth->acl_get('m_approve', $forum_id)) ? $fetch_news[$i]['topic_replies_real'] : $fetch_news[$i]['topic_replies'];

			// Grab icons
			$icons = $cache->obtain_icons();

			$template->assign_block_vars('announcments_index_row', array(
				'ATTACH_ICON_IMG'			=> ($fetch_news[$i]['attachment'] && $config['allow_attachments']) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
				'S_HAS_ATTACHMENTS'			=> (!empty($fetch_news[$i]['attachments'])) ? true : false,
				'S_DISPLAY_NOTICE'			=> $display_notice && $fetch_news[$i]['post_attachment'],
	
				'TOPIC_FOLDER_IMG'			=> $user->img($folder_img, $folder_alt),
				'TOPIC_FOLDER_IMG_SRC'		=> $user->img($folder_img, $folder_alt, false, '', 'src'),
				'TOPIC_FOLDER_IMG_ALT'  	=> $user->lang[$folder_alt],
				'TOPIC_ICON_IMG'			=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['img'] : '',
				'TOPIC_ICON_IMG_HEIGHT'		=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['height'] : '',
				'TOPIC_ICON_IMG_WIDTH'		=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['width'] : '',
				'FOLDER_IMG'				=> $user->img('topic_read', 'NO_NEW_POSTS'),
				
				'OPEN'						=> $open_bracket,
				'CLOSE'						=> $close_bracket,

				'S_NOT_LAST'				=> ($i < sizeof($fetch_news) - 1) ? true : false,
				'S_NUM_POSTS'				=> ($i < sizeof($fetch_news) - 1) ? true : false,
				'S_POLL'					=> $fetch_news[$i]['poll_title'],
				'S_UNREAD_INFO'				=> $unread_topic,

				'ANNOUNCE_ID'				=> $i,
				'FORUM_NAME'				=> ( $forum_id ) ? $fetch_news[$i]['forum_name'] : '',
				'IMG_POSTER_ICON'			=> '<img src="' . $poster_image_icon . '" width="16" height="16" />',
				'L_READ_FULL'				=> $read_full,
				'MINI_POST_IMG'				=> $user->img('icon_post_target', 'POST'),
				'POSTER'					=> $fetch_news[$i]['username'],
				'REPLIES'					=> $fetch_news[$i]['topic_replies'],
				'TEXT'						=> $fetch_news[$i]['post_text'],
				'TIME'						=> $fetch_news[$i]['topic_time'],
				'TITLE'						=> $fetch_news[$i]['topic_title'],
				'TOPIC_VIEWS'				=> $fetch_news[$i]['topic_views'],
				'U_VIEW_UNREAD'		        => append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($real_forum_id) ? 'f=' . $real_forum_id . '&amp;t=' : '') . $fetch_news[$i]['topic_id'] . '&amp;view=unread#unread'),
				'U_LAST_COMMENTS'			=> $center_announcements_index_last_post_url,
				'U_POST_COMMENT'			=> append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=reply&amp;' . (($real_forum_id) ? 'f=' . $real_forum_id . '&amp;' : '') . 't=' . $topic_id),
				'U_READ_FULL'				=> append_sid("{$phpbb_root_path}index.$phpEx", $read_full_url),
				'U_USER_PROFILE'			=> (($fetch_news[$i]['user_type'] == USER_NORMAL || $fetch_news[$i]['user_type'] == USER_FOUNDER) && $fetch_news[$i]['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $fetch_news[$i]['user_id']) : '',
				'U_VIEWFORUM'				=> append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $fetch_news[$i]['forum_id']),
				'U_VIEW_COMMENTS'			=> $center_announcements_index_topic_url,
				));						
	
			if( !empty($fetch_news[$i]['attachments']) )
			{
				foreach ($fetch_news[$i]['attachments'] as $attachment)
				{
					$template->assign_block_vars('announcments_index_row.attachment', array(
						'DISPLAY_ATTACHMENT'	=> $attachment)
					);
				}
			}
			
			if ($limit_announcement <> 0 && $portal_announcement_repository)
			{
				$template->assign_vars(array(
					'ANNOUNCEMENTS_PAGINATION'          => generate_repository(append_sid("{$phpbb_root_path}portal.$phpEx"), $total_announcements, $limit_announcement, $start, 'announcements'),
					'ANNOUNCEMENTS_PAGE_NUMBER'         => on_page($total_announcements, $limit_announcement, $start),
					'ANNOUNCEMENTS_TOTAL_ANNOUNCEMENTS'	=> $total_announcements,
					'S_NUM_POSTS'		 				=> ($i < count($fetch_news) - 1) ? true : false,
					'L_TOTAL_ANNOUNCEMENTS'				=> $user->lang['TOTAL_ANNOUNCEMENTS'],
					));
			}
		}
	}
	else
	{
		/*
		* Send Read Full page to screen
		*/
		switch($fetch_news[$i]['user_rank'])
		{
			case '0': 
				$poster_image_icon = $phpbb_root_path . 'portal/images/user.png'; 
			break;
		
			case '1':	
				$poster_image_icon = $phpbb_root_path . 'portal/images/founder.png'; 
			break;
				
			case '2': 
				$poster_image_icon = $phpbb_root_path . 'portal/images/mod.png'; 
			break;
				
			default:
				$poster_image_icon = $phpbb_root_path . 'portal/images/user.png';
			break;
		}	

		$folder_img = $folder_alt = $topic_type = $folder = $folder_new = '';
		switch ($fetch_news[$i]['topic_type'])
		{
			case POST_GLOBAL:
				$folder = 'global_read';
				$folder_new = 'global_unread';
			break;
			case POST_ANNOUNCE:
				$folder = 'announce_read';
				$folder_new = 'announce_unread';
			break;
			default:
				$folder = 'topic_read';
				$folder_new = 'topic_unread';
				if ($config['hot_threshold'] && $replies >= $config['hot_threshold'] && $fetch_news[$i]['topic_status'] != ITEM_LOCKED)
				{
					$folder .= '_hot';
					$folder_new .= '_hot';
				}
			break;
		}

		if ($fetch_news[$i]['topic_status'] == ITEM_LOCKED)
		{
			$folder .= '_locked';
			$folder_new .= '_locked';
		}
		if ($fetch_news[$i]['topic_type'] == POST_GLOBAL)
		{
			$global_announce_list[$fetch_news[$i]['topic_id']] = true;
		}
		if ($fetch_news[$i]['topic_posted'])
		{
			$folder .= '_mine';
			$folder_new .= '_mine';
		}
		$folder_img = ($unread_topic) ? $folder_new : $folder;
		$folder_alt = ($unread_topic) ? 'NEW_POSTS' : (($fetch_news[$i]['topic_status'] == ITEM_LOCKED) ? 'TOPIC_LOCKED' : 'NO_NEW_POSTS');

		$i = $announcement;
		$forum_id = $fetch_news[$i]['forum_id'];
		$topic_id = $fetch_news[$i]['topic_id'];
  
		$open_bracket = '[ ';
		$close_bracket = ' ]';
	
		// topic tracking
		$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id, $global_announce_list = false);
		$unread_topic = (isset($topic_tracking_info[$topic_id]) && $fetch_news[$i]['topic_last_post_time'] > $topic_tracking_info[$topic_id]) ? true : false; 
		// last comments
		$center_announcements_index_last_post_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $fetch_news[$i]['forum_id'] . '&amp;t=' . $fetch_news[$i]['topic_id'] . '&amp;start=' . @intval($phpbb_seo->seo_opt['topic_last_page'][$fetch_news[$i]['topic_id']]) ) . '#p' . $fetch_news[$i]['topic_last_post_id'];
		// topic id
		$center_announcements_index_topic_url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $fetch_news[$i]['forum_id'] . '&amp;t=' . $fetch_news[$i]['topic_id'] . '&amp;start=' . @intval($phpbb_seo->seo_opt['topic_last_page'][$fetch_news[$i]['topic_id']]) ) . '#p' . $fetch_news[$i]['topic_first_post_id'];
		
		$read_full = $user->lang['BACK_TO_PREV'];
		$read_full_url = (isset($_GET['anc'])) ? 'anc='. $start . '&amp;announce=' . $i . '#announcetop=' . $i : 'announce=' . $i . '#announcetop=' . $i;
		$real_forum_id = ( $forum_id == 0 ) ? $fetch_news[$i]['global_id']: $forum_id;
	
		// Replies
		$replies = ($auth->acl_get('m_approve', $forum_id)) ? $fetch_news[$i]['topic_replies_real'] : $fetch_news[$i]['topic_replies'];

		// Grab icons
		$icons = $cache->obtain_icons();

		$template->assign_block_vars('announcments_index_row', array(
				'ATTACH_ICON_IMG'			=> ($fetch_news[$i]['attachment'] && $config['allow_attachments']) ? $user->img('icon_topic_attach', $user->lang['TOTAL_ATTACHMENTS']) : '',
				'S_HAS_ATTACHMENTS'			=> (!empty($fetch_news[$i]['attachments'])) ? true : false,
				'S_DISPLAY_NOTICE'			=> $display_notice && $fetch_news[$i]['post_attachment'],
	
				'TOPIC_FOLDER_IMG'			=> $user->img($folder_img, $folder_alt),
				'TOPIC_FOLDER_IMG_SRC'		=> $user->img($folder_img, $folder_alt, false, '', 'src'),
				'TOPIC_FOLDER_IMG_ALT'  	=> $user->lang[$folder_alt],
				'TOPIC_ICON_IMG'			=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['img'] : '',
				'TOPIC_ICON_IMG_HEIGHT'		=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['height'] : '',
				'TOPIC_ICON_IMG_WIDTH'		=> (!empty($icons[$fetch_news[$i]['icon_id']])) ? $icons[$fetch_news[$i]['icon_id']]['width'] : '',
				'FOLDER_IMG'				=> $user->img('topic_read', 'NO_NEW_POSTS'),
				
				'OPEN'						=> $open_bracket,
				'CLOSE'						=> $close_bracket,

				'S_NOT_LAST'				=> ($i < sizeof($fetch_news) - 1) ? true : false,
				'S_NUM_POSTS'				=> ($i < sizeof($fetch_news) - 1) ? true : false,
				'S_POLL'					=> $fetch_news[$i]['poll_title'],
				'S_UNREAD_INFO'				=> $unread_topic,

				'ANNOUNCE_ID'				=> $i,
				'FORUM_NAME'				=> ( $forum_id ) ? $fetch_news[$i]['forum_name'] : '',
				'IMG_POSTER_ICON'			=> '<img src="' . $poster_image_icon . '" width="16" height="16" />',
				'L_READ_FULL'				=> $read_full,
				'MINI_POST_IMG'				=> $user->img('icon_post_target', 'POST'),
				'POSTER'					=> $fetch_news[$i]['username'],
				'REPLIES'					=> $fetch_news[$i]['topic_replies'],
				'TEXT'						=> $fetch_news[$i]['post_text'],
				'TIME'						=> $fetch_news[$i]['topic_time'],
				'TITLE'						=> $fetch_news[$i]['topic_title'],
				'TOPIC_VIEWS'				=> $fetch_news[$i]['topic_views'],
				'U_VIEW_UNREAD'		        => append_sid("{$phpbb_root_path}viewtopic.$phpEx", (($real_forum_id) ? 'f=' . $real_forum_id . '&amp;t=' : '') . $fetch_news[$i]['topic_id'] . '&amp;view=unread#unread'),
				'U_LAST_COMMENTS'			=> $center_announcements_index_last_post_url,
				'U_POST_COMMENT'			=> append_sid("{$phpbb_root_path}posting.$phpEx", 'mode=reply&amp;' . (($real_forum_id) ? 'f=' . $real_forum_id . '&amp;' : '') . 't=' . $topic_id),
				'U_READ_FULL'				=> append_sid("{$phpbb_root_path}index.$phpEx", $read_full_url),
				'U_USER_PROFILE'			=> (($fetch_news[$i]['user_type'] == USER_NORMAL || $fetch_news[$i]['user_type'] == USER_FOUNDER) && $fetch_news[$i]['user_id'] != ANONYMOUS) ? append_sid("{$phpbb_root_path}memberlist.$phpEx", 'mode=viewprofile&amp;u=' . $fetch_news[$i]['user_id']) : '',
				'U_VIEWFORUM'				=> append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $fetch_news[$i]['forum_id']),
				'U_VIEW_COMMENTS'			=> $center_announcements_index_topic_url,
			));
	
			if( !empty($fetch_news[$i]['attachments']) )
			{
				foreach ($fetch_news[$i]['attachments'] as $attachment)
				{
					$template->assign_block_vars('announcments_index_row.attachment', array(
						'DISPLAY_ATTACHMENT'	=> $attachment)
					);
				}
			}
			
	}
}

/*
* Set some template assign variables
*/
$template->assign_vars(array(
	'NEWEST_POST_IMG'			=> $user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
	'READ_POST_IMG'				=> $user->img('icon_topic_latest', 'VIEW_NEWEST_POST'),
	'S_DISPLAY_ANNOUNCEMENTS'	=> true,
	));

/*
* Set the filename of the template you want to use for this file
*/
$template->set_filenames(array(
    'body' => 'portal/block/center_announcements_index.html',
	));

?>