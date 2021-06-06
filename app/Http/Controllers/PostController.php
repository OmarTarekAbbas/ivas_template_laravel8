<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Repository\ContentRepository;
use App\Http\Repository\PostRepository;
use App\Http\Repository\OperatorRepository;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Services\PostStoreService;
use App\Http\Services\PostUpdateService;
use App\Models\Post;
use App\Models\Type;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * @var PostRepository
     */
    private $postRepository;
    /**
     * @var PostStoreService
     */
    private $postStoreService;
    /**
     * @var PostUpdateService
     */
    private $postUpdateService;
    /**
     * @var OperatorRepository
     */
    private $operatorRepository;
    /**
     * @var ContentRepository
     */
    private $contentRepository;
    /**
     * __construct
     *
     * @param  PostRepository $postRepository
     * @param  OperatorRepository $operatorRepository
     * @param  ContentRepository $contentRepository
     * @param  PostStoreService $postStoreService
     * @param  PostUpdateService $postUpdateService
     * @return void
     */
    public function __construct(
        PostRepository $postRepository,
        OperatorRepository $operatorRepository,
        ContentRepository $contentRepository,
        PostStoreService $postStoreService,
        PostUpdateService $postUpdateService
    ) {
        $this->postRepository = $postRepository;
        $this->postStoreService = $postStoreService;
        $this->postUpdateService = $postUpdateService;
        $this->operatorRepository = $operatorRepository;
        $this->contentRepository = $contentRepository;
    }

    /**
     * index
     * get all post data
     * @return View
     */
    public function index()
    {
        $posts = $this->postRepository->get();
        $contents = $this->contentRepository->all();
        return view('post.index',compact('posts', 'contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  View
     */
    public function create()
    {
        $operators = $this->operatorRepository->all();
        $contents = $this->contentRepository->all();
        $post = null;
        return view('post.form',compact('post' ,'operators', 'contents'));
    }

    /**
     * store post data
     *
     * @param  PostStoreRequest $request
     * @return Redirect
     */
    public function store(PostStoreRequest $request)
    {
        $this->postStoreService->handle($request->validated());

        $request->session()->flash('success', 'Post created successfull');

        return redirect('post');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int  $id
     * @return  View
     */
    public function edit($id)
    {
        $post = $this->postRepository->findOrfail($id);
        $operators = $this->operatorRepository->all();
        $contents = $this->contentRepository->all();

        return view('post.form',compact('post', 'operators', 'contents'));
    }

    /**
     * update
     *
     * @param  int $id
     * @param  PostUpdateRequest $request
     * @return Redirect
     */
    public function update($id, PostUpdateRequest $request)
    {
        $post = $this->postRepository->findOrfail($id);

        $this->postUpdateService->handle($request->validated(), $post);

        $request->session()->flash('success', 'updated successfully');

        return redirect('post');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->postRepository->findOrfail($id);

        $post->delete();

        session()->flash('success', 'deleted successfully');

        return back();
    }
}
