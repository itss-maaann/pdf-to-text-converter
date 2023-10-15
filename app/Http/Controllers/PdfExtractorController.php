<?php

namespace App\Http\Controllers;

use Spatie\PdfToText\Pdf;
use Illuminate\Http\Request;
use App\Factories\TemplateParserFactory;

class PdfExtractorController extends Controller
{
    protected $files;

    public function index()
    {
        return view('upload-pdf');
    }

    public function parsePdf(Request $request)
    {
        $request->validate([
            'pdf' => 'required|mimes:pdf',
            'template' => 'required|string:'
        ]);

        $pdftotextPath = 'c:/Program Files/Git/mingw64/bin/pdftotext';
        // $pdftotextPath = base_path('bin/pdftotext.exe');

        $pdfFile = $request->file('pdf');

        $text = Pdf::getText($pdfFile->getRealPath(), $pdftotextPath);

        $lines = explode("\n", $text);

        $templateParser = TemplateParserFactory::createParser($request->input('template'), $lines);

        $data = $templateParser->parse($lines);

        return view('upload-pdf', ['data' => $data]);
    }

}
