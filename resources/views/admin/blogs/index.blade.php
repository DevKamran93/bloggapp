@extends('layouts.app')
@section('content')
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-body p-2">
                    <table id="blogs_table" class="table-bordered table-striped table-sm table text-center">
                        <thead class="bg-gradient-navy text-white">
                            <tr>
                                <th>Sr #</th>
                                <th>Title</th>
                                <th>Image</th>
                                <th>Category</th>
                                <th>Added By</th>
                                <th>Tags</th>
                                {{-- <th>Description</th> --}}
                                <th>Status</th>
                                <th>Comments</th>
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
    @include('partials._delete_restore_modal')


    {{-- <div class="modal fade" id="delete_restore_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header py-2" id="delete_restore_modal_heading">
                    <h5 class="modal-title font-weight-bold"></h5>
                    <button type="button" class="close delete_restore_close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="delete_restore_modal_body">
                    <h5></h5>
                    <form class="form" id="delete_restore_form">
                        <input type="hidden" name="category_id" id="category_id">
                    </form>
                </div>
                <div class="modal-footer justify-content-between py-1">
                    <button type="button" class="btn bg-gradient-gray-dark btn-sm delete_restore_close">Close</button>
                    <button type="submit" name="submit" class="btn btn-sm" id="delete_restore_modal_btn"></button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@push('javascript')
    <script>
        $(document).ready(function() {
            fetchAllBlogs();

            var delete_restore_modal = $('#delete_restore_modal');


            function fetchAllBlogs() {
                $("#blogs_table").DataTable({
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
                    "ajax": "{{ route('blogs.getAllBlogsData') }}",
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'blog_title',
                            name: 'blog_title',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'image',
                            name: 'image',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'category',
                            name: 'category',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user',
                            name: 'user',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tags',
                            name: 'tags',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'comments',
                            name: 'comments',
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
                var url = "{{ route('blog.destroyOrRestore') }}";
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
                            background: 'maroon',
                            color: 'white',
                        })

                        fetchAllBlogs();
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
