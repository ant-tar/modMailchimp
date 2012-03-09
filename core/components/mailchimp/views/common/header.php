<div class="container">
	<div>
		<h2><?=$page_title?></h2>
	</div>
	<div>
		<div class="x-tab-panel-header x-unselectable x-tab-panel-header-plain" style="-moz-user-select: none;">
			<div class="x-tab-strip-wrap">
				<ul class="x-tab-strip x-tab-strip-top">
					<? foreach ($tabs as $tab): ?>
					<li <?=$tab == $tab_selected ? 'class="x-tab-strip-active"' : ''?>>
						<a href="?a=<?=$action?>&amp;tab_selected=<?=$tab?>" class="x-tab-right">
							<em class="x-tab-left">
								<span class="x-tab-strip-inner">
									<span class="x-tab-strip-text"><?=$tab?></span>
								</span>
							</em>
						</a>
					</li>
					<? endforeach ?>
					<li class="x-tab-edge"></li>
					<div class="x-clear"></div>
				</ul>
			</div>
			<div class="x-tab-strip-spacer">
			</div>
		</div>
		<div class="x-tab-panel-body x-tab-panel-body-top" style="padding: 1em; height: auto;">