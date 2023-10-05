@extends('layouts.app')
@section('content')
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body p-2">
                    <table id="category_table" class="table-bordered table-striped table-sm table text-center">
                        <thead class="bg-gradient-navy text-white">
                            <tr>
                                <th>Sr #</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Added By</th>
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
    <div class="modal fade" id="add_edit_category_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-navy py-2">
                    <h5 class="modal-title font-weight-bold" id="add_edit_modal_title"></h5>
                    <button type="button" class="close modal_close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body pb-0">
                    <form class="form" role="form" id="add_update_category_form">
                        @csrf
                        <input type="hidden" name="category_id" id="category_id">
                        <div class="form-group">
                            <label for="title">Category Title</label>
                            <input type="text" name="title" class="form-control" id="title">
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                        <div class="form-group">
                            <label for="type">Category Type</label>
                            <br>
                            <div class="d-flex justify-content-between" id="type">
                                <div class="d-inline icheck-navy">
                                    <input type="radio" name="type" id="blog" value="blog">
                                    <label for="blog">Blog</label>
                                </div>
                                <div class="d-inline icheck-navy ml-auto mr-auto">
                                    <input type="radio" name="type" id="job" value="job">
                                    <label for="job">Job</label>
                                </div>
                            </div>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div>
                        {{-- <div class="form-group mb-0">
                            <label for="title">Category Type</label><br>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="toggle_btn">
                                <label class="btn btn-outline-primary btn-sm" id="blog">
                                    <input type="radio" name="type" autocomplete="off" value="{{ old('blog') }}">
                                    Blog
                                </label>
                                <label class="btn btn-outline-primary btn-sm" id="job">
                                    <input type="radio" name="type" autocomplete="off" value="{{ old('job') }}">
                                    Job
                                </label>
                            </div>
                            <span class="invalid-feedback" role="alert">
                                <strong></strong>
                            </span>
                        </div> --}}
                    </form>
                </div>
                <div class="modal-footer justify-content-between py-1">
                    <button type="button" class="btn btn-outline-secondary btn-sm modal_close">Close</button>
                    <button type="submit" name="submit" class="btn btn-outline-primary btn-sm"
                        id="create_update_btn"></button>
                </div>
            </div>
        </div>
    </div>

    @include('partials._delete_restore_modal')
