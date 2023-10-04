<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CategoryRequestValidate;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $page_name = 'Categories';

        return view('admin.categories.index');
    }

    public function getAllCategoryData()
    {
        $query = Category::withTrashed()
            ->with(['user', 'user.role'])
            ->orderBy('created_at', 'desc');

        $query = Category::withTrashed()
            ->with('user')
            ->orderBy('created_at', 'desc');

        return DataTables::eloquent($query)
            ->filterColumn('category_title', function ($query, $keyword) {
                $sql = "CONCAT(categories.title) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('category_title', function ($query, $order) {
                $query->where('title', $order);
            })
            ->addColumn('category_title', function (Category $category) {
                return $category->title;
            })
            ->filterColumn('type', function ($query, $keyword) {
                $sql = "CONCAT(categories.type) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->orderColumn('type', function ($query, $order) {
                $query->where('type', $order);
            })
            ->addColumn('type', function (Category $category) {
                return ucfirst($category->type);
            })
            ->addColumn('user', function (Category $category) {
                $user = '';
                $user = '<span>' . $category->user->name . ' <small class="font-weight-bold">(' . $category->user->role->name . ')</small></span>';
                return $user;
            })
            ->addColumn('created_at', function (Category $category) {
                return $category->created_at->format('d-M-y, g:i A');
            })
            ->addColumn('updated_at', function (Category $category) {
                return $category->updated_at->format('d-M-y, g:i A');
            })
            ->addColumn('actions', function (Category $category) {
                $buttons = '';
                if (is_null($category->deleted_at)) {
                    $buttons = '<button type="button" class="btn bg-gradient-navy btn-sm edit_category" id="edit_category" data-id="' . $category->id . '" data-title="' . $category->title . '" data-type="' . $category->type . '" data-toggle="modal" data-target="#add_edit_category_modal">
                    <i class="fa fa-pen-alt"></i>
                    </button>
                    <button type="button" class="btn bg-gradient-danger btn-sm delete_restore_category" data-id="' . $category->id . '" data-action="delete"  data-toggle="modal" data-target="#delete_restore_modal" >
                    <i class="fa fa-trash-alt"></i>
                    </button>';
                } else {
                    $buttons = '<button type="button" class="btn bg-gradient-danger btn-sm delete_restore_category" data-id="' . $category->id . '" data-action="restore" data-toggle="modal" data-target="#delete_restore_modal" data-toggle="tooltip" data-placement="top" title="Restore"><i class="fa fa-history"></i>
                                </button>';
                }
                return $buttons;
            })

            ->only(['category_title', 'user', 'type', 'created_at', 'updated_at', 'actions'])
            ->rawColumns(['user', 'actions'])
            ->addIndexColumn()
            ->toJson();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequestValidate $request)
    {
        // dd($request->all());
        $category = Category::create([
            'title' => $request->title,
            'slug' => uniqueSlug($request->title),
            'type' => $request->type,
            'user_id' => Auth::id(),
        ]);
        if ($category) {
            return JsonResponse(200, 'success', 'Category Successfully Added !');
        } else {
            return JsonResponse(422, 'warning', 'Something Went Wrong !');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequestValidate $request)
    {
        // dd($request->all());
        $category = Category::findOrFail($request->category_id);
        $category['slug'] = uniqueSlug($request->title);
        $category_data = $request->except(['_token', '_method']);

        if ($category->update($category_data)) {
            return JsonResponse(200, 'success', 'Category Successfully Updated !');
        } else {
            return JsonResponse(422, 'warning', 'Category Not Updated !');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyOrRestore(Request $request)
    {
        // dd($request->all());
        $category = Category::withTrashed()->where('id', $request->id)->first();
        if (is_null($category->deleted_at)) {
            $success = $category->delete();
            $message = 'Category Successfully Deleted !';
        } elseif (!is_null($category->deleted_at)) {
            $success = $category->restore();
            $message = 'Category Successfully Restored !';
        }

        if ($success) {
            return JsonResponse(200, 'success', "$message");
        } else {
            return JsonResponse(422, 'warning', 'Operation Failed, Try Again !');
        }
    }

    // public function restore(Request $request)
    // {
    //     $category = Category::onlyTrashed()->where('id', $request->category_id);
    //     // dd($category);
    //     if ($category->restore()) {
    //         return JsonResponse(200, 'success', 'Category Successfully Restore !');
    //     } else {
    //         return JsonResponse(422, 'warning', 'Category Not Restored !');
    //     }
    // }
}
