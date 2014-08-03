@extends('layouts/application')

@section('content')

  <div class="entries-show">
    <div class="row">
      <div class="container picture">
        <img src="<?= $entry->picture->url('large') ?>">
      </div>
    </div>

    <div class="col-lg-7">
      <h1>
        <% $entry->title %>
      </h1>

      <div class="content">
        <% $entry->content %>
      </div>
    </div>

    <div class="col-lg-5">
      @if($entry->url != '')
        <div class="link">
          <% $entry->url %>
        </div>
      @endif

      <hr/>

      <span class="created_by_title">
        @if($entry->kind == 'event')
          Événement
        @endif

        @if($entry->kind == 'article')
          Article
        @endif

        créé par :
      </span>
      <br/>
      <% $entry->author_name %>

      <div class="created_at">
        23 janvier 2015
      </div>
    </div>

    <div style="clear:both">
    </div>

    <br/><br/>
    <br/><br/>
    <br/><br/>

    @if(isset($voter))
      <div>

        @if($entry->hasVoted($voter))
          <h3>Tu as déjà voté !</h3>
        @else
          <% link_to_route('entries.voteUp',   'VoteUPPPP', [ $entry->id ]); %> <br/>
          <% link_to_route('entries.voteDown', 'VoteDOWN',  [ $entry->id ]); %>

        @endif
      </div>
    @endif


    @if(isset($is_author) && $is_author)
      <div style="background-color: yellow;">
        <h2>PRIVATE PANEL:</h2>

        Nombre total de votes: <% $votes_count %>


        @if($positiveVotes->count() > 0)
          <h3>Votes positifs</h3>
          <ul>
            @foreach ($positiveVotes->get() as $vote)
              <li><% $vote->user->name %></li>
            @endforeach
          </ul>
        @else
          <h3>Il n'y a pas de vote positif</h3>
        @endif


        @if($negativeVotes->count() > 0)
          <h3>Votes négatifs</h3>
          <ul>
            @foreach ($negativeVotes->get() as $vote)
              <li><% $vote->user->name %></li>
            @endforeach
          </ul>
        @else
          <h3>Il n'y a pas de vote négatifs</h3>
        @endif

      </div>
    @endif
  </div>
@stop
