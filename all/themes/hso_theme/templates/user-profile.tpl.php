<?php
	$account = $variables['elements']['#account'];
?>
<div class="user_profile clearfix"<?php print $attributes; ?>>
	<?php print render($user_profile['user_picture']); ?>
	<?php print render($user_profile['field_full_name']); ?>
	<?php print render($user_profile['field_position_function']); ?>
	<?php print render($user_profile['field_bio']); ?>
	<?php print render($user_profile['field_phone']); ?>
	<div class="field-email">
		<?php print invisimail_encode_email($account->mail, 'js_entities', array('link' => true)); ?>
	</div>
  <?php print render($user_profile); ?>
</div>
