<h1>Im the index page</h1>

Links: <a href="/boardwalk/home/signup">Signup</a> | <a href="/boardwalk/home/contact">Contact</a> | <a href="/boardwalk/home/forgottenpassword">Forgotten Password</a> <?php if(isset($_SESSION['id'])) { ?> | <a href="/boardwalk/logout">Logout</a> <?php } ?>

<?php if(isset($fail)) { echo errorbox($fail); } ?>

<?=form_open_this("home/index/submit")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Email Address:")?></div>
			<div class="inputField"><input type="text" name="emailAddress" class="text" value=""></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Password:")?></div>
			<div class="inputField"><input type="password" name="password" class="text"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Login")?>" name="submitButton">
		</div>
	</div>
</form>