@endsection
@push('javascript')
    <script>
        $(document).ready(function() {
            fetchAllCategories();

            var add_edit_form = $('#add_update_category_form');
            var delete_restore_modal = $('#delete_restore_modal');

            $('#add_category').on('click', function() {
                // RELACING MODAL TITLE & BUTTON TEXT ON CREATE
                $('#add_edit_modal_title').html('Add New Category');
                $('#create_update_btn').html('Create');
            });

            $(document).on('click', '.edit_category', function(e) {
                e.preventDefault();
                var edit_btn = $(this);
                add_edit_form.find('#category_id').val(edit_btn.data('id'));
                add_edit_form.find('#title').val(edit_btn.data('title'));
                if (edit_btn.data('type') == 'blog') {
                    add_edit_form.find('#blog').val(edit_btn.data('type')).attr('checked', '');
                } else {
                    add_edit_form.find('#job').val(edit_btn.data('type')).attr('checked', '');
                }
                $('#add_edit_modal_title').html('Edit Category');
                $('#create_update_btn').html('Update');
            });

            $(document).on('click', '#create_update_btn', function() {

                let type = 'POST';
                let url = '';
                let category_id = $('#category_id').val();
                let data = new FormData(add_edit_form[0]);
                if ($(this).text() == 'Create') {
                    url = '{{ route('category.store') }}';
                } else {
                    url = '{{ route('category.update') }}';
                    data.append('_method', 'PATCH');
                }
                SendAjaxRequestToServer(type, url, data, '', createUpdateCategoryResponse);

                function createUpdateCategoryResponse(response) {
                    if (response.status != 200) {
                        add_edit_form.find('#toggle_btn').removeClass('is-invalid');
                        add_edit_form.find('span').removeClass('d-block').html('');
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
                        $(function() {
                            var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });

                            Toast.fire({
                                icon: response.state,
                                title: response.message,
                                // background: 'gray',
                            })

                            modalFormControl();
                            fetchAllCategories();
                        });
                    }
                }
            });

            $('.modal_close').click(modalFormControl);

            function modalFormControl() {
                add_edit_form.find('.is-invalid').removeClass("is-invalid");
                add_edit_form.find('.invalid-feedback').text('');
                add_edit_form.find("input").removeAttr('checked');
                add_edit_form.find("input").removeAttr('value');
                add_edit_form.trigger("reset");
                add_edit_form.find('label').removeClass('active');
                add_edit_form.parents('.modal').modal('hide');
            }


            function fetchAllCategories() {
                $("#category_table").DataTable({
                    "pagingType": 'numbers',
                    "ordering": true,
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
                    "destroy": true,
                    "ajax": "{{ route('category.getAllCategoryData') }}",
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'category_title',
                            name: 'category_title',
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
                            data: 'user',
                            name: 'user',
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
                // $('#example2').DataTable({
                //     "paging": true,
                //     "lengthChange": false,
                //     "searching": false,
                //     "ordering": true,
                //     "info": true,
                //     "autoWidth": false,
                //     "responsive": true,
                // });
            }
            $(document).on('click', '.delete_restore_category', function() {
                var action_btn = $(this);
                var delete_restore_modal_heading = delete_restore_modal.find(
                    '#delete_restore_modal_heading');
                var delete_restore_modal_btn = delete_restore_modal.find('#delete_restore_modal_btn');
                var delete_restore_modal_body = delete_restore_modal.find('#delete_restore_modal_body');

                if (action_btn.data('action') == 'delete') {
                    delete_restore_modal_heading.removeClass('bg-gradient-success').addClass(
                        'bg-gradient-danger');
                    delete_restore_modal_heading.children('h5').html('Delete ?');
                    delete_restore_modal_body.children('h5').html('Are You Sure, You Want To Delete ?');
                    delete_restore_modal_btn.removeClass('bg-gradient-success').addClass(
                        'bg-gradient-danger').text('Delete');
                    delete_restore_modal_btn.attr('data-action', action_btn.data('action'));
                } else {
                    delete_restore_modal_heading.removeClass('bg-gradient-danger').addClass(
                        'bg-gradient-success');
                    delete_restore_modal_heading.children('h5').html('Restore ?');
                    delete_restore_modal_body.children('h5').html('Are You Sure, You Want To Restore ?');
                    delete_restore_modal_btn.removeClass('bg-gradient-danger').addClass(
                        'bg-gradient-success').text('Restore');
                    delete_restore_modal_btn.attr('data-action', action_btn.data('action'));
                }

                delete_restore_modal.find('#delete_restore_form #id').val(action_btn.data('id'));
            });

            $(document).on('click', '#delete_restore_modal_btn', function(e) {
                let dalate_restore_form = $('#delete_restore_form');
                var action_btn = $(this);
                var url = "{{ route('category.destroyOrRestore') }}";
                var data = new FormData(dalate_restore_form[0]);

                SendAjaxRequestToServer('POST', url, data, 'json', deleteRestoreResponse);
            });

            function deleteRestoreResponse(response) {
                if (response.status == 200) {
                    $(function() {
                        var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });

                        Toast.fire({
                            icon: response.state,
                            title: response.message,
                            // background: 'gray',
                        })

                        fetchAllCategories();
                        deleteRestoreModalReset();
                    });
                }
            }

            $('.delete_restore_close').click(deleteRestoreModalReset);

            function deleteRestoreModalReset() {
                delete_restore_modal.find('#delete_restore_form #category_id').removeAttr('value');
                delete_restore_modal.find('#delete_restore_modal_btn').removeAttr(
                    "data-action");
                delete_restore_modal.find('#delete_restore_modal_heading').removeClass(
                    'bg-gradient-success, bg-gradient-danger');
                delete_restore_modal.find('#delete_restore_modal_btn').removeClass(
                    'bg-gradient-success, bg-gradient-danger');
                delete_restore_modal.modal('hide');
            }
        });
    </script>
@endpush
