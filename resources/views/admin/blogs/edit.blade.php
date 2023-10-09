@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-success card-outline direct-chat direct-chat-success">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">Edit Blog</h3>
                </div>

                <div class="card-body p-3">
                    <form action="{{ route('blog.update') }}" class="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="slug" value="{{ Crypt::encrypt($blog->slug) }}">
                        <div class="row">
                            <div class="col-7">
                                <div class="form-group">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" id="title"
                                        value="{{ isset($blog->title) ? $blog->title : old('title') }}" autofocus>
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
                                        value="{{ old('image') }}">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-2">
                                <div>
                                    <img src="{{ isset($blog->image) ? $blog->image : url('storage/images/blogs/dummy.png') }}"
                                        alt="Blog Image" class="img-responsive img-fluid" id="blog_image_preview">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="select2bs4" id="category" name="category_id"
                                        data-placeholder="Select a Category" style="width: 100%;">
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ isset($blog->category_id) && $blog->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->title }}</option>
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
                                        value="{{ isset($blog->tags) ? $blog->tags : old('tags') }}">
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
                                                class="@error('status') is-invalid @enderror"
                                                {{ isset($blog->status) && $blog->status == 1 ? 'checked' : '' }}>
                                            <label for="show">Show</label>
                                        </div>
                                        <div class="icheck-danger d-inline ml-auto mr-auto">
                                            <input type="radio" name="status" id="hide" value="0"
                                                class="@error('status') is-invalid @enderror"
                                                {{ isset($blog->status) && $blog->status == 0 ? 'checked' : '' }}>
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
                                    <textarea id="summernote_editor" name="description" class="@error('description') is-invalid @enderror">{{ isset($blog->description) ? $blog->description : old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <a href="{{ route('blogs') }}" class="btn bg-gradient-gray-dark btn-sm mr-2">Back</a>
                                <button type="submit" class="btn bg-gradient-navy btn-sm">Update</button>
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
@endpush
