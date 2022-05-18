<div class="row">
	<div class="col-xl-12">
		<div class="card">
            <div class="card-header header-elements-sm-inline">
				<h6 class="card-title">Filter</h6>
				<div class="header-elements">
					<div class="list-icons">
						<a class="list-icons-item" data-action="collapse"></a>
					</div>
				</div>
			</div>
			<div class="card-body">
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right">Tahun</label>
                    <div class="col-lg-10">
						<?=form_dropdown('periode_no', $periode, _TAHUN_ID_,' class="form-control select" id="periode_no" style="width:100%;"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right">Periode</label>
                    <div class="col-lg-10">
						<?=form_dropdown('term_no', $term, _TERM_ID_,' class="form-control select" id="term_no" style="width:100%;"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-form-label col-lg-2 text-right">Deparatment</label>
                    <div class="col-lg-10">
						<?=form_dropdown('divisi', $divisi, '',' class="form-control select" id="divisi" style="width:100%;"');?>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <span class="btn btn-primary pointer pull-right" id="proses"> Proses </span>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header header-elements-sm-inline">
                <h6 class="card-title">Kepatuhan Laporan</h6>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                    </div>
                </div>
            </div>
            <div class="card-body" id="content_detail">
				<?=$detail;?>
            </div>
        </div>
    </div>
</div>
