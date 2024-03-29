<?php defined("SYSPATH") or die("No direct script access.") ?>
<!-- ******************************************************************************************* -->
<script type="text/javascript">
/*--------------------------------------------------|
| dTree 2.05 | www.destroydrop.com/javascript/tree/ |
|---------------------------------------------------|
| Copyright (c) 2002-2003 Geir Landrö               |
|                                                   |
| This script can be used freely as long as all     |
| copyright messages are intact.                    |
|----------------------------------------------------\
| Modified for "Treeview" Gallery3 Module by itzling |
| http://codex.galleryproject.org/User:Itzling       |
| Updated: 02.03.2014                                |
|---------------------------------------------------*/
// Node object
function Node(id, pid, name, url, thumb, children, description, title, target, icon, iconOpen, open) {
	this.id = id;
	this.pid = pid;
	this.name = name;
	this.url = url;
	this.thumb = thumb;
	this.children = children;
	this.description = description;
	this.title = title;
	this.target = target;
	this.icon = icon;
	this.iconOpen = iconOpen;
	this._io = open || false;
	this._is = false;
	this._ls = false;
	this._hc = false;
	this._ai = 0;
	this._p;
};

// Tree object
function dTree(objName) {
	this.config = {
		target			: null,
		folderLinks		: true,
		useSelection		: true,
		useCookies		: true,
		useLines		: true,
		useIcons		: false,
		useStatusText		: false,
		closeSameLevel		: false,
		inOrder			: false,
		cookiePath		: null,
		cookieDomain	: null
	}
	this.obj = objName;
	this.aNodes = [];
	this.aIndent = [];
	this.root = new Node(-1);
	this.selectedNode = null;
	this.selectedFound = false;
	this.completed = false;
};

// Adds a new node to the node array
dTree.prototype.add = function(id, pid, name, url, thumb, children, description, title, target, icon, iconOpen, open) {
	this.aNodes[this.aNodes.length] = new Node(id, pid, name, url, thumb, children, description, title, target, icon, iconOpen, open);
};

// Open/close all nodes
dTree.prototype.openAll = function() {
	this.oAll(true);
};
dTree.prototype.closeAll = function() {
	this.oAll(false);
};

// Outputs the tree to the page
dTree.prototype.toString = function() {
	var str = '<div class="dtree">\n';
	if (document.getElementById) {
		if (this.config.useCookies) this.selectedNode = this.getSelected();
		str += this.addNode(this.root);
	} else str += 'Browser not supported.';
	str += '</div>';
	if (!this.selectedFound) this.selectedNode = null;
	this.completed = true;
	return str;
};

// Creates the tree structure
dTree.prototype.addNode = function(pNode) {
	var str = '';
	var n=0;
	if (this.config.inOrder) n = pNode._ai;
	for (n; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == pNode.id) {
			var cn = this.aNodes[n];
			cn._p = pNode;
			cn._ai = n;
			this.setCS(cn);
			if (!cn.target && this.config.target) cn.target = this.config.target;
			if (cn._hc && !cn._io && this.config.useCookies) cn._io = this.isOpen(cn.id);
			if (!this.config.folderLinks && cn._hc) cn.url = null;
			if (this.config.useSelection && cn.id == this.selectedNode && !this.selectedFound) {
					cn._is = true;
					this.selectedNode = n;
					this.selectedFound = true;
			}
			str += this.node(cn, n);
			if (cn._ls) break;
		}
	}
	return str;
};

