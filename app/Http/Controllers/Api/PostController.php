<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    //

    public function index()
    {
		//get all posts
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'List data posts', $posts);
    }

    public function store(Request $request)
    {
        //validate
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required',
            'content' => 'required',
        ]);

        //check if validator fails
        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //upload
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //create post
        $post = Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        //return response
        return new PostResource(true, 'Data added', $post);
    }

    public function show($id)
    {
        //find post by id
        $post = Post::find($id);

        return new PostResource(true, 'Post Detail', $post);
    }

    public function update(Request $request, $id)
    {
        //validate
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        //check if validator fails
        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //find post by id
        $post = Post::find($id);

        //check if has image
        if($request->hasFile('image')) {
            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/'.basename($post->image));

            //update post with new image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
                'image' => $image->hashName(),
            ]);
        }else {
            //update post without image
            $post->update([
                'title' => $request->title,
                'content' => $request->content,
            ]);
        }

        return new PostResource(true, 'Data updated', $post);
    }

    public function destroy($id)
    {
        //find post by id
        $post = Post::find($id);

        if($post) {
            //delete image
            Storage::delete('public/posts/'.basename($post->image));

            //delete post
            $post->delete();

            return new PostResource(true, 'Data deleted', null);
        }else {
            return new PostResource(false, 'Data not found', null);
        }
    }
}
