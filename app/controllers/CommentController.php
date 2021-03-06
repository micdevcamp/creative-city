<?php

class CommentController extends BaseController {
  public function store() {
    $input = Input::all();
    $rules = array(
      'entry_id' => 'required',
      'content'  => 'required',
    );

    $validator = Validator::make($input, $rules);
    $comment   = new Comment($input);

    $userToken = $this->getUserToken();
    $user      = User::where('token', '=', $userToken)->first();

    if($validator->fails()) {
      return Redirect::back()->withErrors($validator)
                             ->withInput();
    }
    else {
      if($user->comments()->save($comment)) {
        $comment->notifyEntryAuthor();
        return Redirect::route('entries.showAsVoter', [ $comment->entry->id ]);
      } else {
        return "Impossible d'ajouter le commentaire.";
      }
    }
  }
}
