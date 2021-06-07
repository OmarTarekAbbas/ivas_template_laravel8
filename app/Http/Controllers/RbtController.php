<?php

namespace App\Http\Controllers;

use App\Constants\ContentTypes;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Repository\ContentRepository;
use App\Http\Repository\OperatorRepository;
use App\Http\Repository\RbtRepository;
use App\Http\Requests\RbtRequest;
use App\Http\Services\RbtService;
class RbtController extends Controller
{
    public function __construct(
        RbtRepository $rbtRepository,
        ContentRepository $contentRepository,
        OperatorRepository $operatorRepository,
        RbtService $rbtService)
    {
        $this->rbtService         = $rbtService;
        $this->rbtRepository      = $rbtRepository;
        $this->contentRepository  = $contentRepository;
        $this->operatorRepository = $operatorRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = $this->contentRepository->all();

        return view('rbt.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $contents  = $this->contentRepository->where('content_type_id', ContentTypes::AUDIO)->get();
      $operators = $this->operatorRepository->all();
      $rbt      = NULL;

      return view('rbt.form',compact('contents','operators','rbt'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RbtRequest $request)
    {
      $this->rbtService->handle($request->validated());

      session()->flash('success', 'rbt created Successfully');

      return redirect('rbt/'.$request->content_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
     $contents=  $this->contentRepository->whereId($id)->get();
     return view('rbt.index',compact('contents'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
      $rbt       = $this->rbtRepository->findOrFail($id);
      $contents  = $this->contentRepository->where('content_type_id', ContentTypes::AUDIO)->get();
      $operators = $this->operatorRepository->all();

      return view('rbt.form',compact('rbt','contents','operators'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RbtRequest $request, $id)
    {
        $this->rbtService->handle($request->validated(), $id);

        session()->flash('success', 'RbtCode Update Successfully');

        return redirect('rbt/'.$request->content_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,Request $request)
    {
      $rbt = $this->rbtRepository->findOrFail($id);
      $rbt->delete();
      session()->flash('success', 'RbtCode Delete Successfully');
      return back();
    }
}
