<!-- Main content -->
<section class="content">

	<div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?=staft_url($user['photo']);?>" alt="User profile picture">
              <h3 class="profile-username text-center"><?=$user['nama_lengkap'];?></h3>
              <p class="text-muted text-center">&nbsp;</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Today Activity</b> <a class="pull-right"><?=$user['jml_activity'];?></a>
                </li>
                <li class="list-group-item">
                  <b>All Activity</b> <a class="pull-right"><?=$user['jml_all_activity'];?></a>
                </li>
                <li class="list-group-item">
                  <b>Feedback</b> <a class="pull-right"><?=$user['jml_feedback'];?></a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Today Activity</a></li>
              <li><a href="#timeline" data-toggle="tab">Feedback</a></li>
              <li><a href="#settings" data-toggle="tab" class="hide">Send Message</a></li>
            </ul>
            <div class="tab-content">
				<div class="active tab-pane" id="activity">
					<table class="table table-borderless" id="tbl_activity">
						<tbody>
						<?php
						foreach($activity as $row){ 
							?>
							<tr><td><small><?=time_ago($row['tgl']);?> <a href="<?=base_url('dashboard/detail-faq/'.$row['id']);?>"> <?=$row['title'];?></a><br/></td></tr>
						<?php } ?>
						</tbody>
					</table>
                </div>
				<div class="tab-pane" id="timeline">
					<table class="table table-borderless" id="tbl_activity">
						<tbody>
						<?php
						foreach($feedback as $row){ 
							?>
							<tr><td><small><?=time_ago($row['tgl']);?> <a href="<?=base_url('dashboard/detail-faq/'.$row['id']);?>"> <?=$row['title'];?></a><br/></td></tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

              <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
</section><!-- /.content -->