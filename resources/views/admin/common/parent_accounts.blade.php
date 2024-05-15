@foreach($accounts AS $account)
    <option value="{{ $account->hashid }}">{{ $account->name }}</option>
@endforeach