// Creates the node icon, url and text
dTree.prototype.node = function(node, nodeId) {
	var str = '<div class="dTreeNode" title="' + node.name + '">' + this.indent(node, nodeId);
	if (this.config.useIcons) {
		if (!node.icon) node.icon = (this.root.id == node.pid) ? this.icon.root : ((node._hc) ? this.icon.folder : this.icon.node);
		if (!node.iconOpen) node.iconOpen = (node._hc) ? this.icon.folderOpen : this.icon.node;
		if (this.root.id == node.pid) {
			node.icon = this.icon.root;
			node.iconOpen = this.icon.root;
		}
		str += '<img id="i' + this.obj + nodeId + '" src="' + ((node._io) ? node.iconOpen : node.icon) + '" alt="" />';
	}
	if (node.url) {
		str += '<a id="s' + this.obj + nodeId + '" class="' + ((this.config.useSelection) ? ((node._is ? 'nodeSel' : 'node')) : 'node') + '" href="' + node.url + '"';
		if (node.title) str += ' title="' + node.title + '"';
		if (node.target) str += ' target="' + node.target + '"';
		if (this.config.useStatusText) str += ' onmouseover="window.status=\'' + node.name + '\';return true;" onmouseout="window.status=\'\';return true;" ';
		if (this.config.useSelection && ((node._hc && this.config.folderLinks) || !node._hc))
			str += ' onclick="' + this.obj + '.s(' + nodeId + ');"';
		str += '>';
	}
	else if ((!this.config.folderLinks || !node.url) && node._hc && node.pid != this.root.id)

    <? if(module::get_var("treeview", "dtree_openonmouseover")) { ?>
 		str += '<a href="#" onmouseover="javascript: ' + this.obj + '.o(' + nodeId + ');" class="node">';
    <? } else { ?>
 		str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');" class="node">';
    <? } ?>
	str += node.name;

	sprev = '';
  <? // Display album details (thumbnail, description, containing photos)
  if (module::get_var("treeview", "showdetails")) { ?>
	sprev = '<ul class="g-treeview-details">';
	sprev += node.thumb;
	sprev += "<li><h2>" + node.name + "</h2></li>";
	sprev += '<li class="g-metadata">';
	sprev += '<strong class="caption"><?= t("Contained photos:") ?></strong> ' + node.children + '</li>';
	sprev += '<li class="g-metadata">' + node.description + '</li>';
	sprev += '</ul>';
  <? } ?>

	if (node.url || ((!this.config.folderLinks || !node.url) && node._hc)) str += ( sprev + '</a>');

	str += '</div>';
	if (node._hc) {
		str += '<div id="d' + this.obj + nodeId + '" class="clip" style="display:' + ((this.root.id == node.pid || node._io) ? 'block' : 'none') + ';">';
		str += this.addNode(node);
		str += '</div>';
	}
	this.aIndent.pop();
	return str;
};

// Adds the empty and line icons
dTree.prototype.indent = function(node, nodeId) {
	var str = '';
	if (this.root.id != node.pid) {
		for (var n=0; n<this.aIndent.length; n++)
			str += '<img src="' + ( (this.aIndent[n] == 1 && this.config.useLines) ? this.icon.line : this.icon.empty ) + '" alt="" />';
		(node._ls) ? this.aIndent.push(0) : this.aIndent.push(1);
		if (node._hc) {
            <? if(module::get_var("treeview", "dtree_openonmouseover")) { ?>
                str += '<a href="#" onmouseover="javascript: ' + this.obj + '.o(' + nodeId + ');">';
            <? } else { ?>
                str += '<a href="javascript: ' + this.obj + '.o(' + nodeId + ');">';
            <? } ?>
            str += '<img id="j' + this.obj + nodeId + '" src="';
			if (!this.config.useLines) str += (node._io) ? this.icon.nlMinus : this.icon.nlPlus;
			else str += ( (node._io) ? ((node._ls && this.config.useLines) ? this.icon.minusBottom : this.icon.minus) : ((node._ls && this.config.useLines) ? this.icon.plusBottom : this.icon.plus ) );
			str += '" alt="" /></a>';
		} else str += '<img src="' + ( (this.config.useLines) ? ((node._ls) ? this.icon.joinBottom : this.icon.join ) : this.icon.empty) + '" alt="" />';
	}
	return str;
};

// Checks if a node has any children and if it is the last sibling
dTree.prototype.setCS = function(node) {
	var lastId;
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id) node._hc = true;
		if (this.aNodes[n].pid == node.pid) lastId = this.aNodes[n].id;
	}
	if (lastId==node.id) node._ls = true;
};

// Returns the selected node
dTree.prototype.getSelected = function() {
	var sn = this.getCookie('cs' + this.obj);
	return (sn) ? sn : null;
};

