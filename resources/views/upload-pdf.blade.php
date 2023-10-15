@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <h2>Upload File and Select Option</h2>
    <form method="POST" action="{{ route('parse-pdf') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="pdf" class="form-label">Choose a PDF</label>
            <input type="file" class="form-control" id="pdf" name="pdf">
            @error('pdf')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="template" class="form-label">Select a Template</label>
            <select class="form-select" id="template" name="template">
                <option value="">Select Template</option>
                <option value="direct_energy">Direct Energy</option>
                <option value="option2">Option 2</option>
                <option value="option3">Option 3</option>
            </select>
            @error('template')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    @isset($data)
    <div class="mt-5">
        <h2>Extracted Data</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                <tr>
                    <td>{{ $key }}</td>
                    <td>
                        @if (is_array($value))
                        <ul>
                            @foreach ($value as $item)
                            <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @else
                        {{ $value }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endisset
</div>
@endsection
