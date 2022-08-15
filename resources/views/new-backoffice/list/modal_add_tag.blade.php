<div class="modal fade" id="modalProductTag" tabindex="-1" role="dialog" aria-labelledby="modalProductTag" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      {!! Form::open(['url'=>route('admin:master.product-tag.save'),'style'=>'width:100%']) !!}
        <div class="modal-body">
          <div class="form-group form-group-float">
            {!! Form::label('name_ind','Name (Indo)') !!}
            {!! Form::text('name_ind',null,['class'=>'form-control','id'=>'name_ind','autocomplete'=>'off']) !!}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>