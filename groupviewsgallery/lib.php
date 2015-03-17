<?php
/**
 * Mahara: Electronic portfolio, weblog, resume builder and social networking
 * Copyright (C) 2006-2009 Catalyst IT Ltd and others; see:
 *                         http://wiki.mahara.org/Contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    mahara
 * @subpackage blocktype-groupviewsgallery
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL
 *
 */

defined('INTERNAL') || die();

require_once('group.php');
require_once('view.php');
class PluginBlocktypeGroupViewsGallery extends SystemBlocktype {

    public static function get_title() {
        return get_string('title', 'blocktype.groupviewsgallery');
    }

    public static function get_description() {
        return get_string('description', 'blocktype.groupviewsgallery');
    }

    public static function single_only() {
        return false;
    }

    public static function allowed_in_view(View $view) {
        // only allow this block in group 'portfolio' type pages
        return $view->get('group') != null;
    }

    public static function get_categories() {
        return array('general');
    }

    public static function get_viewtypes() {
        return array('grouphomepage', 'portfolio');
    }

    public static function hide_title_on_empty_content() {
        return true;
    }

    public static function has_instance_config() {
        return true;
    }

    public static function get_instance_javascript() {
        return array('js/browse.js');
    }

    public static function instance_config_form($instance) {
        $configdata = $instance->get('configdata');
        return array(
            'showviewsoptions' => array(
                    'type' => 'radio',
                    'title' => get_string('showviewsoptions', 'blocktype.groupviewsgallery'),
                    'description' => get_string('showviewsoptionsdesc', 'blocktype.groupviewsgallery'),
                    'options' => array(
                            1 => get_string('displaygroupviews', 'blocktype.groupviewsgallery'),
                            2 => get_string('displaysharedviews', 'blocktype.groupviewsgallery'),
                    ),
                    'defaultvalue' => isset($configdata['showviewsoptions']) ? $configdata['showviewsoptions'] : 1,
            ),
            'viewsperpage' => array(
                'type'  => 'text',
                'title' => get_string('viewsperpage','blocktype.groupviewsgallery'),
                'description' => get_string('viewsperpagedesc', 'blocktype.groupviewsgallery'),
                'defaultvalue' => isset($configdata['viewsperpage']) ? $configdata['viewsperpage'] : 20,
                'size' => 4,
                'rules' => array('integer' => true, 'minvalue' => 1, 'maxvalue' => 50),
            ),
            'moreoptionstoggle' => array(
                    'type'         => 'fieldset',
                    'collapsible'  => true,
                    'collapsed'    => true,
                    'legend'       => get_string('moreoptions',  'blocktype.groupviewsgallery'),
                    'value'        => '<div id="moreoptionstoggle" class="retracted arrow"><label>Advanced options...</label></div>',
                    'elements'     => array(
                            'filteroptions' => array(
                                    'type' => 'radio',
                                    'title' => get_string('filteroptions', 'blocktype.groupviewsgallery'),
                                    'description' => get_string('filteroptionsdesc', 'blocktype.groupviewsgallery'),
                                    'options' => array(
                                            1 => get_string('filterbyquery', 'blocktype.groupviewsgallery'),
                                            2 => get_string('filterbytag', 'blocktype.groupviewsgallery'),
                                    ),
                                    'defaultvalue' => isset($configdata['filteroptions']) ? $configdata['filteroptions'] : 1,
                            ),
                            'filterquery' => array(
                                    'type'  => 'text',
                                    'title' => get_string('filterquery','blocktype.groupviewsgallery'),
                                    'description' => get_string('filterquerydesc', 'blocktype.groupviewsgallery'),
                                    'defaultvalue' => isset($configdata['filterquery']) ? $configdata['filterquery'] : null,
                                    'size' => 50,
                            ),
                            'excludeimagesids' => array(
                                    'type'  => 'text',
                                    'title' => get_string('excludeimagesids','blocktype.groupviewsgallery'),
                                    'description' => get_string('excludeimagesidsdesc', 'blocktype.groupviewsgallery'),
                                    'defaultvalue' => isset($configdata['excludeimagesids']) ? $configdata['excludeimagesids'] : null,
                                    'size' => 50,
                            ),
                            'excludeimagesfilenames' => array(
                                    'type'  => 'text',
                                    'title' => get_string('excludeimagesfilenames','blocktype.groupviewsgallery'),
                                    'description' => get_string('excludeimagesfilenamesdesc', 'blocktype.groupviewsgallery'),
                                    'defaultvalue' => isset($configdata['excludeimagesfilenames']) ? $configdata['excludeimagesfilenames'] : null,
                                    'size' => 50,
                            ),
                    )
            ),
        );
    }

