<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BlogPostRepository;

class HomeController extends Controller
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->blogPostRepository = app(BlogPostRepository::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $postsData = $this->blogPostRepository->getByIdPosts($userId);

        return view('home', [
            'post_data' => $postsData
        ]);
    }
}
