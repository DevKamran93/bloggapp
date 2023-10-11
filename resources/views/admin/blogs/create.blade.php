@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-success card-outline direct-chat direct-chat-success">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Add New Blog</h3>
                </div>

                <div class="card-body p-3">
                    <form action="{{ route('blog.store') }}" class="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        value="{{ old('title') }}" autofocus>
                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="title" class="form-label">Image</label>
                                    <input type="file" name="image"
                                        class="form-control @error('image') is-invalid @enderror" id="blog_image"
                                        value="{{ old('image') }}" accept="image/png, image/jpg, image/jpeg">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-2">
                                <div>
                                    <img src="{{ url('storage/images/blogs/dummy.png') }}"
                                        data-old-src="{{ url('storage/images/blogs/dummy.png') }}" alt="Blog Image"
                                        class="img-responsive img-fluid" id="blog_image_preview">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="select2bs4" id="category" name="category_id"
                                        data-placeholder="Select a Category" style="width: 100%;">
                                        <option value="">Select Category</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                                        @empty
                                            <option>No Category Found</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tags" class="form-label">Tags</label>
                                    <input type="text" name="tags"
                                        class="form-control @error('tags') is-invalid @enderror" id="tags"
                                        value="{{ old('tags') }}">
                                    @error('tags')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-5">
                                <div class="bootstrap-switch-on bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-animate"
                                    style="width: 85.6px;">
                                    <div class="bootstrap-switch-container" style="width: 126px; margin-left: 0px;"><span
                                            class="bootstrap-switch-handle-on bootstrap-switch-success"
                                            style="width: 42px;">ON</span><span class="bootstrap-switch-label"
                                            style="width: 42px;">&nbsp;</span><span
                                            class="bootstrap-switch-handle-off bootstrap-switch-danger"
                                            style="width: 42px;">OFF</span><input type="checkbox" name="my-checkbox"
                                            checked="" data-bootstrap-switch="" data-off-color="danger"
                                            data-on-color="success"></div>
                                </div>
                            </div> --}}

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <br>
                                    <div class="d-flex justify-content-between">
                                        <div class="icheck-success d-inline">
                                            <input type="radio" name="status" id="show" value="1"
                                                class="@error('status') is-invalid @enderror">
                                            <label for="show">Show</label>
                                        </div>
                                        <div class="icheck-danger d-inline ml-auto mr-auto">
                                            <input type="radio" name="status" checked id="hide" value="0"
                                                class="@error('status') is-invalid @enderror">
                                            <label for="hide">Hide</label>
                                        </div>
                                    </div>
                                    @error('status')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="summernote_editor" name="description" class="@error('description') is-invalid @enderror"></textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <a href="{{ route('blogs') }}" class="btn bg-gradient-gray-dark btn-sm mr-2">Back</a>
                                <button type="submit" class="btn bg-gradient-navy btn-sm" id="save_update_btn">
                                    <span class="spinner-border spinner-border-sm d-none"
                                        id="save_update_btn_spinner"></span>
                                    Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @elseif (session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
@endsection
@push('javascript')
    <script src="{{ asset('assets/dist/js/common.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     var image = localStorage.getItem("image");
        //     if (image) {
        //         $("#blog_image_preview").attr("src", image);
        //     }
        //     var _URL = window.URL || window.webkitURL;
        //     $("#blog_image").change(function(e) {
        //         var file, img;
        //         if ((file = this.files[0])) {
        //             img = new FileReader();
        //             var objectUrl = _URL.createObjectURL(file);
        //             img.onload = function() {
        //                 // if (this.width == 1200 && this.height == 630) {
        //                 $("#blog_image_preview").attr("src", objectUrl);
        //                 localStorage.setItem("image", objectUrl);
        //                 // } else {
        //                 //     $(function() {
        //                 //         var Toast = Swal.mixin({
        //                 //             toast: true,
        //                 //             position: 'top-end',
        //                 //             showConfirmButton: false,
        //                 //             timer: 10000
        //                 //         });
        //                 //         Toast.fire({
        //                 //             icon: 'error',
        //                 //             title: 'Image Diamension Should be 1200 x 630',
        //                 //             // background: 'gray',
        //                 //         })
        //                 //         $('#blog_image').val('');
        //                 //     });
        //                 //     _URL.revokeObjectURL(objectUrl);
        //                 // }
        //             };
        //             img.src = objectUrl;
        //         }
        //     });
        // });
        // var image = localStorage.getItem("image");
        // if (image) {
        //     $("#blog_image_preview").attr("src", image);
        // }

        // function readAndValidateImage(input) {
        //     var old_image = $('#blog_image_preview').attr('src');
        //     // console.log();
        //     var file = input.files[0];

        //     if (!file) {
        //         return;
        //     }

        //     var reader = new FileReader();

        //     reader.onload = function(e) {
        //         var img = new Image();
        //         img.src = e.target.result;

        //         img.onload = function() {
        //             if (file.type.startsWith('image/') && img.width === 1200 && img.height === 630) {
        //                 $('#blog_image_preview').attr('src', e.target.result);
        //                 localStorage.setItem("image", e.target.result);
        //             } else {
        //                 $('#blog_image_preview').attr('src', old_image);
        //                 if (!file.type || !file.type.startsWith('image/')) {
        //                     $(function() {
        //                         var Toast = Swal.mixin({
        //                             toast: true,
        //                             position: 'top-end',
        //                             showConfirmButton: false,
        //                             timer: 10000
        //                         });

        //                         Toast.fire({
        //                             icon: 'error',
        //                             title: 'Please select a valid image file.',
        //                             // background: 'gray',
        //                         })
        //                         $('#blog_image').val('');
        //                     });
        //                 } else {
        //                     $(function() {
        //                         var Toast = Swal.mixin({
        //                             toast: true,
        //                             position: 'top-end',
        //                             showConfirmButton: false,
        //                             timer: 10000
        //                         });

        //                         Toast.fire({
        //                             icon: 'error',
        //                             title: 'Image Diamension Should be 1200 x 630',
        //                             // background: 'gray',
        //                         })
        //                         $('#blog_image').val('');
        //                     });
        //                 }
        //                 $('#blog_image').val(''); // Reset the file input field
        //                 localStorage.removeItem('image');

        //             }
        //         };
        //     };

        //     reader.readAsDataURL(file);
        // }

        // $(document).ready(function() {
        //     function readAndValidateImage(input) {
        //         var file = input.files[0];

        //         if (!file) {
        //             return;
        //         }

        //         var reader = new FileReader();

        //         reader.onload = function(e) {
        //             var img = new Image();
        //             img.src = e.target.result;

        //             img.onload = function() {
        //                 console.log(file.type.startsWith('image/'), img.width, img.height, img.src, e.target
        //                     .result);
        //                 if (file.type.startsWith('image/') && img.width == 1200 && img.height == 630) {
        //                     console.log('if');
        //                     $('#blog_image_preview').attr('src', e.target.result);
        //                     localStorage.setItem("image", e.target.result);
        //                     return false;
        //                 } else {

        //                     var old_image = $('#blog_image_preview').data('old-src');
        //                     $('#blog_image_preview').attr('src', old_image);
        //                     localStorage.removeItem("image");
        //                     console.log(old_image);
        //                     $('#blog_image').val('');
        //                     if (!file.type || !file.type.startsWith('image/')) {
        //                         console.log('in else if');

        //                         $(function() {
        //                             var Toast = Swal.mixin({
        //                                 toast: true,
        //                                 position: 'top-end',
        //                                 showConfirmButton: false,
        //                                 timer: 10000
        //                             });

        //                             Toast.fire({
        //                                 icon: 'error',
        //                                 title: 'Please select a valid image file.',
        //                                 // background: 'gray',
        //                             })
        //                             $('#blog_image').val('');
        //                         });
        //                     } else {
        //                         console.log('in else else');

        //                         $(function() {
        //                             var Toast = Swal.mixin({
        //                                 toast: true,
        //                                 position: 'top-end',
        //                                 showConfirmButton: false,
        //                                 timer: 10000
        //                             });

        //                             Toast.fire({
        //                                 icon: 'error',
        //                                 title: 'Image Diamension Should be 1200 x 630',
        //                                 // background: 'gray',
        //                             })
        //                             $('#blog_image').val('');
        //                         });
        //                     }
        //                 }
        //             };
        //         };

        //         reader.readAsDataURL(file);
        //     }

        //     $('#blog_image').change(function() {
        //         readAndValidateImage(this);
        //     });
        // });
        $(document).ready(function() {
            function readAndValidateImage(input) {
                var file = input.files[0];

                if (!file) {
                    return;
                }

                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = new Image();
                    img.src = e.target.result;
                    img.onload = function() {
                        if (img.width == 1200 && img.height == 630) {
                            $('#blog_image_preview').attr('src', e.target.result);
                            // Store image in local storage (optional)
                            localStorage.setItem("image", e.target.result);
                        } else {
                            var old_image = $('#blog_image_preview').data('old-src');
                            $('#blog_image_preview').attr('src', old_image);
                            $('#blog_image').val('');
                            localStorage.removeItem("image");
                            // console.log(file.type);
                            if (!file.type || !file.type.startsWith('image/')) {
                                displayError('Please Select an Image File !');
                            } else {
                                displayError('Image Dimensions Should be 1200 x 630');
                            }
                        }
                    };
                };

                reader.readAsDataURL(file);
            }

            function displayError(message) {
                var Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    iconColor: 'white',
                    padding: '1em',
                    customClass: {
                        popup: 'colored-toast',
                    },
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'error',
                    title: '<span class="h5 text-white ml-3">Wrong Image</span>',
                    text: message,
                });
            }

            $('#blog_image').change(function() {
                readAndValidateImage(this);
            });
        });
    </script>
    {{-- <script src="{{ asset('assets/dist/js/common.js') }}"></script> --}}
@endpush
