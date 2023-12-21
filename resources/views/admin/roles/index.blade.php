@extends('layouts.app')
@section('content')
<div class="row">
    {{-- <div class="col-12">
        <h3 class="no-margin text-semibold text-center">Occupied Disk Space - Techsolutionstuff</h3>
        <div class="col-sm-12 col-md-4 col-md-offset-4">
            <div class="progress progress-micro mb-10">
                <div class="progress-bar bg-indigo-400" style="width: {{$diskuse}}">
                    <span class="sr-only">{{$diskuse}}</span>
                </div>
            </div>
            <span class="pull-right">{{round($diskusedize,2)}} GB /
                {{round($disktotalsize,2)}} GB ({{$diskuse}})</span>
        </div>
    </div> --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body p-2">
                <table id="roles_table" class="table-bordered table-striped table-sm table text-center">
                    <thead class="bg-gradient-navy text-white">
                        <tr>
                            <th>Sr #</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add_edit_role_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-gradient-navy py-2">
                <h5 class="h5 modal-title font-weight-bold" id="add_edit_modal_title"></h5>
                <button type="button" class="close modal_close">
                    <i class="bg-gradient-danger fa fa-times fa-xs rounded px-2 py-1"></i>
                </button>
            </div>
            <div class="modal-body pb-0">
                <form class="form" role="form" id="add_update_role_form">
                    @csrf
                    <input type="hidden" name="role_id" id="role_id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" id="name">
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="type">Role Type</label>
                        <br>
                        <div class="d-flex justify-content-between" id="type">
                            <div class="d-inline icheck-navy">
                                <input type="radio" name="type" id="sub_admin" value="sub_admin">
                                <label for="sub_admin">Sub Admin</label>
                            </div>
                            <div class="d-inline icheck-navy ml-auto mr-auto">
                                <input type="radio" name="type" id="moderator" value="moderator">
                                <label for="moderator">Moderator</label>
                            </div>
                        </div>
                        <span class="invalid-feedback" role="alert">
                            <strong></strong>
                        </span>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between py-1">
                <button type="button" class="bg-gradient-gray-dark btn btn-sm modal_close">Close</button>
                <button type="submit" name="submit" class="bg-gradient-navy btn btn-sm" id="create_update_btn">
                    <span class="spinner-border spinner-border-sm d-none" id="add_btn_spinner"></span>
                    <span id="add_btn"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@include('partials._delete_restore_modal')
@endsection
@push('javascript')
<script>
    $(document).ready(function() {

            var add_edit_form = $('#add_update_role_form');
            var delete_restore_modal = $('#delete_restore_modal');
            var table = $("#roles_table");

            fetchAllRoles();

            $('#add_role').on('click', function() {
                // RELACING MODAL TITLE & BUTTON TEXT ON CREATE
                $('#add_edit_modal_title').html('Add New Role');
                $('#create_update_btn').find('#add_btn').html('Create');
                add_edit_form.find('#sub_admin').val('sub_admin');
                add_edit_form.find('#moderator').val('moderator');
            });

            $(document).on('click', '.edit_role', function(e) {
                e.preventDefault();
                var edit_btn = $(this);

                add_edit_form.find('#role_id').val(edit_btn.data('id'));
                add_edit_form.find('#name').val(edit_btn.data('name'));
                if (edit_btn.data('type') == 'sub_admin') {
                    add_edit_form.find('#sub_admin').val('sub_admin').attr('checked', '');
                    add_edit_form.find('#moderator').val('moderator');
                } else {
                    add_edit_form.find('#sub_admin').val('sub_admin');
                    add_edit_form.find('#moderator').val('moderator').attr('checked', '');
                }
                $('#add_edit_modal_title').html('Edit Role');
                $('#create_update_btn').find('#add_btn').html('Update');
            });

            $(document).on('click', '#create_update_btn', function() {
                var add_btn = $(this);
                add_btn.find('#add_btn_spinner').removeClass('d-none');
                add_btn.attr("disabled", true);
                let type = 'POST';
                let url = '';
                let role_id = $('#role_id').val();
                let data = new FormData(add_edit_form[0]);
                if (add_btn.find('#add_btn').text() == 'Create') {
                    url = '{{ route('role.store') }}';
                } else {
                    url = '{{ route('role.update') }}';
                    data.append('_method', 'PATCH');
                }
                SendAjaxRequestToServer(type, url, data, '', createUpdateRoleResponse);

                function createUpdateRoleResponse(response) {
                    if (response.status != 200) {
                        add_edit_form.find('#toggle_btn').removeClass('is-invalid');
                        add_edit_form.find('span').removeClass('d-block').html('');
                        $('#create_update_btn').removeAttr("disabled");
                        $('#create_update_btn').find('#add_btn_spinner').addClass('d-none');
                        $.each(response.responseJSON.errors, function(key, value) {
                            add_edit_form.find('#' + key).addClass('is-invalid');
                            add_edit_form.find('#' + key).siblings('span').addClass('d-block').html(
                                value[
                                    0]);
                            if (key == 'type') {
                                add_edit_form.find('#type').next('span').addClass(
                                        'd-block')
                                    .html(value[0]);
                            }
                        });
                    } else {
                        table.DataTable().ajax.reload();
                        modalFormControl();

                        $(function() {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-right',
                                iconColor: 'white',
                                padding: '1em',
                                customClass: {
                                    popup: 'colored-toast',
                                    title: 'swal2-styled',
                                },
                                showConfirmButton: false,
                                timer: 4000,
                                timerProgressBar: true
                            });

                            Toast.fire({
                                icon: response.state,
                                title: response.message,
                            })
                        });
                    }
                }
            });

            $('.modal_close').click(modalFormControl);

            function modalFormControl() {
                add_edit_form.find('.is-invalid').removeClass("is-invalid");
                add_edit_form.find('.invalid-feedback').text('');
                add_edit_form.find("input").removeAttr('checked value');
                add_edit_form.trigger('reset');
                add_edit_form.find('label').removeClass('active');
                $('#create_update_btn').removeAttr("disabled");
                $('#create_update_btn').find('#add_btn_spinner').addClass('d-none');
                add_edit_form.parents('.modal').modal('hide');
            }

            function fetchAllRoles() {
                table.DataTable({
                    "pagingType": 'numbers',
                    // "ordering": true,
                    "orderable": true,
                    'pageLength': 10,
                    "lengthMenu": [
                        [10, 15, 20, 25, 50, -1],
                        [10, 15, 20, 25, 50, 'All'],
                    ],
                    "responsive": true,
                    "lengthChange": true,
                    "autoWidth": true,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('role.getAllRolesData') }}",
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'name',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'type',
                            name: 'type',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'updated_at',
                            name: 'updated_at',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        },
                    ]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }

            $(document).on('click', '#delete_restore_modal_btn', function() {
                // var action_btn = $(this);
                var confirm_btn = $(this);
                confirm_btn.find('#delete_btn_spinner').removeClass('d-none');
                confirm_btn.addClass('disabled');
                let dalate_restore_form = $('#delete_restore_form');
                var url = "{{ route('role.destroyOrRestore') }}";
                var data = new FormData(dalate_restore_form[0]);

                SendAjaxRequestToServer('POST', url, data, 'json', deleteRestoreResponse);
            });

            function deleteRestoreResponse(response) {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    iconColor: 'white',
                    padding: '1em',
                    customClass: {
                        popup: 'colored-toast',
                        title: 'swal2-styled',
                    },
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });


                if (response.status == 200) {
                    table.DataTable().ajax.reload();
                    deleteRestoreModalReset();

                    Toast.fire({
                        icon: response.state,
                        title: response.message,
                    });
                } else {
                    Toast.fire({
                        icon: response.state,
                        title: response.message,
                    });
                }
            }

            $('.delete_restore_close').click(deleteRestoreModalReset);

            function deleteRestoreModalReset() {
                delete_restore_modal.find('#delete_restore_form #role_id').removeAttr('value');
                delete_restore_modal.find('#delete_restore_modal_btn').removeAttr(
                    "data-action");
                delete_restore_modal.find('#delete_restore_modal_heading').removeClass(
                    'bg-gradient-success, bg-gradient-danger');
                delete_restore_modal.find('#delete_restore_modal_btn').removeClass(
                    'bg-gradient-success, bg-gradient-danger disabled');
                $('#delete_restore_modal_btn').find('#delete_btn_spinner').addClass('d-none');
                delete_restore_modal.modal('hide');

            }
        });
</script>
@endpush