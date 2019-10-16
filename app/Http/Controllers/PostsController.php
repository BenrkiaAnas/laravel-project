<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ["except" => ["index","show"]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Getting All The Post
        // $posts = Post::all();
        //return $post = Post::where('title','Post One')->get();
        //$posts = DB::select("SELECT * FROM posts");
        //$posts = Post::orderBy('title','desc')->take(1)->get();7
        $posts = Post::orderBy('created_at','desc')->paginate(1);
        return view('posts.index')->with('posts',$posts);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        // Handle File Upload
        if($request->hasFile('cover_image')) // If The User Upload The File
        {
            // Get Filename whit Extension
            $fileNameWhitExt = $request->file('cover_image')->path();
            // Get Just File Name
            $fileName = pathinfo($fileNameWhitExt,PATHINFO_FILENAME);
            // Get Just Extension
            $extension = $request->file('cover_image')->extension();
            // Create File Name To Store
            $filenameToStore = $fileName.'_'.time().'.'.$extension;
            // Upload The Image
            $path = $request->file('cover_image')->storeAs("public/cover_images",$filenameToStore);
        }else{
            $filenameToStore = "noimage.jpg";
        }
        // Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = Auth::id();
        $post->cover_image = $filenameToStore;
        $post->save();
        // Redirect After Saving Data Whit Success Message | success => is the message on the session success
        return redirect('/posts')->with('success' , 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $post = Post::find($id);
        // Check For Correct User
        if(Auth::user()->id !== $post->user_id)
        {
            return redirect('/posts')->with('error','Unauthorized Page');
        }
        return view('posts.edit')->with('post',$post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validation
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        // Handle File Upload
        if($request->hasFile('cover_image')) // If The User Upload The File
        {
            // Get Filename whit Extension
            $fileNameWhitExt = $request->file('cover_image')->getClientOriginalName();
            // Get Just File Name
            $fileName = pathinfo($fileNameWhitExt,PATHINFO_FILENAME);
            // Get Just Extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            // Create File Name To Store
            $filenameToStore = $fileName.'_'.time().'.'.$extension;
            // Upload The Image
            $path = $request->file('cover_image')->storeAs("public/cover_images",$filenameToStore);
        }
        // Edit Post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')) // If Uploaded Image
        {
            $post->cover_image = $filenameToStore;
        }
        $post->save();
        return redirect('/posts')->with('success','Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        // Find The Post
        $post = Post::find($id);
        // Check For Correct User
        if(Auth::user()->id !== $post->user_id)
        {
            return redirect('/posts')->with('error','Unauthorized Page');
        }
        // Delete The Image Except The Default One
        if($post->cover_image !== "noimage.jpg")
        {
            // Delete Image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        $post->delete();
        return redirect('/posts')->with('success','Post Delete');
    }
}