// Highlights the selected node
dTree.prototype.s = function(id) {
	if (!this.config.useSelection) return;
	var cn = this.aNodes[id];
	if (cn._hc && !this.config.folderLinks) return;
	if (this.selectedNode != id) {
		if (this.selectedNode || this.selectedNode==0) {
			eOld = document.getElementById("s" + this.obj + this.selectedNode);
			eOld.className = "node";
		}
		eNew = document.getElementById("s" + this.obj + id);
		eNew.className = "nodeSel";
		this.selectedNode = id;
		if (this.config.useCookies) this.setCookie('cs' + this.obj, cn.id);
	}
};

// Toggle Open or close
dTree.prototype.o = function(id) {
	var cn = this.aNodes[id];
	this.nodeStatus(!cn._io, id, cn._ls);
	cn._io = !cn._io;
	if (this.config.closeSameLevel) this.closeLevel(cn);
	if (this.config.useCookies) this.updateCookie();
};

// Open or close all nodes
dTree.prototype.oAll = function(status) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n]._hc && this.aNodes[n].pid != this.root.id) {
			this.nodeStatus(status, n, this.aNodes[n]._ls)
			this.aNodes[n]._io = status;
		}
	}
	if (this.config.useCookies) this.updateCookie();
};

// Opens the tree to a specific node
dTree.prototype.openTo = function(nId, bSelect, bFirst) {
	if (!bFirst) {
		for (var n=0; n<this.aNodes.length; n++) {
			if (this.aNodes[n].id == nId) {
				nId=n;
				break;
			}
		}
	}
	var cn=this.aNodes[nId];
	if (cn.pid==this.root.id || !cn._p) return;
	cn._io = true;
	cn._is = bSelect;
	if (this.completed && cn._hc) this.nodeStatus(true, cn._ai, cn._ls);
	if (this.completed && bSelect) this.s(cn._ai);
	else if (bSelect) this._sn=cn._ai;
	this.openTo(cn._p._ai, false, true);
};

// Closes all nodes on the same level as certain node
dTree.prototype.closeLevel = function(node) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.pid && this.aNodes[n].id != node.id && this.aNodes[n]._hc) {
			this.nodeStatus(false, n, this.aNodes[n]._ls);
			this.aNodes[n]._io = false;
			this.closeAllChildren(this.aNodes[n]);
		}
	}
}

// Closes all children of a node
dTree.prototype.closeAllChildren = function(node) {
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n].pid == node.id && this.aNodes[n]._hc) {
			if (this.aNodes[n]._io) this.nodeStatus(false, n, this.aNodes[n]._ls);
			this.aNodes[n]._io = false;
			this.closeAllChildren(this.aNodes[n]);
		}
	}
}

// Change the status of a node(open or closed)
dTree.prototype.nodeStatus = function(status, id, bottom) {
	eDiv	= document.getElementById('d' + this.obj + id);
	eJoin	= document.getElementById('j' + this.obj + id);
	if (this.config.useIcons) {
		eIcon	= document.getElementById('i' + this.obj + id);
		eIcon.src = (status) ? this.aNodes[id].iconOpen : this.aNodes[id].icon;
	}
	eJoin.src = (this.config.useLines)?
	((status)?((bottom)?this.icon.minusBottom:this.icon.minus):((bottom)?this.icon.plusBottom:this.icon.plus)):
	((status)?this.icon.nlMinus:this.icon.nlPlus);
	eDiv.style.display = (status) ? 'block': 'none';
};


// [Cookie] Clears a cookie
dTree.prototype.clearCookie = function() {
	var now = new Date();
	var yesterday = new Date(now.getTime() - 1000 * 60 * 60 * 24);
	this.setCookie('co'+this.obj, 'cookieValue', yesterday);
	this.setCookie('cs'+this.obj, 'cookieValue', yesterday);
};

// [Cookie] Sets value in a cookie
dTree.prototype.setCookie = function(cookieName, cookieValue, expires, path, domain, secure) {
	path = path || this.config.cookiePath;
	domain = domain || this.config.cookieDomain;
	document.cookie =
		escape(cookieName) + '=' + escape(cookieValue)
		+ (expires ? '; expires=' + expires.toGMTString() : '')
		+ (path ? '; path=' + path : '')
		+ (domain ? '; domain=' + domain : '')
		+ (secure ? '; secure' : '');
};

