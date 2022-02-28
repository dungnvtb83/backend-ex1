<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();
        if (auth()->user()) {
            $query->where('user_id', '=', auth()->user()->id);
        }
        if ($s = $request->title) {
            $query->whereRaw("title LIKE '%" . $s . "%'");
        }
        $userId= auth()->user()->id;
        $response = $query->sortable();
        if ($request->current) {
            $rowPerPage = $request->pageSize ? $request->pageSize : env('ROWS_PER_PAGE', 20);
            return $response->paginate($rowPerPage, ['*'], 'current');
        }
        return Response()->json($response->get());
    }

 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $item = $request->all();
        $item['user_id'] = auth()->user()->id;
        $article = Article::create($item);
        return Response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
        if ($article)
        {
            return Response()->json($article, 200);
        }
        return Response()->json([], 404);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $item = Article::find($id);
        if (is_null($item)) {
            return response()->json([
                "message" => __('Invalid Id')
            ], 404);
        }
        $item->update($request->all());
        return $item;
    }

    public function destroy(Request $request, $id)
    {
        $item = Article::find($id);
        if (is_null($item)) {
            return response()->json([
                "message" => __('Invalid Id')
            ], 400);
        }
        
        return response()->json(["message" => $item->delete()]);
    }
}
