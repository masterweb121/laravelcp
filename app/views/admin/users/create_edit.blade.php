@extends('admin.layouts.modal')

@section('content')
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab-general" data-toggle="tab">{{{ Lang::get('core.general') }}}</a></li>
		<li><a href="#tab-profile" data-toggle="tab">{{{ Lang::get('core.profile') }}}</a></li>
		@if ($mode != 'create')
			<li><a href="#tab-logs" data-toggle="tab">{{{ Lang::get('core.activity') }}}</a></li>
			<li><a href="#tab-email" data-toggle="tab">{{{ Lang::get('core.emails') }}}</a></li>
			<li><a href="#tab-details" data-toggle="tab">{{{ Lang::get('core.details') }}}</a></li>
		@endif
	</ul>

	@if ($message = Session::get('success'))
	<script type="text/javascript">
		if($('#users').html()){
			var oTable = $('#users').dataTable();
			oTable.fnReloadAjax();
		}
	</script>
	@endif

	@if (isset($user))
		{{ Form::open(array('method' => 'put','url' => URL::to('admin/users/' . $user->id . '/edit'), 'class' => 'form-horizontal form-ajax')) }}
	@else
		{{ Form::open(array('class' => 'form-horizontal form-ajax')) }}
	@endif

		<div class="tab-content">
			@if ($mode != 'create')
				<div class="tab-pane" id="tab-details">
					<div class="list-group">
					  <a href="#" class="list-group-item" data-toggle="tooltip" data-placement="bottom" title="{{{ $user->created_at }}}">
						<h4 class="list-group-item-heading">{{{ Lang::get('core.created') }}}</h4>
						<p class="list-group-item-text">{{{ Carbon::parse($user->created_at)->diffForHumans() }}}</p>
					  </a>
					  <a href="#" class="list-group-item" data-toggle="tooltip" data-placement="bottom" title="{{{ $user->last_login }}}">
						<h4 class="list-group-item-heading">{{{ Lang::get('core.lastlogin') }}}</h4>
						<p class="list-group-item-text">{{{ Carbon::parse($user->last_login)->diffForHumans() }}}</p>
					  </a>
					  <a href="#" class="list-group-item" data-toggle="tooltip" data-placement="bottom" title="{{{ $user->last_activity }}}">
						<h4 class="list-group-item-heading">{{{ Lang::get('core.lastactivity') }}}</h4>
						<p class="list-group-item-text">{{{ Carbon::parse($user->last_activity)->diffForHumans() }}}</p>
					  </a>
					</div>
				</div>
			@endif

			<div class="tab-pane active" id="tab-general">


				<div class="form-group">
					<label class="col-md-2 control-label" for="displayname">{{{ Lang::get('core.fullname') }}}</label>
					<div class="col-md-10">
						<input class="form-control" type="text" name="displayname" id="displayname" value="{{{ Input::old('displayname', isset($user) ? $user->displayname : null) }}}" />
					</div>
				</div>

				<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="email">{{{ Lang::get('button.email') }}}</label>
					<div class="col-md-10">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="fa fa-envelope"></span>
                                </span>

						<input class="form-control" type="email" name="email" id="email" value="{{{ Input::old('email', isset($user) ? $user->email : null) }}}" /></div>
						{{ $errors->first('email', '<span class="help-inline">:message</span>') }}
					</div>
				</div>

				
				<div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password">{{{ Lang::get('core.password') }}}</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password" id="password" value="" />
						{{ $errors->first('password', '<span class="help-inline">:message</span>') }}
					</div>
				</div>

				
				<div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="password_confirmation">{{{ Lang::get('core.password') }}} {{{ Lang::get('core.confirm') }}}</label>
					<div class="col-md-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="" />
						{{ $errors->first('password_confirmation', '<span class="help-inline">:message</span>') }}
					</div>
				</div>

				
				<div class="form-group {{{ $errors->has('activated') || $errors->has('confirm') ? 'error' : '' }}}">
					<label class="col-md-2 control-label" for="confirm">{{{ Lang::get('core.active') }}}</label>
					<div class="col-md-6">
						@if ($mode == 'create')
							<select class="form-control" name="confirm" id="confirm">
								<option value="1"{{{ (Input::old('confirm', 0) === 1 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0"{{{ (Input::old('confirm', 0) === 0 ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
						@else
							<select class="form-control" {{{ ($user->id === Confide::user()->id ? ' disabled="disabled"' : '') }}} name="confirm" id="confirm">
								<option value="1"{{{ ($user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.yes') }}}</option>
								<option value="0"{{{ ( ! $user->confirmed ? ' selected="selected"' : '') }}}>{{{ Lang::get('general.no') }}}</option>
							</select>
						@endif
						{{ $errors->first('confirm', '<span class="help-inline">:message</span>') }}
					</div>
				</div>

				<div class="form-group {{{ $errors->has('roles') ? 'error' : '' }}}">
	                <label class="col-md-2 control-label" for="roles">{{{ Lang::get('core.roles') }}}</label>
	                <div class="col-md-6">
		                <select class="form-control" name="roles[]" id="roles[]" multiple>
		                        @foreach ($roles as $role)
									@if ($mode == 'create')
		                        		<option value="{{{ $role->id }}}"{{{ ( in_array($role->id, $selectedRoles) ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
		                        	@else
										<option value="{{{ $role->id }}}"{{{ ( array_search($role->id, $user->currentRoleIds()) !== false && array_search($role->id, $user->currentRoleIds()) >= 0 ? ' selected="selected"' : '') }}}>{{{ $role->name }}}</option>
									@endif
		                        @endforeach
						</select>

						<span class="help-block">
							{{{ Lang::get('admin/users/messages.select_group') }}}
						</span>
	            	</div>
				</div>

				<div class="modal-footer">
					{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
					{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-default')); }} 
					{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-success')); }} 
				</div>
			</div>

			<div class="tab-pane" id="tab-profile">
				<ul class="nav nav-pills">
					@if ($mode != 'create')
						<li ><a href="#tab-create" data-toggle="tab"><span class="fa fa-plus-square"></span>  {{{ Lang::get('button.create') }}}</a></li>
						@foreach($profiles as $index=>$pro)
							<li @if ($index == 0)class="active"@endif><a href="#tab-{{{$pro->id}}}" data-toggle="tab" id="tab-c{{{$pro->id}}}">@if ($pro->title){{$pro->title}}@elseif($index == 0)Default @else#{{{$index}}}@endif</a></li>
						@endforeach
					@endif
				</ul>
				<div class="tab-content">
					<div class="tab-pane @if ($mode == 'create')active@endif" id="tab-create">
						@include('admin/users/profiles')
					</div>

				@if ($mode != 'create')
					@foreach($profiles as $index=>$profile)
						<div class="tab-pane @if (isset($index) && $index == 0)active@endif" id="tab-@if(isset($profile)){{{$profile->id}}}@endif">
							@include('admin/users/profiles')
						</div>
					@endforeach
				@endif
				</div>
				<div class="modal-footer">
					{{ Form::reset(Lang::get('button.cancel'), array('class' => 'btn btn-danger', 'onclick'=>"$('#site-modal').modal('hide')")); }} 
					{{ Form::reset(Lang::get('button.reset'), array('class' => 'btn btn-default')); }} 
					{{ Form::submit(Lang::get('button.save'), array('class' => 'btn btn-success')); }} 
					@if(isset($profile))
						<a data-method="delete" href="{{{ URL::to('admin/users/' . $user->id . '/profile/'.$profile->id ) }}} " class="ajax-alert-confirm btn btn-danger">Delete {{{ $profile->title }}} </a>
					@endif
				</div>
			</div>

			<div class="tab-pane" id="tab-logs">
				@include('admin/dt-loading')

				<div  id="activitylog-container" class="dt-wrapper">
					<table id="activitylog" class="table-responsive table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>{{{ Lang::get('admin/users/table.description') }}}</th>
								<th>{{{ Lang::get('admin/users/table.details') }}}</th>
								<th>{{{ Lang::get('admin/users/table.ip_address') }}}</th>
								<th>{{{ Lang::get('admin/users/table.updated_at') }}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="tab-pane" id="tab-email">
				@include('admin/dt-loading')

				<div id="emaillog-container" class="dt-wrapper">
					<table id="emaillog" class="table-responsive table table-striped table-hover table-bordered">
						<thead>
							<tr>
								<th></th>
								<th>{{{ Lang::get('core.subject') }}}</th>
								<th>{{{ Lang::get('core.body') }}}</th>
								<th>{{{ Lang::get('admin/users/table.ip_address') }}}</th>
								<th>{{{ Lang::get('admin/users/table.updated_at') }}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
	{{ Form::close(); }}
@stop


@section('scripts')
@if (isset($user))
<script type="text/javascript">
	$('a').tooltip();
	dtLoad('#activitylog', "{{URL::to('admin/users/' . $user->id . '/activity') }}", 'td:eq(2), th:eq(2)', 'td:eq(1), th:eq(1)');
	dtLoad('#emaillog', "{{URL::to('admin/users/' . $user->id . '/emails') }}", 'td:eq(2), th:eq(2)', 'td:eq(1), th:eq(1)');
</script>
@endif
@stop