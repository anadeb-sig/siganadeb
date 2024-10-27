@extends('layouts.app')

@section('content')
    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ trans('signers.model_plural') }}</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('signers.signer.create') }}" class="btn btn-success" title="{{ trans('signers.create') }}">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($signers) == 0)
            <div class="panel-body text-center">
                <h4>{{ trans('signers.none_available') }}</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('signers.contrat_id') }}</th>
                            <th>{{ trans('signers.ouvrage_id') }}</th>
                            <th>{{ trans('signers.entreprise_id') }}</th>
                            <th>{{ trans('signers.date_sign') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($signers as $signer)
                        <tr>
                            <td>{{ optional($signer->Contrat)->date_debut }}</td>
                            <td>{{ optional($signer->Ouvrage)->nom_ouvrage }}</td>
                            <td>{{ optional($signer->Entreprise)->nom_entrep }}</td>
                            <td>{{ $signer->date_sign }}</td>

                            <td>

                                <form method="POST" action="{!! route('signers.signer.destroy', $signer->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('signers.signer.show', $signer->id ) }}" class="btn btn-info" title="{{ trans('signers.show') }}">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('signers.signer.edit', $signer->id ) }}" class="btn btn-primary" title="{{ trans('signers.edit') }}">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('signers.delete') }}" onclick="return confirm(&quot;{{ trans('signers.confirm_delete') }}&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $signers->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection