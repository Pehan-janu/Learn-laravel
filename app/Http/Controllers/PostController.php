<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;


class PostController extends Controller
{
    public function deletePost(Post $post){
        if(Auth::user()->id === $post['user_id']){
            $post->delete();
        }
        return redirect('/');

    }


    public function actualyUpdatePost(Post $post, Request $request){
        if(Auth::user()->id !== $post['user_id']){
            return redirect('/');
        }
        
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return redirect('/');

    }

    public function showEditScreen(Post $post){
        if(Auth::user()->id !== $post['user_id']){
            return redirect('/');

        }
        return view('edit-post', ['post' => $post]);
    }

    public function createPost(Request $request){
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
            
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = Auth::id();
        Post::create($incomingFields);
        return redirect('/');
    }
}
