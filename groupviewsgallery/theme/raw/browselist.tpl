<div id="gallery">
{foreach $items.data.photos post}
        <div class="gall-cell {$post.type}" style="width:{$items.cellwidth}px;">
			{if $post.type eq "html"}
			     <div class="padded-content" style="height:{$items.cellheight + 13}px">
				    <a class="gall-cell celllink html" href="{$post.page.url}">{$post.asset.html}</a>
				 </div>
			{elseif $post.type eq "photo"}
			     <div class="unpadded-content" style="height:{$items.cellheight + 33}px">
				     <a class="gall-cell celllink photo" href="{$post.page.url}" style="background-image:url('{$wwwroot}artefact/file/download.php?file={$post.asset.id}&view={$post.asset.view}&maxwidth={$items.cellwidth * 2}&minheight={$items.cellheight}'); background-color: #EEEEEE; background-repeat: repeat-x;height:{$items.cellheight}px"></a>
				 </div>
			{else}
				<div class="padded-content" style="height:{$items.cellheight + 13}px">
                    <a class="gall-cell celllink html" href="{$post.page.url}">{$post.page.title}</a>
				</div>
			{/if}
			{if $pagetype eq "shared"}
            <span class="gall-span {$post.type}">
                <div class="padded-content" style="height:{$items.cellheight + 13}px">
                    <a href="{$post.owner.profileurl}">
                        <div class="avatar fl"><img alt="{$post.owner.name}" src="{$post.owner.avatarurl}" /></div>
                    </a>
                    <div class="ownername fl cl"><a href="{$post.owner.profileurl}">{$post.owner.name}</a></div>
    				{if $post.asset.id neq "0"}
    					<a href="{$wwwroot}view/artefact.php?artefact={$post.asset.id}&view={$post.asset.view}">
    						<span class="imagelinkicon"></span>
    						<span class="imagelink">View details</span>
    					</a>
    				{/if}
				</div>
            </span>
            {/if}
            <span class="pagelinkicon"></span>
            <a href="{$post.page.url}"><span class="pagelink">{$post.page.title}</span></a>
        </div>
{/foreach}
</div>
<form><input id="groupid" type="hidden" value="{$groupid}"></form>
