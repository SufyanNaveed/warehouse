	<style type="text/css">
		table{
			border-collapse: collapse;
			padding: 5px;
		}
		.border-bottom{
			border-bottom: 1px solid #ccc;
			border-bottom-style: dotted;
		}
		.top-border-only{
			border-top: 1px solid black;
		}
		.bottom-border-only{
			border-bottom: 1px solid black;
		}
		.left-border-only{
			border-left: 1px solid black;
		}
		.right-border-only{
			border-right: 1px solid black;
		}
		.td-border-only{
			border: 1px solid black;
		}
		table tr td{
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left:5px;
		}
	</style>

	<table width="100%" border="1" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="4" align="center" style="padding-top: 0px; padding-bottom: 0px;">
				<table width="100%" cellspacing="0">
					<tr>
						<td width="70%" valign="center">
							<h1>Profit & Loss Report</h1>
						</td>
						<td align="right" style="border-left: 1px solid #ccc;padding-right: 10px;">
							<h4>Vakratunda System Pvt Ltd.</h4><br/>
							<span>from <?=$start_date?> to <?=$end_date?></span>
						</td>
					</tr>
				</table>
				
				
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				Debit (Dr)
			</td>
			<td colspan="2" align="center">
				Credit (Cr)
			</td>
		</tr>
		<tr>
			<td width="25%">
				Particulars
			</td>
			<td width="25%">
				Amount (Rs)
			</td>
			<td width="25%">
				Particulars
			</td>
			<td width="25%">
				Amount (Rs)
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<table width="100%" border="0">
					<tr>
						<td width="25%">
							To Opening Stock
						</td>
						<td width="25%" class="border-bottom">
							<?=number_format((float)$opening_stock, 2, '.', '')?>
						</td>
						<td width="25%">
							By Sales (<b><?=$total_sales?></b>) <br> less: Sales returns(<b><?=$total_sales_return?></b>)
						</td>
						<td width="25%" class="border-bottom">
							<?=number_format((float)($total_sales-$total_sales_return), 2, '.', '')?>
						</td>
					</tr>
					<tr>
						<td width="25%">
							To Purchases(<b><?=$total_purchase?></b>) <br>less: Purchase returns(<b><?=$total_purchase_return?></b>)
						</td>
						<td class="border-bottom">
							<?=number_format((float)($total_purchase-$total_purchase_return), 2, '.', '')?>
						</td>
						<td width="25%">
							By Closing Stock
						</td>
						<td class="border-bottom">
							<?=number_format((float)$closing_stock, 2, '.', '')?>
						</td>
					</tr>
					<?php 
						$gross_profit 	= 0.0;
						$gross_loss 	= 0.0;

						$s_c = $closing_stock  + ($total_sales-$total_sales_return);
						$p_o = $opening_stock  + ($total_purchase-$total_purchase_return); 	

						if($s_c > $p_o)
						{
							$gross_profit = $s_c - $p_o;
						}
						else if($s_c <= $p_o)
						{
							$gross_loss = abs($s_c - $p_o);	
						}
					?>
					<tr>
						<td width="25%">
							To gross profit (c/d)
							<br/>
							(In case of gross profit)
						</td>
						<td class="border-bottom">
							<?=number_format((float)$gross_profit, 2, '.', '')?>
						</td>
						<td width="25%">
							By gross loss (c/d)
							<br/>
							(In case of gross loss)
						</td>
						<td class="border-bottom">
							<?=number_format((float)$gross_loss, 2, '.', '')?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
				<table border="0" width="100%" >
					<tr>
						<td width="25%"></td>
						<td width="25%" class="left-border-only">
							<?=($p_o+$gross_profit)?>
						</td>
					</tr>
				</table>
			</td>
			<td colspan="2" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
				<table border="0" width="100%" >
					<tr>
						<td width="25%"></td>
						<td width="25%" class="left-border-only">
							<?=($s_c+$gross_loss)?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%">
					
					<tr>
						<td width="50%">
							To gross loss b/d
						</td>
						<td class="border-bottom">
							<?=$gross_loss?>
						</td>
					</tr>
					<?php 
						foreach ($expenses as $row) {
					?>
					<tr>
						<td>
							To <?=strtolower($row->title)?>
						</td>
						<td class="border-bottom">
							<?=$row->closing_balance?>
						</td>
					</tr>
					<?php 
						}
					?>
					<tr>
						<td>
							To rates & taxes
						</td>
						<td class="border-bottom">
							<?=$taxes?>							
						</td>
					</tr>
					<tr>
						<td>
							To discount allowed
						</td>
						<td class="border-bottom">
							<?=$discount?>
						</td>
					</tr>
					<tr>
						<td>
							To travelling expenses
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To net profit transferred to B/S
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To travelling expenses
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>

				</table>
			</td>
			<td colspan="2">
				<table width="100%">
					<tr>
						<td width="50%">
							To gross loss b/d
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To salaries
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To rates & taxes
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To insurance
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To depericiation
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To bad debts
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To advertising
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To discount allowed
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To travelling expenses
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To net profit transferred to B/S
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>
					<tr>
						<td>
							To travelling expenses
						</td>
						<td class="border-bottom">
							
						</td>
					</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
				<table border="0" width="100%" >
					<tr>
						<td width="25%"></td>
						<td width="25%" class="left-border-only">
							250000 /-
						</td>
					</tr>
				</table>
			</td>
			<td colspan="2" style="padding-left: 0px; padding-right: 0px; padding-top: 0px; padding-bottom: 0px;">
				<table border="0" width="100%" >
					<tr>
						<td width="25%"></td>
						<td width="25%" class="left-border-only">
							10000 /-
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
