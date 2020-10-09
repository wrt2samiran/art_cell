<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;
use App\Post;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
class PostController extends Controller
{
    public function index(){
    	$posts=Post::withTranslation()->get();
    	// dd($posts[0]->translations);
    	return view('post',compact('posts'));
    }

    public function change_language($locale){
        App::setLocale($locale);
        session()->put('locale', $locale);

        return redirect()->back();
    }

    public function datatable(){
    	    $posts = Post::select('posts.*');
            return Datatables::of($posts)->editColumn('created_at', function ($post) {
                return $post->created_at ? with(new Carbon($post->created_at))->format('m/d/Y') : '';
            })
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereTranslationLike('title', "%{$keyword}%");
            })
            ->orderColumn('title', function ($query, $order) {
                 $query->orderByTranslation('title',$order);
            })

            ->filterColumn('description', function ($query, $keyword) {
                $query->whereTranslationLike('description', "%{$keyword}%");
            })
            ->orderColumn('description', function ($query, $order) {
                 $query->orderByTranslation('description',$order);
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(created_at,'%m/%d/%Y') like ?", ["%$keyword%"]);
            })
            ->make(true);
    }

    public function storePost(Request $request){
      
	    $post_data = [
	       'ar' => [
	           'title'       => $request->input('ar_title'),
	           'description' => $request->input('ar_description')
	       ],
	       'author'=>'author'
	    ];

        if($request->post_in_english){
            $post_data['en']=[
               'title'       => $request->input('en_title'),
               'description' => $request->input('en_description')
           ];
        }

	    // Now just pass this array to regular Eloquent function and Voila!    
	    Post::create($post_data);

	    return redirect()->back();

    }
}
