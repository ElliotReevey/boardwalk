<h1><?=gettext("My Account")?></h1>

<?php if(isset($fail)) { echo errorbox($fail); } elseif(isset($success)) { successbox($success); } ?>

<h2><?=gettext("Change Password")?></h2>
<?=form_open_this("personal/myaccount/changepassword")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Current password:")?></div>
			<div class="inputField"><input type="password" name="currentPassword" class="text"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("New password:")?></div>
			<div class="inputField"><input type="password" name="newPassword" class="text"></div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Confirm new password:")?></div>
			<div class="inputField"><input type="password" name="confirmNewPassword" class="text"></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Change Password")?>" name="changePassword">
		</div>
	</div>
</form>

<h2><?=gettext("Add Friends")?></h2>
<?=form_open_this("personal/myaccount/addfriend")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Friends list:")?></div>
			<div class="inputField">
			<?php
				if($preload['friends']) {
					foreach($preload['friends'] as $friend) {
						echo "<a href='#".$friend['friendid']."'>".$friend['username']."</a> <a href='".$preload['site_url']."personal/myaccount/deletefriend/".$friend['friendid']."'>x</a>, ";
					}
				} else {
					echo gettext("You do not have any friends");
				}
			?>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Add friends list:")?></div>
			<div class="inputField"><input type="text" name="friendName" class="text" value="<?=set_value('friendName')?>"></div>
			<div class="inputField"><input type="submit" class="submitButton" value="<?=gettext("Add")?>" name="addFriend"></div>
		</div>
	</div>
</form>

<h2><?=gettext("Add Ignore")?></h2>
<?=form_open_this("personal/myaccount/addignore")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Ignore list:")?></div>
			<div class="inputField">
			<?php
				if($preload['ignoring']) {
					foreach($preload['ignoring'] as $ignore) {
						echo "<a href='#".$ignore['ignoreid']."'>".$ignore['username']."</a> <a href='".$preload['site_url']."personal/myaccount/deleteignore/".$ignore['ignoreid']."'>x</a>, ";
					}
				} else {
					echo gettext("You are not ignoring anyone");
				}
			?>
			</div>
		</div>
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Add to ignore list:")?></div>
			<div class="inputField"><input type="text" name="ignoreName" class="text" value="<?=set_value('ignoreName')?>"></div>
			<div class="inputField"><input type="submit" class="submitButton" value="<?=gettext("Add")?>" name="addIgnore"></div>
		</div>
	</div>
</form>

<h2><?=gettext("Take A Break")?></h2>
<?php if(!$preload['hibernation_auth']) {?>
<?=form_open_this("personal/myaccount/takeabreak")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Number of days:")?></div>
			<div class="inputField"><input type="text" name="hibernationDays" class="text" value="<?=set_value('hibernationDays')?>"></div>
			<div class="inputField"><input type="submit" class="submitButton" value="<?=gettext("Take a Break")?>" name="submitHibernation"></div>
		</div>
	</div>
</form>
<?php } else { ?>
<?=form_open_this("personal/myaccount/takeabreakconfirm")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Confirmation code:")?></div>
			<div class="inputField"><input type="text" name="confirmationCode" class="text" value="<?=set_value('confirmationCode')?>"></div>
			<div class="inputField"><input type="submit" class="submitButton" value="<?=gettext("Take a Break")?>" name="submitHibernationConfirm"></div>
		</div>
	</div>
</form>
<?php } ?>

<h2><?=gettext("Profile Quote")?></h2>
<?=form_open_this("personal/myaccount/profilequote")?>
	<div class="contentContainer">
		<div class="fieldHolder">
			<div class="labelItem"><?=gettext("Profile quote:")?></div>
			<div class="inputField"><textarea name="profileQuote" class="textArea"><?=$preload['profile']['quote']?></textarea></div>
		</div>
		<div class="inputButton">
			<input type="submit" class="submitButton" value="<?=gettext("Update Quote")?>" name="updateQuote">
		</div>
	</div>
</form>