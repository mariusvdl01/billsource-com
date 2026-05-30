$(document).ready(function() {
	
	function getSumOfAssetFields() {
		var home_1 = Number($('#individualfinancial-home_1').val());
		var home_2 = Number($('#individualfinancial-home_2').val());
		var home_3 = Number($('#individualfinancial-home_3').val());
		var vehicle_1 = Number($('#individualfinancial-vehicle_1').val());
		var vehicle_2 = Number($('#individualfinancial-vehicle_2').val());
		var craft = Number($('#individualfinancial-craft').val());
		var insurance = Number($('#individualfinancial-insurance').val());
		var investments = Number($('#individualfinancial-investments').val());
		var savings = Number($('#individualfinancial-savings').val());
		
		var sum_assets = home_1 + home_2 + home_3 + vehicle_1 + vehicle_2 
						+ craft + insurance + investments + savings;
		
		if(!$.isNumeric(sum_assets))
			return $('#individualfinancial-total_assets').val();
		return sum_assets;
	}
	
	function getSumOfLiabilityFields() {
		var bond_1 = Number($('#individualfinancial-bond_1').val());
		var bond_2 = Number($('#individualfinancial-bond_2').val());
		var bond_3 = Number($('#individualfinancial-bond_3').val());
		var car_loan_1 = Number($('#individualfinancial-car_loan_1').val());
		var car_loan_2 = Number($('#individualfinancial-car_loan_2').val());
		var debt = Number($('#individualfinancial-debt').val());
		var outstanding_bills = Number($('#individualfinancial-outstanding_bills').val());
		var craft_loan = Number($('#individualfinancial-craft_loan').val());
		
		var sum_liabilities = bond_1 + bond_2 + bond_3 + car_loan_1 + car_loan_2 
						+ debt + outstanding_bills + craft_loan;
		
		if(!$.isNumeric(sum_liabilities))
			return $('#individualfinancial-total_liabilities').val();
		return sum_liabilities;
	}
	
	function surplusIncome() {
		var income = $('#individualfinancial-net_income');
		var expenses = $('#individualfinancial-total_expenses');
		var surplus = $('#individualfinancial-surplus');
		
		
		income.change(function() {
			surplus.val(income.val() - expenses.val());
			surplus.attr('value', surplus.val());
		});
		
		expenses.change(function() {
			surplus.val(income.val() - expenses.val());
			surplus.attr('value', surplus.val());
		});
	}
	
	function totalAssets() {
		var total_assets = $('#individualfinancial-total_assets');
		
		$('#individualfinancial-home_1').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-home_2').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-home_3').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-vehicle_1').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-vehicle_2').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-craft').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-insurance').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-investments').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});
		
		$('#individualfinancial-savings').change(function() {
			total_assets.val(getSumOfAssetFields());
			total_assets.attr('value', total_assets.val());
		});	
	}
	
	function totalLiabilities() {
		var total_liabilities = $('#individualfinancial-total_liabilities');
		
		$('#individualfinancial-bond_1').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-bond_2').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-bond_3').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-car_loan_1').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-car_loan_2').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-debt').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-outstanding_bills').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});
		
		$('#individualfinancial-craft_loan').change(function() {
			total_liabilities.val(getSumOfLiabilityFields());
			total_liabilities.attr('value', total_liabilities.val());
		});		
	}

	surplusIncome();
	totalAssets();
	totalLiabilities();
});