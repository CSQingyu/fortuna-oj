<?php $this->load->model('contests'); ?>

<fieldset>
<legend><strong>Statistic</strong></legend>

	<div class="row-fluid">
		<div class="span6">
			<div id="verdicts" style="max-width:90%"></div>
			
		</div>
		
		<div class="span6">
			<div id="categories" style="max-width:90%"></div>
<!--			<select id="category">
				<?php foreach ($categorization as $id => $name) { ?>
					<option value="<?=$id?>"><?=$name?></option>
				<?php } ?>
			</select>
			<button id="stat" class="btn btn-primary btn-small pull-right">Stat!</button>
			<hr />
			
			<div id="category_chart"></div>-->
		</div>
	</div>
	<br />
	<div class="row-fluid">
	<span class="span3 offset9">
		Ordered By
		<span class="btn-group" data-toggle="buttons-radio">
			<button type="button" class="btn active" onclick="$('.by_contest').hide();$('.individual').show();">
				pid
			</button>
			<button type="button" class="btn" onclick="$('.individual').hide();$('.by_contest').show();">
				contest
			</button>
		</span>
	</span>

	<div class="span11 accordion" id="problems">

		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#problems" data-target="#accepted">
					<strong>Problems Accepted</strong>
				</a>
			</div>
			
			<div id="accepted" class="accordion-body collapse">
				<div class="accordion-inner">
					<div class="individual">
						<?php foreach ($statistic->accepted as $row) {?>
							<span class="badge badge-info">
								<a href="#main/show/<?=$row->pid?>"><?=$row->pid?></a>
							</span>
						<?php } ?>
					</div>
					<div class="by_contest" style="display:none">
						<table class="table table-hover">
							<tr><th>cid</th><th>Contest</th><th>Problems</th></tr>
							<?php
								$current = -1;
								foreach ($statistic->accepted_in_contests as $row)
								{
									if ($row->cid != $current)
									{
										if ($current > -1) echo "</td>\n</tr>\n";
										$title = $this->contests->load_contest_title($row->cid);
										echo "<tr>\n<td>$row->cid</td><td><a href='#contest/home/$row->cid'>$title</a></td>\n<td>";
										$current = $row->cid;
									}
									echo "<span class='badge badge-info'><a href='#main/show/$row->pid'>$row->pid</a></span>\n";
								}
								if ($current > -1) echo "</td>\n</tr>\n";
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<div class="accordion-group">
			<div class="accordion-heading">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#problems" data-target="#unaccepted">
					<strong>Problems Tried But Failed</strong>
				</a>
			</div>
			
			<div id="unaccepted" class="accordion-body collapse">
				<div class="accordion-inner">
					<div class="individual">
						<?php foreach ($statistic->unaccepted as $row) {?>
							<span class="badge badge-info">
								<a href="#main/show/<?=$row->pid?>"><?=$row->pid?></a>
							</span>
						<?php } ?>
					</div>
					<div class="by_contest" style="display:none">
						<table class="table table-hover">
							<tr><th>cid</th><th>Contest</th><th>Problems</th></tr>
							<?php
								$current = -1;
								foreach ($statistic->unaccepted_in_contests as $row)
								{
									if ($row->cid != $current)
									{
										if ($current > -1) echo "</td>\n</tr>\n";
										$title = $this->contests->load_contest_title($row->cid);
										echo "<tr>\n<td>$row->cid</td><td><a href='#contest/home/$row->cid'>$title</a></td>\n<td>";
										$current = $row->cid;
									}
									echo "<span class='badge badge-info'><a href='#main/show/$row->pid'>$row->pid</a></span>\n";
								}
								if ($current > -1) echo "</td>\n</tr>\n";
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	</div>
</fieldset>

<script type="text/javascript">
	verdicts_pie = [{
		type: 'pie',
		data: [
			[ 'Other',    <?=$statistic->verdict[-2] + $statistic->verdict[3] + $statistic->verdict[9]?> ],
			[ 'Pending',    <?=$statistic->verdict[-1]?> ],
			{
				name: 'Accepted',
				y: <?=$statistic->verdict[0]?>,
				sliced: true,
				selected: true
			},
			['PE',    <?=$statistic->verdict[1]?>],
			['WA',    <?=$statistic->verdict[2]?>],
			['OLE',    <?=$statistic->verdict[4]?>],
			['MLE',    <?=$statistic->verdict[5]?>],
			['TLE',    <?=$statistic->verdict[6]?>],
			['RE',    <?=$statistic->verdict[7]?>],
			['CE',    <?=$statistic->verdict[8]?>],
		]
	}]
	
	categories_column = [
		<?php foreach ($statistic->categories as $row) { ?>
			{
				name: '<?=$row->name?>',
				data: [<?=$row->count?>]
			},
		<?php } ?>
	]

	$(document).ready(function() {
		$("#stat").click(function() {
			category = $("#category").val()
			return false
		})
	})
	
	function render() {
		render_pie('#verdicts', 'Submission Verdicts', verdicts_pie)
		render_column('#categories', 'Accepted Categories', categories_column)
	}

	if ( typeof (Highcharts) == 'undefined') {
		$.getScript("https://cdn.bootcss.com/highcharts/3.0.2/highcharts.js", function() {
			$.getScript("https://cdn.bootcss.com/highcharts/3.0.2/modules/exporting.js", function() {
				initialize_chart()
				render()
			})
		})
	} else render()
	
</script>
