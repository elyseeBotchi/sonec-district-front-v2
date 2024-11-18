@extends('layout.adminApp')

@section('content')
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Roles {{ $role->name ?? '' }}</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item ">Configuration</li>
                    <li class="breadcrumb-item"><a href="{{ route('panel.autorisations.roles.index') }}">Roles {{ $role->name ?? '' }} </a> </li>
                    <li class="breadcrumb-item active">permissions</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row" id="container">
            <div class="col-md-12">
                <div class="card table-card">
                  <div class="card-header">
                    Veuillez definir les permissions pour le rôle : <strong>{{ $role->name }}</strong>
                  </div>
                    <div class="card-body">
                      <div class="table-responsive">

                        <table class="table bg-light">
                            <tr class="bg-primary text-uppercase">
                                <td class="text-white">Modules</td>
                                <td class="text-white">Permissions</td>
                            </tr>
                            <tbody class="render-html">
                              @forelse ($modules as $module)
                                <tr>
                                  <td style="vertical-align: center;">{{ $module->name }}</td>
                                  <td>
                                    <div class="list-group">
                                      <div class="row">
                                        @forelse ($module->permissions as $permission)
                                          <div class="col-md-12">
                                              <label class="list-group-item">
                                                  &nbsp; &nbsp;
                                                <input class="form-check-input me-1" type="checkbox" @if($permission->status) checked @endif name="permission" value="{{ $permission->role_permission_uuid }}"><span class="permission-load"></span> {{ $permission->name }}
                                              </label>

                                          </div>
                                          {{-- <div class="col-md-4">
                                              <input type="text" class="form-control" value="{{ $permission->slug }}" onclick="this.select();">
                                          </div> --}}
                                        @empty
                                        @endforelse
                                      </div>
                                    </div>
                                  </td>
                                </tr>

                              @empty

                              @endforelse
                            </tbody>
                        </table>
                      </div>
                    </div>
                </div>
            </div>
            <!-- [ sample-page ] end -->
        </div>


@endsection

@push('footer-script')
    <script src="{{ asset('backoffice/js/roles.js') }}"></script>
@endpush
