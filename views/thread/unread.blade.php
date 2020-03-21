@extends ('forum::master', ['thread' => null, 'breadcrumbs_append' => [trans('forum::threads.unread_updated')]])

@section ('content')
    <div id="new-posts">
        <h2>{{ trans('forum::threads.unread_updated') }}</h2>

        @if (! $threads->isEmpty())
            <div class="threads list-group my-3 shadow-sm">
                @foreach ($threads as $thread)
                    @include ('forum::thread.partials.list')
                @endforeach
            </div>

            @can ('markNewThreadsAsRead')
                <div class="text-center">
                    <form action="{{ Forum::route('unread.mark-read') }}" method="POST" onsubmit="return confirm('{{ trans('forum::general.generic_confirm') }}')">
                        @csrf
                        @method('patch')

                        <button class="btn btn-primary px-5">
                            <i data-feather="book"></i> {{ trans('forum::general.mark_read') }}
                        </button>
                    </form>
                </div>
            @endcan
        @else
            <div class="card my-3">
                <div class="card-body text-center text-muted">
                    {{ trans('forum::threads.none_found') }}
                </div>
            </div>
        @endif
    </div>
@stop