// [Cookie] Gets a value from a cookie
dTree.prototype.getCookie = function(cookieName) {
	var cookieValue = '';
	var posName = document.cookie.indexOf(escape(cookieName) + '=');
	if (posName != -1) {
		var posValue = posName + (escape(cookieName) + '=').length;
		var endPos = document.cookie.indexOf(';', posValue);
		if (endPos != -1) cookieValue = unescape(document.cookie.substring(posValue, endPos));
		else cookieValue = unescape(document.cookie.substring(posValue));
	}
	return (cookieValue);
};

// [Cookie] Returns ids of open nodes as a string
dTree.prototype.updateCookie = function() {
	var str = '';
	for (var n=0; n<this.aNodes.length; n++) {
		if (this.aNodes[n]._io && this.aNodes[n].pid != this.root.id) {
			if (str) str += '.';
			str += this.aNodes[n].id;
		}
	}
	this.setCookie('co' + this.obj, str);
};

// [Cookie] Checks if a node id is in a cookie
dTree.prototype.isOpen = function(id) {
	var aOpen = this.getCookie('co' + this.obj).split('.');
	for (var n=0; n<aOpen.length; n++)
		if (aOpen[n] == id) return true;
	return false;
};

// If Push and pop is not implemented by the browser
if (!Array.prototype.push) {
	Array.prototype.push = function array_push() {
		for(var i=0;i<arguments.length;i++)
			this[this.length]=arguments[i];
		return this.length;
	}
};
if (!Array.prototype.pop) {
	Array.prototype.pop = function array_pop() {
		lastElement = this[this.length-1];
		this.length = Math.max(this.length-1,0);
		return lastElement;
	}
};
</script>

<style type="text/css">
/*--------------------------------------------------|
| dTree 2.05 | www.destroydrop.com/javascript/tree/ |
|---------------------------------------------------|
| Copyright (c) 2002-2003 Geir Landrö               |
|----------------------------------------------------\
| Modified for "Treeview" Gallery3 Module by itzling |
| Updated: 02.03.2014                                |
|---------------------------------------------------*/

.dtree {
    font-size: 1.05em;
    white-space: nowrap;
}
.dtree img {
    border: 0px;
    vertical-align: middle;
}
.dtree a.node, .dtree a.nodeSel {
    white-space: nowrap;
    padding: 1px 2px 1px 2px;
}
.dtree a.nodeSel {
    font-style: italic;
}
.dtree .clip {
    overflow: hidden;
}

/* hide Sidebar Block "albumtree" */
div#g-albumtree.g-block {
    display: none!important;
}
</style>
<div id="g-treeview-header" class="g-page-block">
  <h1><?= html::clean($title) ?></h1>
</div>
<div id="g-treeview-body" class="g-page-block-content">
  <div class="g-treeview-root">
<script type="text/javascript">
// <![CDATA[
var albumTree = new dTree('albumTree');
var albumTree_images = '<?= url::base(false) ?>modules/treeview/images/'
albumTree.icon = {
root            : albumTree_images + 'base.gif',
folder          : albumTree_images + 'folder.gif',
folderOpen      : albumTree_images + 'imgfolder.gif',
node            : albumTree_images + 'imgfolder.gif',
empty           : albumTree_images + 'empty.gif',
line            : albumTree_images + 'line.gif',
join            : albumTree_images + 'join.gif',
joinBottom      : albumTree_images + 'joinbottom.gif',
plus            : albumTree_images + 'plus.gif',
plusBottom      : albumTree_images + 'plusbottom.gif',
minus           : albumTree_images + 'minus.gif',
minusBottom     : albumTree_images + 'minusbottom.gif',
nlPlus          : albumTree_images + 'nolines_plus.gif',
nlMinus         : albumTree_images + 'nolines_minus.gif'
};
albumTree.config.useLines = true;
albumTree.config.useIcons = false;
albumTree.config.useCookies = false;
albumTree.config.closeSameLevel = <? if(module::get_var("treeview", "dtree_closesamelevel")) { ?>true<? } else { ?>false<? } ?>;
albumTree.config.cookiePath = '<?= item::root()->url() ?>';
albumTree.config.cookieDomain = '';
{ var pf = '<?= item::root()->url() ?>';
<?
function addtree($album){
?>
albumTree.add(<?= $album->id -1 ?>, <?= $album->parent_id -1 ?>, "<?= html::purify($album->title) ?>", pf+'<?= $album->relative_url() ?>'<? if (module::get_var("treeview", "showdetails")) { ?>, '<?= "<li>".$album->thumb_img(array("class" => "g-thumbnail"))."</li>" ?>', <?= $album->viewable()->children_count(array(array("type", "=", "photo"))); ?>, '<?= str_replace("\n", "<br>", html::purify($album->description)) ?>'<? } ?>);
<?
  foreach ($album->viewable()->children(null, null, array(array("type", "=", "album"))) as $child){
    addtree($child);
  }
}
addtree($root);
?>
 }
