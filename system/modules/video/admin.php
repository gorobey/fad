<?php if(!isset($status)){auth_check_point();} ?>
        <div class="col-xs-12">
			<hr />
            <div class="input-group">
                <span class="input-group-btn">
                    <span data-remote="../system/modules/video/upload_video.php" data-toggle="modal" data-target="#custom-field" class="btn btn-primary btn-file">
                        Browse&hellip;
                    </span>
                </span>
                <input value="<?php if(isset($data['video'])){echo $data['video'];}?>" type="text" class="form-control" id="filevideo" name="content[video]" placeholder="select video" readonly>
            </div>
            	<hr />
          </div>
<style>
.btn-file {
  position: relative;
  overflow: hidden;
}
.btn-file input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  min-width: 100%;
  min-height: 100%;
  font-size: 100px;
  text-align: right;
  filter: alpha(opacity=0);
  opacity: 0;
  background: red;
  cursor: inherit;
  display: block;
}
input[readonly] {
  background-color: white !important;
  cursor: text !important;
}
</style>
