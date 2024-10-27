@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Signer' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('signers.signer.destroy', $signer->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('signers.signer.index') }}" class="btn btn-primary" title="{{ trans('signers.show_all') }}">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('signers.signer.create') }}" class="btn btn-success" title="{{ trans('signers.create') }}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('signers.signer.edit', $signer->id ) }}" class="btn btn-primary" title="{{ trans('signers.edit') }}">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="{{ trans('signers.delete') }}" onclick="return confirm(&quot;{{ trans('signers.confirm_delete') }}?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>{{ trans('signers.contrat_id') }}</dt>
            <dd>{{ optional($signer->Contrat)->date_debut }}</dd>
            <dt>{{ trans('signers.ouvrage_id') }}</dt>
            <dd>{{ optional($signer->Ouvrage)->nom_ouvrage }}</dd>
            <dt>{{ trans('signers.entreprise_id') }}</dt>
            <dd>{{ optional($signer->Entreprise)->nom_entrep }}</dd>
            <dt>{{ trans('signers.date_sign') }}</dt>
            <dd>{{ $signer->date_sign }}</dd>
            <dt>{{ trans('signers.created_at') }}</dt>
            <dd>{{ $signer->created_at }}</dd>
            <dt>{{ trans('signers.updated_at') }}</dt>
            <dd>{{ $signer->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection