<div class="modal fade add-association" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      {!! Form::open(['url'=>route('admin:providers.save-association',['id'=>$company->id_company]),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Asosiasi</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group form-group-float">
            {!! Form::label('association_name','Nama Membership', ['class' => 'form-group-float-label animate is-visible']) !!}
            {!! Form::select('association_id',\App\Models\Association::where('status',1)->whereDoesntHave('companies', function ($c) use($company){
                $c->where('tbl_company.id_company',$company->id_company);
            })->pluck('association_name','id'),null,['class'=>' form-control m-input','id'=>'association_name','autocomplete'=>'off']) !!}
          </div>

          <div class="form-group form-group-float">
            {!! Form::label('membership_id','Membership ID', ['class' => 'form-group-float-label animate is-visible']) !!}
            {!! Form::text('membership_id',null,['class'=>'form-control m-input','id'=>'association_name','autocomplete'=>'off']) !!}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>

<div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        {!! Form::open(['url'=>route('admin:providers.delete-association',['id'=>$company->id_company]),'style'=>'width:100%','id'=>'form-add','files'=>true]) !!}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Hapus dari asosiasi: <span
                            class="name"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group m-form__group">
                    {!! Form::hidden('association_id',null,['class'=>'form-control m-input']) !!}
                    Are You Sure?
                </div>

            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button class="btn btn-primary btn-do-submit" type="submit" id="btn-do-delete">Simpan</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
