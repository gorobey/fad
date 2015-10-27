<?php $inc = hash_equals($_CONFIG['tabasco'], crypt($_CONFIG['pepper'], $_CONFIG['tabasco'])) or die(); ?>


<i class="fa fa-video-camera fa-2x"></i>