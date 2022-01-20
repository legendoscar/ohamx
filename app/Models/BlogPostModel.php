<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Carbon\Carbon;

Class BlogPostModel extends Model {

    use SoftDeletes, HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = ['author_id', 'post_title', 'post_body', 'post_slug', 'isActve', 'isPublished', 
    'date_drafted', 'date_published']; 

    // public $author_id = 1;

    /**
     * The attributes for date created at, deleted at and updated at columns.
     *
     * @var array
     */
    const CREATED_AT = 'blog_post_creation_date';
    const UPDATED_AT = 'blog_post_update_date';
    const DELETED_AT = 'blog_post_deleted_at';

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'date_published' => 'datetime', 'date_drafted' => 'datetime',

    ];



    public function __construct()
    {
        // $this->author_id = $author_id; 
    }


    public function showAllPublishedAndActivePosts(){
        
        try {
            $query = $this
            ->where('isActve', '=', 1)
            ->where('isPublished', '=', 1)
            ->where('blog_posts.blog_post_deleted_at', '=', null)
            ->join('users', 'blog_posts.author_id', 'users.id')
            ->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Blog posts returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No published blog post found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
    }


      /**
     * Show single blog post by ID.
     *
     * @return void
     */
    public function showSingleBlogPost($id){
        try {
            
            $data = $this->findOrFail($id)
            ->join('users', 'blog_posts.author_id', 'users.id')
            ->where('isPublished', '=', 1)
            ->where('blog_posts.blog_post_deleted_at', '=', null)
            ->get(); 
            !empty($data)
                ? $ret = response()->json([
                    'data'=> $data,
                    'msg' => 'Record returned successfully.',
                    'statusCode' => 200
                ], 200)
                : $ret = response()->json([
                'msg' => 'No Record found for post `' . $data->post_title . '` with ID: ' . $id,
                'statusCode' => 404
            ], 404);
    
            return $ret;
    
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }
        }

        
    public function createBlogPost(Request $request){
        try{
            
            $image_name = $request->post_image;
            if($request->hasFile('post_image')){
                $image_name = $request->post_image->getClientOriginalName();

                $path = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                $destinationPath = app()->basePath($path);
                $request->file('post_image')->move($destinationPath, $image_name);
            }

            $BlogPostModel = new BlogPostModel;

            $delimiter = '-';
            $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', 
            $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', 
            iconv('UTF-8', 'ASCII//TRANSLIT', $request->post_title))))), $delimiter));


            $BlogPostModel->author_id = auth()->user()->id;
            $BlogPostModel->post_title = $request->post_title;
            $BlogPostModel->post_body = $request->post_body;
            $request->post_slug ? $BlogPostModel->post_slug = $request->post_slug : $BlogPostModel->post_slug = $slug;
            $BlogPostModel->post_image = $request->image_name;
            $BlogPostModel->isPublished = $request->isPublished;
            $request->isPublished ? $BlogPostModel->date_published = Carbon::now() :  $BlogPostModel->date_published = null;

            $BlogPostModel->save();

            return response()->json([
                'msg' => 'New blog post: `' . $request->post_title .'` created successfully',
                'data' => $BlogPostModel,
                'statusCode' => 201
            ], 201);
            } catch(\Exception $e){
                return response()->json([
                    'msg' => 'Blog post creation failed!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
            }

    }


    public function updateSingleBlogPost(Request $request){
        try {

            $BlogPostModel = BlogPostModel::findorFail($request->id);
            $BlogPostModel->author_id = auth()->user()->id;
            $BlogPostModel->post_title = $request->filled('post_title') ? $request->post_title : $BlogPostModel->post_title;
            $BlogPostModel->post_body = $request->filled('post_body') ? $request->post_body : $BlogPostModel->post_body;
            
            $delimiter = '-';
           $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', 
            $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', 
            iconv('UTF-8', 'ASCII//TRANSLIT', $BlogPostModel->post_title))))), $delimiter));

            $BlogPostModel->post_slug = $request->filled('post_slug') ? $slug : $BlogPostModel->post_slug;
            
            //date_published
            $BlogPostModel->isActve = $request->filled('isActve') ? $request->isActve : $BlogPostModel->isActve;
            $BlogPostModel->date_published = $request->filled('isPublished') ? 
            $BlogPostModel->date_published == Carbon::now() : $BlogPostModel->date_published;

            $BlogPostModel->save();

            return response()->json([
                'data' => $BlogPostModel,
                'msg' => '`' . $BlogPostModel->post_title . '` details updated successfully.',
                'statusCode' => 200
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooopss! Error encountered!!!',
                    'err' => $e->getMessage(),
                    'statusCode' => 409
            ], 409);
        }
    }

     /**
     * Get popular crypto coins.
     *
     * @return void
     */

    public function getPopularCrypto(){
        try {
            $query = $this->where('author_id', '=', 1)->where('is_popular', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    /**
     * Get recommended crypto coins.
     *
     * @return void
     */
    public function getRecommendedCrypto(){
        try {
            $query = $this->where('author_id', '=', 1)->where('is_recommended', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
    
    
    /**
     * Get new crypto coins.
     *
     * @return void
     */
    public function getNewCrypto(){
        try {
            $query = $this->where('author_id', '=', 1)->where('is_new', '=', 1)->get();
            $count = count($query);
            return response()->json([
            'msg' => $count . ' Crypto coins returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'No crypto coin found!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
   

    /**
     * Get published blog posts by author.
     *
     * @return void
     */
   
    public function showAllBlogPostsByAuthor($id){
        try {
            $query = $this->findOrFail($id)
            ->where('isActve', '=', 1)
            ->where('isPublished', '=', 1)
            ->where('blog_posts.blog_post_deleted_at', '=', null)
            ->join('users', 'blog_posts.author_id', 'users.id')
            ->get();

            $count = count($query);
            return response()->json([
            'msg' => $count . ' published blog posts returned successfully.',
                'data' => $query,
                'statusCode' => 200,
            ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'msg' => 'Ooops! Error encountered!!', 
                    'err' => $e->getMessage(),
                    'statusCode' => 409
                ], 409);
        }
    }
}