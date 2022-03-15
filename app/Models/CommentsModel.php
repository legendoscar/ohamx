<?php
  
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
  
class CommentsModel extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'comments';
   
    const CREATED_AT = 'blog_comment_creation_date';
    const UPDATED_AT = 'blog_comment_update_date';
    const DELETED_AT = 'blog_comment_deleted_at';
    
    
    protected $dates = ['blog_comment_deleted_at'];
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'post_id', 'parent_id', 'body'];
   
    /**
     * The belongs to Relationship
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    /**
     * The has Many Relationship
     *
     * @var array
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}