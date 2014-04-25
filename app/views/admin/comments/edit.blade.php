@extends('admin/layouts/modal')

@section('content')
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
	</ul>

	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if(parent.$('#blogs').html()){
			var oTable = parent.$('#comments').dataTable();
			oTable.fnReloadAjax();
		}
	</script>
	@endif

	{{ Form::open(array('method' => 'put','class' => 'form-horizontal form-ajax')) }}
		<div class="tab-content">
			<div class="tab-pane active" id="tab-general">
				<div class="form-group {{{ $errors->has('content') ? 'error' : '' }}}">
					<div class="col-md-12">
                        <label class="control-label" for="content">{{{ Lang::get('core.content') }}}</label>
						<textarea class="form-control full-width wysihtml5" name="content" value="content" rows="10">{{{ Input::old('content', $comment->content) }}}</textarea>
						{{ $errors->first('content', '<span class="help-inline">:message</span>') }}
					</div>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
			{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-default')); }} 
			{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-success')); }} 
		</div>
	{{ Form::close(); }}
@stop