    public static function instance_config_validate($form, $values) {
        if (!empty($values['excludeimagesids'])) {
            $regex = '/^[0-9, ]+$/';
            if (!preg_match($regex, trim($values['excludeimagesids'])) ) {
                $result['message'] = get_string('invalidids', 'blocktype.groupviewsgallery');
                $form->set_error(null, $result['message']);
                $form->reply(PIEFORM_ERR, $result);
            }
        }
        if (!empty($values['excludeimagesfilenames'])) {
            $regex = '/^[ A-Za-z0-9\,\._-]+$/';
            if (!preg_match($regex, trim($values['excludeimagesfilenames'])) ) {
                $result['message'] = get_string('invalidfilenames', 'blocktype.groupviewsgallery');
                $form->set_error(null, $result['message']);
                $form->reply(PIEFORM_ERR, $result);
            }
        }
    }

    public static function default_copy_type() {
        return 'shallow';
    }

    public static function render_instance(BlockInstance $instance, $editing=true) {
        $groupid = $instance->get_view()->get('group');
        if (!$groupid) {
            return get_string('nogroupfound', 'blocktype.groupviewsgallery');;
        }

        $offset = 0;
        $configdata = $instance->get('configdata');
        $limit = isset($configdata['viewsperpage']) ? $configdata['viewsperpage'] : 20;
        $items = self::get_data($groupid, $offset, $limit, null, $configdata);

        if(!isset($items) || $items['count'] === 0) {
            return '<div align="center">' . get_string('nopagesfound', 'blocktype.groupviewsgallery') . '</div>';
        }
        self::build_browse_list_html($items, $groupid, $instance->get('id'), $configdata);

        $smarty = smarty();
        $smarty->assign_by_ref('items', $items);
        return $smarty->fetch('blocktype:groupviewsgallery:index.tpl');
    }

