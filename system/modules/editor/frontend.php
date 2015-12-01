<?php $path = path(); ?>
<h1><?php echo $data['title']; ?></h1>
<small><?php echo RelativeTime($data['date']); ?></small>
<hr />
<?php if(isset($data['video'])){ ?>
<link rel="stylesheet" href="<?php echo $path; ?>system/modules/video/style/functional.css">
<script src="<?php echo $path; ?>system/modules/video/js/flowplayer.min.js"></script>
<div class="no-brand flowplayer" data-ratio="0.4167">
  <video>
	 <source type="video/mp4" src="<?php echo $data['video']; ?>">
  </video>
</div>
<?php } ?>
<div class="content">
	<?php echo $data['content']; ?>
</div>
<style>
.fp-embed{display:none !important; opacity:0 !important;}
</style>
