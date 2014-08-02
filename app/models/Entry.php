<?php

use Rhumsaa\Uuid\Uuid;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

class Entry extends Eloquent implements StaplerableInterface {
  use EloquentTrait;

  protected $table = 'entries';
  protected $fillable = array('title', 'content', 'url', 'picture',
                              'author_name', 'author_email', 'kind',
                              'start_date', 'end_date');


  public function __construct(array $attributes = array()) {
    $this->hasAttachedFile('picture', [
        'styles' => [
          'medium' => '360x210',
          'thumb' => '100x100'
        ]
    ]);
    parent::__construct($attributes);
  }

  public function votes() {
    return $this->hasMany('Vote');
  }

  public function hasVoted($user) {
    return $this->votes()->where('user_id', '=', $user->id)->count() > 0;
  }

  public function voteUp($user) {
    if(!$this->hasVoted($user)){
      $vote = new Vote([ 'user_id' => $user->id, 'up' => true ]);
      $this->votes()->save($vote);
    }
  }

  public function voteDown($user) {
    if(!$this->hasVoted($user)){
      $vote = new Vote([ 'user_id' => $user->id, 'up' => false ]);
      $this->votes()->save($vote);
    }
  }

  public function authorUrl() {
    return URL::route('entries.showAsAuthor', array($this->token));
  }

  public function url() {
    return URL::route("entries.show", array($this->id));
  }

  public function asJson() {
    return [
      'id'          => $this->id,
      'title'       => $this->title,
      'content'     => $this->content,
      'author_name' => $this->author_name,
      'kind'        => $this->kind,
      'path'        => $this->url(),
      'picture_url' => $this->picture->url('medium')
    ];
  }
}

Entry::creating(function($entry){
  $entry->token = Uuid::uuid4();
});
