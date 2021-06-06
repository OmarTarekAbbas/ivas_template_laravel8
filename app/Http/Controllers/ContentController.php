<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Repository\CategoryRepository;
use App\Http\Repository\ContentRepository;
use App\Http\Repository\ContentTypeRepository;
use App\Http\Requests\ContentRequest;
use App\Http\Services\ContentService;

class ContentController extends Controller
{
    /**
     * contentRepository
     *
     * @var ContentRepository
     */
    private $contentRepository;
    /**
     * contentService
     *
     * @var ContentService
     */
    private $contentService;
    /**
     * contentUpdateService
     *
     * @var ContentUpdateService
     */
    private $contentUpdateService;
    /**
     * contentTypeRepository
     *
     * @var ContentTypeRepository
     */
    private $contentTypeRepository;
    /**
     * __construct
     *
     * @param  ContentRepository $contentRepository
     * @param  ContentTypeRepository $contentTypeRepository
     * @param  ContentService $contentService
     * @param  CategoryRepository $categoryRepository
     * @return void
     */
    public function __construct(
        ContentRepository $contentRepository,
        ContentTypeRepository $contentTypeRepository,
        CategoryRepository $categoryRepository,
        ContentService $contentService
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentService = $contentService;
        $this->contentTypeRepository = $contentTypeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * index
     * get all content data
     * @return View
     */
    public function index()
    {
        $contents = $this->contentRepository->with('type')->get();
        return view('content.index',compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return  View
     */
    public function create()
    {
        $content = null;
        $content_types = $this->contentTypeRepository->all();
        $categorys = $this->categoryRepository->all();
        return view('content.form',compact('content_types','content','categorys'));
    }

    /**
     * store content data
     *
     * @param  ContentRequest $request
     * @return Redirect
     */
    public function store(ContentRequest $request)
    {
        $this->contentService->handle($request->validated());

        $request->session()->flash('success', 'Content created successfull');

        return redirect('content');
    }

    /**
     * Show the form for editing the specified resource.
     * @param   int  $id
     * @return  View
     */
    public function edit($id)
    {
        $content = $this->contentRepository->with('type')->findOrfail($id);
        $content_types = $this->contentTypeRepository->all();
        $categorys = $this->categoryRepository->all();
        return view('content.form',compact('content_types','content','categorys'));
    }

    /**
     * update
     *
     * @param  int $id
     * @param  ContentRequest $request
     * @return Redirect
     */
    public function update($id, ContentRequest $request)
    {
        $content = $this->contentRepository->findOrfail($id);

        $this->contentService->handle($request->validated(), $id);

        $request->session()->flash('success', 'updated successfully');

        return redirect('content');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param    int $id
     * @return  \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = $this->contentRepository->findOrfail($id);

        $content->delete();

        \Session::flash('success', 'deleted successfully');

        return back();
    }
}
