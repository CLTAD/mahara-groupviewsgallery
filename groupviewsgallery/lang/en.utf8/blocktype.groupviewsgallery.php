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

$string['title'] = 'Group pages gallery';
$string['description'] = 'Visual display of pages related to the group';
$string['nopagesfound'] = 'No gallery pages found yet';
$string['nogroupfound'] = 'Error retrieving group';
$string['noviewsselected'] = 'You haven\'t selected any pages to show!';
$string['invalidids'] = 'Ids of images to exclude should be numerical, separated by commas!';
$string['invalidfilenames'] = 'Filenames of images should be alphanumerical, separated by commas! They can include underscores, hyphens and full stops.';
$string['showviewsoptions'] = 'Pages to display';
$string['moreoptions'] = 'Advanced options';
$string['showviewsoptionsdesc'] = 'Choose type of pages to show in the gallery';
$string['displaygroupviews'] = 'Group pages';
$string['viewsperpage'] = 'Thumbnails per page';
$string['viewsperpagedesc'] = 'How many thumbnails to show per page, from 1 - 50';
$string['thumbnailsizewidth'] = 'Thumbnail width';
$string['thumbnailsizewidthdesc'] = 'Width of each thumbnail in pixels, from 100 - 600. Default is 150.';
$string['thumbnailsizeheight'] = 'Thumbnail height';
$string['thumbnailsizeheightdesc'] = 'Height of each thumbnail in pixels, from 100 - 600. Default is 120.';
$string['displaygroupviewsdesc'] = 'Group pages - pages created in the group';
$string['filteroptions'] = 'Filter options';
$string['filteroptionsdesc'] = 'Choose a filter type here, then add a filter query below';
$string['filterbyquery'] = 'Search page titles, descriptions and tags';
$string['filterbytag'] = 'Search tags only';
$string['filterquery'] = 'Filter query';
$string['filterquerydesc'] = 'Enter keyword to filter content by';
$string['excludeimagesids'] = 'Exclude images by ID';
$string['excludeimagesidsdesc'] = 'Don\'t use specified images for gallery thumbnails. Useful if, for example, you have the same header image on many pages. <br />Enter file ids separated by commas (e.g. 3071,209). To find the id, view the uploaded image\'s link in \'My Files\' or the Group Files area.';
$string['excludeimagesfilenames'] = 'Exclude images by filename';
$string['excludeimagesfilenamesdesc'] = 'Don\'t use specified images for gallery thumbnails. Enter file names separated by commas (e.g. header.jpg,group_banner.png). It\'s recommended that you either give your excluded image files distinctive names to avoid unintentionally blocking other images, or use the ID option above.';
$string['displaysharedviews'] = 'Pages shared to the group';
$string['displaysharedviewsdesc'] = 'Shared pages - pages shared by group members from their individual portfolios';
$string['defaulttitledescription'] = 'A default title will be generated if you leave the title field blank';
