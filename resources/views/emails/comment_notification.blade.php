@component('mail::message')
# New Comment Received

You have a new comment on your {{ $comment->commentable_type == 'App\Models\Blog' ? 'blog post' : 'profile' }}.

**Comment:**  
{{ $comment->content }}

@component('mail::button', ['url' => route('comments.show', $comment->id)])
View Comment
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