document.write(albumTree);
// ]]>
</script>
  </div>

<? // List dynamic albums provided by "dynamic", "tag_cloud_page"
$show_dynamic = ((module::get_var("treeview", "showdynamic")) && (module::is_active("dynamic"))) ? true : false;
$show_tagcloudpage = ((module::get_var("treeview", "showtagcloudpage")) && (module::is_active("tag_cloud_page"))) ? true : false;
    if ($show_dynamic || $show_tagcloudpage) {
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("More Contents") ?></h1>
  </div>
  <div class="g-treeview-albumnav g-treeview-root">
    <div class="g-treeview-album">
        <? if ($show_dynamic) { ?>
          <div class="dTreeNode">
              <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
              <a href="<?= url::site("dynamic/updates/") ?>"><?= t("Recent changes") ?></a>
          </div>
          <div class="dTreeNode">
              <? if ($show_tagcloudpage) { ?>
                <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
              <? } else { ?>
                  <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
              <? } ?>
              <a href="<?= url::site("dynamic/popular/") ?>"><?= t("Most viewed") ?></a>
          </div>
        <? } ?>
        <? if ($show_tagcloudpage) { ?>
          <div class="dTreeNode">
              <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
              <a href="<?= url::site("tag_cloud_page") ?>"><?= t("Tag Cloud") ?></a>
          </div>
        <? } ?>
    </div>
  </div>
<? } ?>

<? // List Static Pages provided by "pages" module
    if ( (module::get_var("treeview", "showpages")) && (module::is_active("pages")) ):
?>
  <div id="g-treeview-header" class="g-page-block">
    <h1><?= t("Pages Links") ?></h1>
  </div>
  <div class="g-treeview-albumnav g-treeview-root">
<?
      // Create a new list of all Pages and their links.
      // Query the database for all existing pages.
      //  If at least one page exists, display the sidebar block.
      $query = ORM::factory("static_page");
      $pages = $query->order_by("title", "ASC")->find_all();
      $pages_count = count($pages);
      if ($pages_count > 0) {
        // Loop through each page and generate an HTML list of their links and titles.
      foreach ($pages as $one_page) {
?>
        <div class="g-treeview-album">
          <div class="dTreeNode">
          <?php
            $pages_counter++;
            if( $pages_counter < $pages_count ) { ?>
              <img src="<?= url::base(false) ?>modules/treeview/images/join.gif">
            <? } else { ?>
              <img src="<?= url::base(false) ?>modules/treeview/images/joinbottom.gif">
            <? } ?>
          <a href="<?= url::site("pages/show/". $one_page->name) ?>"><?= t($one_page->title) ?>
            <? if (module::get_var("treeview", "showdetails")): ?>
              <ul class="g-treeview-details">
              <li><h2><?= html::purify($one_page->title) ?></h2></li>
              <li class="g-metadata"><?= substr(strip_tags($one_page->html_code, '<br><b><strong><h1><h2><h3><h4><h5><h6><p><div>'), 0, 300) ?> ...</li>
              </ul>
            <? endif ?>
          </a>
          </div>
        </div>
<?
        }
      }
?>
  </div>
<? endif ?>
</div>
