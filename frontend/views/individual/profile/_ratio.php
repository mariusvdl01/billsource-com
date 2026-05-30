<h4>My Financial Position</h4>
<ul>
	<li><span>Assets: R</span><?= $ratio['assets'] ? number_format($ratio['assets'], 2) : '0.00' ?></li>
	<li><span>Liabilities: R</span><?= $ratio['liabilities'] ? number_format($ratio['liabilities'], 2) : '0.00'?></li>
	<?php 
		$networth = number_format($ratio['assets'] - $ratio['liabilities'], 2);
		$debt_ratio = 0;
		if( $ratio['assets'] != 0 )
			$debt_ratio = ($ratio['liabilities'] / $ratio['assets']) * 100;
		if( $debt_ratio < 0 )
			$debt_ratio = 0;
		else if( $debt_ratio > 100 )
			$debt_ratio = 100;
		
		$debt_ratio = number_format($debt_ratio, 2) . '%';
	?>
	<li><span>Networth: R</span><?= !empty($networth) ? $networth : '0.00' ?></li>
	<li><span>Debt Ratio: </span><?= $debt_ratio ?></li>
	<li><span>Surplus: R</span><?= !empty($data['surplus']) ? number_format($data['surplus'], 2) : '0.00' ?></li>
</ul>