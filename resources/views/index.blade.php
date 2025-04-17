@extends('layouts.app')
@section('content')
<h1>Daftar Project</h1>
@if(session('success')) <p>{{ session('success') }}</p> @endif
<table>
  <thead>
    <tr>
      <th>Klien</th><th>Divisi</th><th>Deadline</th><th>Prioritas</th>
      <th>Kesulitan</th><th>Overtime</th>
    </tr>
  </thead>
  <tbody>
    @foreach($projects as $p)
    <tr>
      <td>{{ $p->nama_klien }}</td>
      <td>{{ $p->divisi }}</td>
      <td>{{ $p->deadline }}</td>
      <td>{{ $p->skala_prioritas }}</td>
      <td>{{ $p->tingkat_kesulitan }}</td>
      <td>{{ number_format($p->kemungkinan_overtime * 100, 0) }}%</td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
