<p><?=gettext("Register now to start a life of crime, killing, girls and money. Join thousands of others in the biggest and best free online gangster game on the web.")?></p>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/signup/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Email Address:")?></div>
			<div class="inputField"><input type="text" name="emailAddress" class="text" value="<?=set_value('emailAddress')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Confirm Email:")?></div>
			<div class="inputField"><input type="text" name="confirmEmail" class="text" value="<?=set_value('confirmEmail')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Country:")?></div>
			<div class="inputField">
				<select name="selectCountry" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Country")?> --</option>
					<?php foreach($preload['countries'] as $country) { echo "<option>".$country['value']."</option>"; } ?>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Date of birth:")?></div>
			<div class="inputField">
				<select name="selectDay" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Day")?> --</option>
					<?php for($i = 1; $i <= 31; $i++) { echo "<option>".$i."</option>"; } ?>
				</select>
				<select name="selectMonth" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Month")?> --</option>
					<?php
					$month_names = array("","January","February","March","April","May","June","July","August","September","October","November","December");
					for($c = 1; $c <= 12; $c++) { echo "<option value='".$c."'>".$month_names[$c]."</option>"; } ?>
				</select>
				<select name="selectYear" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Year")?> --</option>
					<?php for($c = date("Y",time()); $c >= date("Y",time())-70; $c--) { echo "<option>".$c."</option>"; } ?>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Gender:")?></div>
			<div class="inputField">
				<select name="genderType" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Gender")?> --</option>
					<option><?=gettext("Male")?></option>
					<option><?=gettext("Female")?></option>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Age:")?></div>
			<div class="inputField">
				<select name="ageAmount" class="selectBox">
					<option selected="selected" value="">-- <?=gettext("Select Age")?> --</option>
					<option value="13">< 13</option>
					<?php for($i = 14; $i < 65; $i++) {?>
						<option><?=$i?></option>
					<?php } ?>
					<option value="65">65 ></option>
				</select>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Referral Code:")?></div>
			<div class="inputField"><input type="text" name="referralCode" class="text" value="<?=set_value('referralCode')?>"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Anti Script:")?></div>
			<div class="antiScriptCode"></div>
			<div class="antiScriptField"><input type="text" name="antiScript" class="antiScript"></div>
		</div>
		<div class="fieldHolder">
			<div class="longLabelItem"><?=gettext("I have read and accept the terms of service")?></div>
			<div class="inputField"><input type="checkbox" name="termsOfService" class="checkBox" value="1"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Register")?>" name="submitButton">
		</div>
	</div>
</form>