@extends('pemeriksaans.template.template')

@section('content')
<style scoped>
    body {
        margin: 0;
        background-color: #f5f5f5;
    }

    .pdf-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .pdf-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>
<div class="pdf-container">
    <iframe src="{{ $pdfPath }}" title="PDF Viewer"></iframe>
</div>
@endsection
