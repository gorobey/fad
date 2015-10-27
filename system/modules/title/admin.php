<?php $inc = hash_equals($_CONFIG['tabasco'], crypt($_CONFIG['pepper'], $_CONFIG['tabasco'])) or die(); ?>
<div class="form-group">			
	<input type="text" name="title" placeholder="<?php echo _('Title');?>" class="form-control">
</div>