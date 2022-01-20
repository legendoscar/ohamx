<?php

namespace App\Http\Controllers;

use App\Models\BlogPostModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class BlogPostsController extends Controller 
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['showAll', 'showOneCrypto', 'showStoreProducts']]);
        $this->middleware('admin', ['only' => ['createBlogPost', 'updateSingleBlogPost', 'deleteSingleBlogPost']]);
        
    } 

    /**
     * Show all published and active blog posts.
     *
     * @return void
     */

    public function showAllPublishedAndActivePosts(BlogPostModel $BlogPostModel)
    {
        return $BlogPostModel->showAllPublishedAndActivePosts();       

    }
    
    
    /**
     * Show single blog post by ID.
     *
     * @return void
     */

    public function showSingleBlogPost(BlogPostModel $BlogPostModel, $id)
    {
        return $BlogPostModel->showSingleBlogPost($id);       

    }


    /**
     * Show single exchange rate for single asset.
     *
     * @return void
     */
    public function showOneExchangeRate(Request $request, BlogPostModel $BlogPostModel)
    {
        return $BlogPostModel->showOneExchangeRate($request->id);
    }


    /**
     * Create a new exchange rate for an asset.
     *
     * @return void
     */
    public function createBlogPost(Request $request, BlogPostModel $BlogPostModel)
    {    
        // return 33;      
        $rules = [
            'post_title' => 'bail|required|string|unique:blog_posts,post_title',
            'post_body' => 'bail|required|string',
            'post_slug' => 'bail|string|unique:blog_posts,post_slug',
            'post_image' => 'bail|file',
            'isPublished' => 'bail|boolean',
            'isActve' => 'bail|boolean',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errorMsg' => $validator->errors(), 
                'statusCode' => 422
            ], 422);
        };
        
        return $BlogPostModel->createBlogPost($request);
       
    }



    /**
     * Update exchange rate for an asset.
     *
     * @return void
     */
    public function updateSingleBlogPost(Request $request, BlogPostModel $BlogPostModel, $id)
    {

        // return 33;
        $rules = [
            'post_title' => 'bail|string|unique:blog_posts,post_title',
            'post_body' => 'bail|string',
            'post_slug' => 'bail|string|unique:blog_posts,post_slug',
            'post_image' => 'bail|file',
            'isPublished' => 'bail|boolean',
            'isActve' => 'bail|boolean',
        ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'errorMsg' => $validator->errors(), 
                    'statusCode' => 422
                ], 422);
             };

             return $BlogPostModel->updateSingleBlogPost($request);

       
    }

    /**
     * Delete a single blog post.
     *
     * @return void
     */
    public function deleteSingleBlogPost($id)
    {
        
        try {
                $post = BlogPostModel::findOrFail($id);
                if($post->blog_post_deleted_at != null){
                    return response()->json([
                        'msg' => 'Blog post already deleted!',
                        'statusCode' => 200
                    ], 200);
                }
                
                return response()->json([
                    'msg' => 'Blog post Deleted successfully!',
                    'statusCode' => 200
                ], 200);
                }catch(\Exception $e){
                    return response()->json([
                        'msg' => 'Ooops!! Error encountered!!',
                        'err' => $e->getMessage(),
                        'statusCode' => 409
                    ], 409);
            }
        }
    
    
        /**
     * Delete a single exchange rate for an asset.
     *
     * @return void
     */
    public function deleteExchangeRatesForAnAsset($id)
    {
        
        try {
                // return
                $id =
                BlogPostModel::
                where('is_active', '=', 1)
                ->where('asset_id', '=', $id)
                ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
                // ->join('asset_list', 'exchange_rates.asset_id', 'asset_list.id')
                // ->get();
                ->pluck('exchange_rates.id');


                $ids = explode(",", $id);


             return  BlogPostModel::find($id)->each(function($rate, $key){
                   return $key;
                   $key->delete();
               });
                return response()->json([
                    'msg' => 'Exchange rates Deleted for selected asset successfully!',
                    'statusCode' => 200
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Delete operation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
        }
    
    
        /**
     * Get all published blog posts by author.
     *
     * @return void
     */

    public function showAllBlogPostsByAuthor(BlogPostModel $BlogPostModel, $id)
    { 
       return $BlogPostModel->showAllBlogPostsByAuthor($id);
    }
    
   
}
