Group Views Gallery
================================

### A Mahara plugin for browsing through pages in groups via a gallery view

*See licence below for standard disclaimer - use this plugin at your own risk.*


This plugin is a ```blocktype``` Mahara plugin, which presents users with a gallery of images which link through to the group-related pages on which they are published. The content block can be added to any page created within a group, including the group homepage.

The plugin offers the option to show either **Group Pages** or **Pages Shared to the Group**, and includes some other advanced configuration options. (See Usage below.)


Credits
--

This plugin was originally based on Auckland University of Technology's [GroupImageDisplay](https://github.com/CFLAT-AUT/Mahara-GroupImageDisplay) plugin, which was in turn based on my previous Mahara plugins, [Browse](https://github.com/CLTAD/mahara-browse) and [BrowseProfiles](https://github.com/CLTAD/mahara-browseprofiles)
 
Requirements
---
Browse requires Mahara 1.6 or later.



Installation
---

* Copy the 'groupviewsgallery' folder to your Mahara installation, inside the folder htdocs/blocktype.
* Visit the Site Administration->Extensions->Plugin administration page and install the new plugin.


Usage
---

###Normal usage

Add the block to any group page by dragging on a **Group pages gallery** block from the **General** menu, when editing the content of the Mahara page.

The gallery shows a thumbnail of the first image it finds in the group page. If no image is found in the page, a text extract from the first text box on the page is shown. If there is no text box, the title of the page is displayed instead.

In the case of **Pages Shared to the Group**, mousing over the thumbnail shows the page author's avatar, which links back to the author's profile page. In the case of **Group Pages**, the author is the group itself, so this option has been removed.

The number of thumbnails per page can be defined when configuring the block.


###Advanced options

There are some advanced options when configuring the block:

* The pages shown in the gallery can be filtered by a keyword, which can be applied to **Page title, description and tags**, or just to **Page tags**. If no filter keyword is added, no filtering will take place.
*  Certain images can be blocked from showing in the gallery. This can done by adding a list of image IDs or filenames. This is useful if many of the pages have the same header image, for example. Blocking that particular header image means that the gallery will not show the same image for multiple pages.


Technical Notes
---
**Group Pages** are ordered by time of creation, with the most recent first.
The order of **Pages Shared to the Group** can be customised by adding an ```ORDER BY``` clause to the SQL query in Mahara's ```View::get_sharedviews_data``` function. There is some commented code in the plugin which references this.

Fork Me
---
Please feel free to update or adapt this plugin.


Licence
---

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