    public static function get_data($groupid, $offset=0, $limit=20, $instanceid, $configdata=null) {
        global $USER;
        $texttitletrim = 20;
        $mintextlength = 20;
        $textarrays = array();
        $tagsarray = array();
        $excludedimageidsarray = array();
        $excludedimagefilenamesarray = array();
        $items = array();
        $data = array();
        $contents = array();

        if (empty($configdata)) {
            // Pagination requests from browse.json.php will just pass an instance id
            // However, when submitting the config form, we need the new submitted configdata, passed from render_instance
            $instance = new BlockInstance($instanceid);
            $configdata = $instance->get('configdata');
        }

        if(!defined('GROUP')) {
          define('GROUP', $groupid);
        }
        // get the currently requested group
        $group = group_current_group();
        $role = group_user_access($group->id);
        $showgroupviews = $showsharedviews = true;

        if ($configdata['showviewsoptions'] == 1) {
            $showsharedviews = false;
        }

        if ($configdata['showviewsoptions'] == 2) {
            $showgroupviews = false;
        }

        if (!$showgroupviews && !$showsharedviews) {
            return (array());
        }

        if (!empty($configdata['excludeimagesids'])) {
            $allimageids = $configdata['excludeimagesids'];
            $excludedimageidsarray = explode(',', $allimageids);
            foreach ($excludedimageidsarray as $key => $id) {
                $excludedimageidsarray[$key] = intval(trim($id));
            }
            $excludedimageidsarray = array_unique($excludedimageidsarray);
        }

        if (!empty($configdata['excludeimagesfilenames'])) {
            $allimagefilenames = $configdata['excludeimagesfilenames'];
            $excludedimagefilenamesarray = explode(',', $allimagefilenames);
            foreach ($excludedimagefilenamesarray as $key => $id) {
                $excludedimagefilenamesarray[$key] = trim($id);
            }
            $excludedimagefilenamesarray = array_unique($excludedimagefilenamesarray);
        }

        if (isset($configdata['filterquery']) ) {

            $filtertype = isset($configdata['filteroptions'])? $configdata['filteroptions'] : 1;
            // 1 = use query filter
            // 2 = use tag filter
            if (strlen(trim($configdata['filterquery'])) == 0) {
                $filtertype = 0;
                $tag = $query = null;
            } else if ($filtertype == 1) {
                $query = trim($configdata['filterquery']);
                $tag = null;
            } else if ($filtertype == 2) {
                $tag = trim($configdata['filterquery']);
                $query = null;
            }

        } else {
            $filtertype = 0;
            $tag = $query = null;
        }

        if ($role) {
            $data = self::get_views($query, $tag, $limit, $offset, $groupid,  $showgroupviews, $showsharedviews);
            $allviews = new stdclass();
            $sharedcount = $data['sharedviews']->count;
            $groupcount = $data['groupviews']->count;
            $allviews->data = array_merge($data['sharedviews']->data, $data['groupviews']->data);
            $allviews->count = $sharedcount + $groupcount;

            foreach($allviews->data as $view) {

                $viewid = $view['id'];
                $fullurl = $view['fullurl'];
                $viewtitle = str_shorten_text($view['displaytitle'], $texttitletrim, true);

                if ($view['owner'] == null && isset($view['group'])) {
                    $viewownername = str_shorten_text($view['groupdata']->name, 24, true);
                    $groupobj = new stdclass();
                    $groupobj->id = $view['group'];
                    $profileurl = group_homepage_url($groupobj);
                    $avatarurl = get_config('wwwroot') . 'blocktype/groupviewsgallery/theme/raw/static/images/group_avatar.png';
                } else {
                    $userid = $view['owner'];
                    $userobj = new User();
                    $userobj->find_by_id($userid);
                    $viewownername = str_shorten_text($userobj->get('firstname') . " " . $userobj->get('lastname'), 24, true);
                    $profileurl = profile_url($userobj);
                    $avatarurl = profile_icon_url($view['user']->id, 50, 50);
                }

                $theview = new View($viewid);
                $artefactsinview = $theview->get_artefact_metadata();
                if ($artefactsinview) {

                    $artefactid = 0;

                    foreach($artefactsinview as $artefact) {
                        if($artefact->artefacttype == 'image' && !in_array($artefact->id, $excludedimageidsarray) && !in_array($artefact->title, $excludedimagefilenamesarray)) {
                            $artefactid = $artefact->id;
                            $artefacttype = 'photo';
                            $artefacthtml = '';
                            break;
                        }
                    }

                    if ($artefactid === 0) {
                        foreach($artefactsinview as $artefact) {
                            if($artefact->artefacttype == 'html' && strlen($artefact->description) >= $mintextlength) {
                                $artefactid = $artefact->id;
                                $artefacttype = 'html';
                                $artefacthtml = strip_tags($artefact->description);
                                $artefacthtml = str_shorten_text($artefacthtml, 30, true);
                                break;
                            }
                            $artefactid = 0;
                            $artefacttype = 'none';
                        }
                    }

                } else {
                    $artefactid = 0;
                    $artefacttype = 'none';
                    $artefacttype = $artefacthtml = '';
                }

                //the items variable below requires the contents array to be in this format
                $contents['photos'][] = array(
                    "asset" => array (
                            "id" => $artefactid,
                            "view" => $viewid,
                            "html" => $artefacthtml
                            ),
                    "type" => $artefacttype,
                    "page" => array(
                                "url" => $fullurl,
                                "title" => $viewtitle
                    ),
                    "owner" => array(
                                "name" => $viewownername,
                                "profileurl" => $profileurl,
                                "avatarurl" => $avatarurl
                    )
                );
            }

            $items = array(
                    'count'  => $allviews->count,
                    'data'   => $contents,
                    'offset' => $offset,
                    'limit'  => $limit,
            );
        }

        return $items;
    }

