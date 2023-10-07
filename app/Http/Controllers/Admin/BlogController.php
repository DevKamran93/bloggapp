<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Blog;
use Illuminate\Http\Request;
use App\Models\Admin\Category;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\BlogRequestValidate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        // $page_name = 'Blogs';
        $current_page = 'Your Page Name';
        $segment = 'categories';
        return view('admin.blogs.index', compact('current_page', 'segment'));
    }

    public function getAllBlogsData()
    {
        $query = Blog::withTrashed()
            ->with(['user', 'user.role', 'category'])
            ->orderBy('created_at', 'desc');

        // $query = Blog::withTrashed()
        //     ->with('user')
        //     ->orderBy('created_at', 'desc');

        return DataTables::eloquent($query)
            ->filterColumn('blog_title', function ($query, $keyword) {
                $sql = "CONCAT(blogs.title) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('blog_title', function ($query, $order) {
                $query->where('title', $order);
            })
            ->addColumn('blog_title', function (Blog $blog) {
                return $blog->title;
            })
            ->addColumn('image', function (Blog $blog) {
                $blog_image = '';
                $blog_image = '<img src="' . asset($blog->image) . '" class="img-fluid img-responsive">';
                return $blog_image;
            })
            // ->filterColumn('category', function ($query, $keyword) {
            //     $sql = "CONCAT(blogs.type) like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            ->orderColumn('category', function ($query, $order) {
                $query->where('category_id', $order);
            })
            ->addColumn('category', function (Blog $blog) {
                return ucfirst($blog->category->title);
            })
            ->addColumn('user', function (Blog $blog) {
                $user = '';
                $user = '<span>' . $blog->user->name . ' <small class="font-weight-bold">(' . $blog->user->role->name . ')</small></span>';
                return $user;
            })
            ->addColumn('tags', function (Blog $blog) {
                return $blog->tags;
            })
            ->addColumn('status', function (Blog $blog) {
                $status = '';
                $status = $blog->status == 0 ? '<span>Hide</span>' : '<span>Show</span>';
                return $status;
            })
            ->addColumn('comments', function (Blog $blog) {
                $comments = '';
                $comments = $blog->comments == 0 ? '<span>Off</span>' : '<span>On</span>';
                return $comments;
            })
            ->addColumn('created_at', function (Blog $blog) {
                return $blog->created_at->format('d-M-y, g:i A');
            })
            ->addColumn('updated_at', function (Blog $blog) {
                return $blog->updated_at->format('d-M-y, g:i A');
            })
            ->addColumn('actions', function (Blog $blog) {
                $buttons = '';
                if (is_null($blog->deleted_at)) {
                    $buttons = '<a href="' . route('blog.edit', $blog->slug) . '" class="btn bg-gradient-navy btn-sm edit_blog" id="edit_blog"><i class="fa fa-pen-alt"></i></a>
                    <button type="button" class="btn bg-gradient-danger btn-sm delete_restore_category" data-id="' . $blog->id . '" data-action="delete"  data-toggle="modal" data-target="#delete_restore_modal" >
                    <i class="fa fa-trash-alt"></i>
                    </button>';
                } else {
                    $buttons = '<button type="button" class="btn bg-gradient-danger btn-sm delete_restore_category" data-id="' . $blog->id . '" data-action="restore" data-toggle="modal" data-target="#delete_restore_modal" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fa fa-history"></i>
                                </button>';
                }
                return $buttons;
            })

            ->only(['blog_title', 'image', 'category', 'user', 'tags', 'status', 'comments', 'created_at', 'updated_at', 'actions'])
            ->rawColumns(['image', 'user', 'status', 'comments', 'actions'])
            ->addIndexColumn()
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::get();

        // Set a success toast, with a title
        // toastr()->success('Data has been saved successfully!', 'Congrats', ['closeButton' => true]);
        // drakify('success'); // for success alert
        // drakify('error'); // for error alert
        // smilify('success', 'You are successfully reconnected');
        // emotify('success', 'You are awesome, your data was successfully created');
        // notify()->preset('blog-added');
        // connectify('success', 'Connection Found', 'Success Message Here');

        // notify()->preset('common-notification', ['title' => 'This is the overridden title']);

        return view('admin.blogs.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequestValidate $request)
    {
        $path = storeImage($request->image, 'blogs');

        $blog = Blog::create([
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'slug' => uniqueSlug($request->title),
            'image' => $path, // Store the full path in the 'image' field
            'description' => $request->description,
            'tags' => $request->tags,
            'status' => $request->status,
        ]);

        if ($blog) {
            connectify('success', 'Successful !', 'New Blog Successfully Added');
            return to_route('blogs');
        } else {
            if (isset($path)) {
                removeImage($path);
            }
            connectify('error', 'Failed !', 'Operation Failed, Please Try Again');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {
        $blog = Blog::where('slug', $slug)->first();
        $categories = Category::get();
        return view('admin.blogs.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequestValidate $request)
    {
        $blog = Blog::where('slug', Crypt::decrypt($request->slug))->first();

        if ($request->image) {
            $path = storeImage($request->image, 'blogs');
            removeImage($blog->image);
            $blog->image = $path;
        }

        $blog_data = $request->except(['_token', '_method', 'image']);
        $blog_data['slug'] = uniqueSlug($request->title);

        if ($blog->update($blog_data)) {
            connectify('success', 'Successful !', 'Blog Successfully Updated');
            return to_route('blogs');
        } else {
            if (isset($path)) {
                removeImage($path);
            }
            connectify('error', 'Failed !', 'Operation Failed, Please Try Again');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroyOrRestore(Request $request)
    {
        $blog = Blog::withTrashed()->where('id', $request->id)->first();
        if (is_null($blog->deleted_at)) {
            $success = $blog->delete();
            $message = '<h3>Blog Successfully Deleted !</h3>';
        } elseif (!is_null($blog->deleted_at)) {
            $success = $blog->restore();
            $message = 'Blog Successfully Restored !';
        }

        if ($success) {
            return JsonResponse(200, 'success', "$message");
        } else {
            return JsonResponse(422, 'warning', 'Operation Failed, Try Again !');
        }
    }
}
