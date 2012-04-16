<div hidefocus="true" class="x-grid3">
	<div class="x-grid3-header">
		<div class="x-grid3-header-inner" style="float: none;">
			<div class="x-grid3-header-offset">
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<thead>
						<tr class="x-grid3-hd-row">
							<td class="x-grid3-hd x-grid3-cell x-grid3-td-1" width="300">
								<div style="" unselectable="on" class="x-grid3-hd-inner x-grid3-hd-1">
									<a href="#" class="x-grid3-hd-btn"></a>Name
								</div>
							</td>
							<td class="x-grid3-hd x-grid3-cell x-grid3-td-2" width="200">
								<div style="" unselectable="on" class="x-grid3-hd-inner x-grid3-hd-2">
									<a href="#" class="x-grid3-hd-btn"></a>Snippet Code
								</div>
							</td>
							<td class="x-grid3-hd x-grid3-cell x-grid3-td-3" width="100">
								<div style="" unselectable="on" class="x-grid3-hd-inner x-grid3-hd-3">
									<a href="#" class="x-grid3-hd-btn"></a>Total Subscribers
								</div>
							</td>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div class="x-clear"></div>
	</div>
	<div class="x-grid3-scroller" style="overflow: visible;">
		<div class="x-grid-group">
			<div class="x-grid-group-body">
				<div class="x-grid3-row x-grid3-row-alt x-grid3-row-collapsed">
					<table cellspacing="0" cellpadding="0" border="0" width="100%" class="x-grid3-row-table">
						<tbody>
							<?php if ($api->errorCode): ?>
							<tr>
								<td rowspan="2" colspan="4" tabindex="0" class="x-grid3-col x-grid3-cell x-grid3-td-expander x-selectable x-grid3-cell-first ">
									<div class="x-grid3-cell-inner x-grid3-col-1">
										Failed to load mailing lists <?php if (trim($api->errorCode != '')): ?>(Error code <?php echo $api->errorCode; ?>)<?php endif ?><br/><?php echo $api->errorMessage; ?>
									</div>
								</td>
							</tr>
							<?php else: ?>
							<?php foreach ($lists['data'] as $list): ?>
							<tr>
								<td tabindex="0" class="x-grid3-col x-grid3-cell x-grid3-td-1 x-selectable" width="300">
									<div class="x-grid3-cell-inner x-grid3-col-1"><a href="?a=<?php echo $action; ?>&amp;action=fields&amp;tab_selected=Main&amp;listId=<?php echo $list['id']; ?>"><?php echo $list['name']; ?></a></div>
								</td>
								<td tabindex="0" class="x-grid3-col x-grid3-cell x-grid3-td-2 x-selectable" width="200">
									<div class="x-grid3-cell-inner x-grid3-col-2">
										<input onclick="this.select(); return false;" readonly="readonly" style="width: 220px;" type="text" value="[[!modMailchimp? &listId=`<?=$list['id']?>`]]" />
									</div>
								</td>
								<td tabindex="0" class="x-grid3-col x-grid3-cell x-grid3-td-3 x-selectable" width="100">
									<div class="x-grid3-cell-inner x-grid3-col-3"><?php echo $list['stats']['member_count']; ?></div>
								</td>
							</tr>
							<?php endforeach ?>
							<?php endif ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