    function get_views($query=null, $tag=null, $limit, $offset, $groupid, $showgroupviews=false, $showsharedviews=false) {
        if ($showgroupviews) {
            $sort = array(array('column' => 'ctime', 'desc' => true));
            $data['groupviews'] = View::view_search($query, null, (object) array('group' => $groupid), null, $limit, $offset, true, $sort, $types=array('portfolio', 'profile'), $collection=false, $accesstypes=null, $tag);
        } else {
            $data['groupviews'] = (object) array('data' => array(), 'count' => 0);
        }
        if ($showsharedviews) {
            $_POST['tag'] = $tag;
            $_POST['query'] = $query;
            // You may wish to customise the View::get_sharedviews_data function to include an ORDER BY clause in the sql query
            // You can then pass the order type here, like so:
            // $order = 'dateasc';
            // $data['sharedviews'] = View::get_sharedviews_data($limit, $offset, $groupid, false, $order);
            $data['sharedviews'] = View::get_sharedviews_data($limit, $offset, $groupid, false);
        } else {
            $data['sharedviews'] = (object) array('data' => array(), 'count' => 0);
        }
        return $data;
    }

    public static function build_browse_list_html(&$items, $groupid, $instanceid, $configdata=null) {

        if (empty($configdata)) {
            // Pagination requests from browse.json.php will just pass an instance id
            // However, when submitting the config form, we need the new submitted configdata, passed from render_instance
            $instance = new BlockInstance($instanceid);
            $configdata = $instance->get('configdata');
        }

        if (isset($configdata['showviewsoptions'])) {
            $pagetype = $configdata['showviewsoptions'] == 1 ? 'group' : 'shared';
        } else {
            $pagetype = 'group';
        }

        $smarty = smarty_core();
        $smarty->assign_by_ref('items', $items);
        $smarty->assign('wwwroot', get_config('wwwroot'));
        $smarty->assign('groupid', $groupid);
        $smarty->assign('pagetype', $pagetype);
        $items['tablerows'] = $smarty->fetch('blocktype:groupviewsgallery:browselist.tpl'); // the 'tablerows' naming is required for pagination script
        $pagination = self::build_browse_pagination(array(
            'id' => 'browselist_pagination',
            'url' => get_config('wwwroot') . 'blocktype/groupviewsgallery/lib.php',
            'jsonscript' => 'blocktype/groupviewsgallery/browse.json.php',
            'datatable' => 'browselist', // the pagination script expects a table with this id
            'count' => $items['count'],
            'limit' => $items['limit'],
            'offset' => $items['offset'],
            'firsttext' => '',
            'previoustext' => '',
            'nexttext' => '',
            'lasttext' => '',
            'numbersincludefirstlast' => false,
            'resultcounttextsingular' => 'Page',
            'resultcounttextplural' => 'Pages',
            'blockinstanceid' => $instanceid
        ));
        $items['pagination'] = $pagination['html'];
    }

    /**
    * Builds pagination links for HTML display.
    *
    * @param array $params Options for the pagination
    */
    function build_browse_pagination($params) {
        // Bail if the required attributes are not present
        $required = array('url', 'count', 'limit', 'offset');
        foreach ($required as $option) {
            if (!isset($params[$option])) {
                throw new ParameterException('You must supply option "' . $option . '" to build_pagination');
            }
        }

        // Work out default values for parameters
        if (!isset($params['id'])) {
            $params['id'] = substr(md5(microtime()), 0, 4);
        }

        $params['offsetname'] = (isset($params['offsetname'])) ? $params['offsetname'] : 'offset';
        if (isset($params['forceoffset']) && !is_null($params['forceoffset'])) {
            $params['offset'] = (int) $params['forceoffset'];
        }
        else if (!isset($params['offset'])) {
            $params['offset'] = param_integer($params['offsetname'], 0);
        }

        // Correct for odd offsets
        $params['offset'] -= $params['offset'] % $params['limit'];
        $params['firsttext'] = (isset($params['firsttext'])) ? $params['firsttext'] : get_string('first');
        $params['previoustext'] = (isset($params['previoustext'])) ? $params['previoustext'] : get_string('previous');
        $params['nexttext']  = (isset($params['nexttext']))  ? $params['nexttext'] : get_string('next');
        $params['resultcounttextsingular'] = (isset($params['resultcounttextsingular'])) ? $params['resultcounttextsingular'] : get_string('result');
        $params['resultcounttextplural'] = (isset($params['resultcounttextplural'])) ? $params['resultcounttextplural'] : get_string('results');

        if (!isset($params['numbersincludefirstlast'])) {
            $params['numbersincludefirstlast'] = true;
        }
        if (!isset($params['numbersincludeprevnext'])) {
            $params['numbersincludeprevnext'] = true;
        }

        if (!isset($params['extradata'])) {
            $params['extradata'] = null;
        }

        // Begin building the output
        $output = '<div id="' . $params['id'] . '" class="pagination';
        if (isset($params['class'])) {
            $output .= ' ' . hsc($params['class']);
        }
        $output .= '">';

        if ($params['limit'] < $params['count']) {
            $pages = ceil($params['count'] / $params['limit']);
            $page = $params['offset'] / $params['limit'];

            $last = $pages - 1;
            if (!empty($params['lastpage'])) {
                $page = $last;
            }
            $next = min($last, $page + 1);
            $prev = max(0, $page - 1);

            // Build a list of what pagenumbers will be put between the previous/next links
            $pagenumbers = array();
            if ($params['numbersincludefirstlast']) {
                $pagenumbers[] = 0;
            }
            if ($params['numbersincludeprevnext']) {
                $pagenumbers[] = $prev;
            }
            $pagenumbers[] = $page;
            if ($params['numbersincludeprevnext']) {
                $pagenumbers[] = $next;
            }
            if ($params['numbersincludefirstlast']) {
                $pagenumbers[] = $last;
            }
            $pagenumbers = array_unique($pagenumbers);

            // Build the first/previous links
            $isfirst = $page == 0;
            $setlimit = true;
            $output .= self::build_browse_pagination_pagelink('first',
                                                                $params['url'],
                                                                $setlimit,
                                                                $params['limit'],
                                                                0,
                                                                '&laquo; First ' . $params['firsttext'],
                                                                get_string('firstpage'),
                                                                $isfirst,
                                                                $params['offsetname'],
                                                                $params['blockinstanceid']
                                                            );

            $output .= self::build_browse_pagination_pagelink('prev',
                                                                $params['url'],
                                                                $setlimit,
                                                                $params['limit'],
                                                                $params['limit'] * $prev,
                                                                '&larr; Previous ' . $params['previoustext'],
                                                                get_string('prevpage'),
                                                                $isfirst,
                                                                $params['offsetname'],
                                                                $params['blockinstanceid']
                                                            );

            // Build the pagenumbers in the middle
            foreach ($pagenumbers as $k => $i) {
                if ($k != 0 && $prevpagenum < $i - 1) {
                    $output .= '���';
                }
                if ($i == $page) {
                    $output .= '<span class="selected">' . ($i + 1) . '</span>';
                }
                else {
                    $output .= self::build_browse_pagination_pagelink('',
                                                                        $params['url'],
                                                                        $setlimit,
                                                                        $params['limit'],
                                                                        $params['limit'] * $i,
                                                                        $i + 1,
                                                                        '',
                                                                        false,
                                                                        $params['offsetname'],
                                                                        $params['blockinstanceid']
                                                                    );
                }
                $prevpagenum = $i;
            }

            // Build the next/last links
            $islast = $page == $last;
            $output .= self::build_browse_pagination_pagelink('next',
                                                                $params['url'],
                                                                $setlimit,
                                                                $params['limit'],
                                                                $params['limit'] * $next,
                                                                $params['nexttext'] . ' Next &rarr;',
                                                                get_string('nextpage'),
                                                                $islast,
                                                                $params['offsetname'],
                                                                $params['blockinstanceid']
                                                            );

        }

        // Close the container div
        $output .= '</div>';

        return array('html' => $output);

    }

    function build_browse_pagination_pagelink($class, $url, $setlimit, $limit, $offset, $text, $title, $disabled=false, $offsetname='offset', $blockinstanceid) {
        $url = "javascript:Browse.filtercontent(" . $limit . "," . $offset . "," . $blockinstanceid . ");";

        $output = '';

        if (!$disabled) {
            $output = '<span class="pagination';
            $output .= ($class) ? " $class" : '';
            $output .= '">'
            . '<a href="' . $url . '" title="' . $title
            . '">' . $text . '</a></span>';
        }

        return $output;
    }
}