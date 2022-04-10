<x-admin.layout :title="$title" :section="$section" :model="$model::getModelNameIdx()">

    <style>
        .dataTable .btn-link {
            margin: 0px !important;
            color: #5A738E;
            border-width: 0px;
            padding: 0px;
        }

        .btn-group .btn {
            border-color: silver;
        }

        .dataTable form {
            display: inline;
            margin: 0px;
            padding: 0px;
        }
    </style>

    @if ($items->total())

    <table class="table dataTable table-bordered table-striped table-hover">
        <thead>
        <tr>
            <th class="text-center">&nbsp;</th>
            @foreach ($fields as $field_key => $field_data)
                @if ($field_data['customTableView'])
                <th>
                    {{ $field_data['name'] }}
                </th>
                @else
                    <th class="sorting{{ $sort_key == $field_key ? ($sort_type == 'asc' ? '_asc' : '_desc') : '' }}">
                        <a class="{{ $sort_key == $field_key ? ($sort_type == 'asc' ? 'asc' : 'desc') : '' }}"
                           href="{{ url()->current() }}?sort_key={{ $field_key }}&sort_type={{ $sort_key == $field_key && $sort_type == 'asc' ? 'desc' : 'asc' }}">
                            {{ $field_data['name'] }}
                        </a>
                    </th>
                @endif
            @endforeach
        </thead>
        <tbody>
        @foreach ($items as $item)
        <tr data-key="{{ $item->id }}">
            <td class="text-center" style="vertical-align: middle">
                @if ($deleted)
                    <form method="post" action="{{ url()->current() }}/restore/{{ $item->id }}">
                        @csrf
                        <button type="submit" class="btn btn-link" title="{{ __('Restore') }}">
                            <i class="fa fa-refresh"></i>
                        </button>
                    </form>
                    <form method="post"
                          action="{{ url()->current() }}/delete/{{ $item->id }}"
                          onsubmit="if (!confirm('{{ __('Are you sure you want to delete this item?') }}')) return false">
                        @csrf
                        <button type="submit" class="btn btn-link" title="{{ __('Delete') }}">
                            <i class="fa fa-remove"></i>
                        </button>
                    </form>
                @else
                    @foreach ($actions as $action => $action_data)
                        @if ($action_data['type'] == 'get')
                            <a href="{{ url()->current() }}/{{ $action }}/{{ $item->id }}" title="{{ __($action_data['title']) }}">
                                <i class="fa fa-{{ $action_data['icon'] }}"></i>
                            </a>
                        @elseif ($action_data['type'] == 'post')
                            <form method="post"
                                  action="{{ url()->current() }}/{{ $action }}/{{ $item->id }}"
                                  @if (isset($action_data['confirm']))
                                  onsubmit="if (!confirm('{{ __($action_data['confirm']) }}')) return false"
                                  @endif >
                                @csrf
                                <button type="submit" class="btn btn-link" title="{{ __($action_data['title']) }}">
                                    <i class="fa fa-{{ $action_data['icon'] }}"></i>
                                </button>
                            </form>
                        @endif
                    @endforeach
                @endif
            </td>
            @foreach ($fields as $field_key => $field_name)
                <td>{!! $item->tableValue($field_key, $fields) !!}</td>
            @endforeach
        </tr>
        @endforeach
        </tbody>
    </table>

    @else

    <div class="well text-center"><h3>{{ __('No items found') }}</h3><div></div></div>

    @endif

    @if (!$deleted)
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a class="btn btn-round btn-success" href="{{ url()->current() }}/edit">
                    <i class="fa fa-plus"></i> {{ __('Add') }}
                </a>
            </div>
        </div>
    </div>
    @endif

    @if ($soft_delete)
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-12">
            <div class="btn-group" role="group">
                <a class="btn btn-round @if (!$deleted) btn-success @else btn-default @endif" href="{{ url()->current() }}">
                    <i class="fa fa-check"></i> {{ __('Active records') }}
                </a>
                <a class="btn btn-round @if ($deleted) btn-success @else btn-default @endif" href="{{ url()->current() }}?deleted=1">
                    <i class="fa fa-trash"></i> {{ __('Deleted records') }}
                </a>
            </div>
        </div>
    </div>
    @endif

</x-admin.layout>
