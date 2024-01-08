<form id="order_payment" method="post" accept-charset="utf-8">
	
		<h2>Simple Moneris Purchase</h2>
		
		<label for="cc_number">Credit Card Number</label>
		<input type="text" class="textbox" name="cc_number" value="4242424242424242" id="cc_number">
		
		<label for="cvd">CVD</label>
		<input type="text" class="textbox" name="cvd" value="123" id="cvd">
		
		<label for="amount">Amount</label>
		<input type="text" class="textbox" name="amount" value="20.00" id="amount">
		
		<label>Expires</label>
		<select name="expiry_month" id="expiry_month">
			<option value="01">01</option>
			<option value="02">02</option>
			<option value="03">03</option>
			<option value="04">04</option>
			<option value="05">05</option>
			<option value="06">06</option>
			<option value="07">07</option>
			<option value="08">08</option>
			<option value="09">09</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
		</select>
		
		<select name="expiry_year" id="expiry_year">
			<?php 
			$year = (int) date('Y');
			for ($i = $year; $i < $year + 20; $i++ ): ?>
				<option value="<?= substr((string) $i, -2); ?>"><?= $i; ?></option>
			<?php endfor; ?>
		</select>
		
		<button type="submit">Complete Purchase</button>
	
</form>