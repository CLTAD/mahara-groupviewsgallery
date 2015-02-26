<div id="gallery">
{foreach $items.data.photos post}
        <div class="gall-cell {$post.type}">
			{if $post.type eq "html"}
				<a class="gall-cell celllink html" href="{$post.page.url}">{$post.asset.html}</a>
			{elseif $post.type eq "photo"}
				<a class="gall-cell celllink photo" href="{$post.page.url}" style="background-image:url('{$wwwroot}artefact/file/download.php?file={$post.asset.id}&view={$post.asset.view}&maxwidth=150&minheight=120'); background-color: #EEEEEE; background-repeat: repeat-x;"></a>
			{else}
			    <a class="gall-cell celllink html" href="{$post.page.url}">{$post.page.title}</a>
			{/if}
			{if $pagetype eq "shared"}
            <span class="gall-span {$post.type}">
                <a href="{$post.owner.profileurl}">
                    <div class="avatar fl"><img alt="{$post.owner.name}"  src="{$post.owner.avatarurl}" /></div>
                </a>
                <div class="ownername fl cl"><a href="{$post.owner.profileurl}">{$post.owner.name}</a></div>
				{if $post.asset.id neq "0"}
					<a href="{$wwwroot}view/artefact.php?artefact={$post.asset.id}&view={$post.asset.view}">
						<span class="imagelinkicon"></span>
						<span class="imagelink">View details</span>
					</a>
				{/if}
            </span>
            {/if}
            <span class="pagelinkicon"></span>
            <a href="{$post.page.url}"><span class="pagelink">{$post.page.title}</span></a>
        </div>
{/foreach}
</div>
<form><input id="groupid" type="hidden" value="{$groupid}"></form>
