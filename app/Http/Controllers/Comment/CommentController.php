<?php

namespace App\Http\Controllers\Comment;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    protected $modelComment;
    protected $modelQuestion;
    protected $modelUser;
    public function __construct(
        Question $question,
        User $user,
        Comment $comment
    ){
        $this->middleware('auth');
        $this->modelQuestion = $question;
        $this->modelUser = $user;
        $this->modelComment = $comment;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $comment = new Comment;
        $comment->body = $request->get('comment_body');
        $comment->user()->associate($request->user());
        $question = Question::find($request->get('question_id'));
        $question->comments()->save($comment);

        $view1 = \View::make('partials.comment_replies')->with(['question_id'=>$question->id])->with(compact('comment'))->with(['isSolved'=>$question->is_solved]);
         $contents = (string) $view1;
         return response()->json(['success'=>$contents]);
    }

    public function replyStore(Request $request)
    {
        $reply = new Comment();
        $reply->body = $request->get('comment_body');
        $reply->user()->associate($request->user());
        $reply->parent_id = $request->get('comment_id');
        $question = Question::find($request->get('question_id'));
        $question->comments()->save($reply);

        return back();
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
        //
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
    }
}
