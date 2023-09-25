<?php

use Illuminate\Support\Str;
use App\Models\Admin\Category;
use Illuminate\Support\Facades\File;

if (!function_exists('storeImage')) {
    function storeImage($image, $folder)
    {
        $path = "assets/admin/img/$folder";

        if (!File::exists(public_path($path))) {
            File::makeDirectory(public_path($path), 0755, true);
        }

        $new_image_name = time() . "-" . rand(100000, 999999) . "." . $image->extension();
        $image->move(public_path($path), $new_image_name);
        return $new_image_name;
    }
}

if (!function_exists('checkRelation')) {
    // Generic Function For Checking Relation
    function checkRelation($has_children = '', $has_firmwares = '')
    {
        return ($has_children ? 'Child Category ' : '') . ($has_firmwares ? 'Firmwares ' : '');
    }
}

if (!function_exists('JsonResponse')) {
    // Generic Function For Response
    function JsonResponse($status, $state, $message)
    {
        return response()->json([
            'status' => $status,
            'state' => $state,
            'message' => $message
        ]);
    }
}

if (!function_exists('uniqueSlug')) {
    // Generic Function For Response
    // function uniqueSlug($title)
    // {
    //     $title = strtolower($title);
    //     $slug_exist = Category::whereSlug($slug = Str::slug($title))->first('slug');
    //     if ($slug_exist) {
    //         if (is_numeric($slug_exist[-1])) {
    //             return preg_replace_callback('/(\+d)$/', function ($matches) {
    //                 return $matches[1] + 1;
    //             }, $slug_exist);
    //         }
    //         return "{$slug_exist}-2";
    //     }
    //     return $slug;
    // }

    function uniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = Category::where('slug', strtolower($slug))->exists();

        while ($count) {
            $slug = $slug  . rand(0, 3);
            $count = Category::where('slug', $slug)->exists();
        }

        return  $slug;
    }
    if (!function_exists('generateBreadcrumb')) {
        function generateBreadcrumb($segments)
        {
            $breadcrumb = [];

            $projectName = 'GSM'; // Replace with the actual project name or get it from your configuration/settings.
            $breadcrumb[] = ['text' => $projectName, 'url' => '/']; // Assuming the home page URL is '/'

            $urlSoFar = '';
            foreach ($segments as $segment) {
                $urlSoFar .= '/' . $segment['url'];
                $breadcrumb[] = ['text' => $segment['text'], 'url' => $urlSoFar];
            }

            return $breadcrumb;
        }
    }
}
