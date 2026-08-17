<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class CertificateController extends Controller
{
    public function download(Attempt $attempt)
    {
        $attempt->load(['user', 'mock', 'mockStudent', 'test']);

        // Security check: allow owner user, candidate in session, or admin/teacher
        $candidateStudentId = session('mock_student_id');
        $isOwner = auth()->check() && auth()->id() === $attempt->user_id;
        $isCandidate = $candidateStudentId && (int) $candidateStudentId === (int) $attempt->mock_student_id;
        $isStaff = auth()->check() && auth()->user()->hasRole(['Admin', 'Teacher']);

        if (!$isOwner && !$isCandidate && !$isStaff) {
            abort(403, 'Ruxsat berilmagan.');
        }

        if (!$attempt->score && !$attempt->ai_score_avg) {
            return back()->with('error', 'Natijalar hali tayyor emas.');
        }

        $verifyUrl = route('certificate.verify', $attempt->id);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($verifyUrl);

        return Pdf::view('pdf.certificate', [
            'attempt' => $attempt,
            'qrCodeUrl' => $qrCodeUrl,
            'verifyUrl' => $verifyUrl,
            'certNumber' => 'MT-' . str_pad($attempt->id, 6, '0', STR_PAD_LEFT),
        ])
            ->name("certificate-{$attempt->id}.pdf")
            ->download();
    }

    public function verify(Attempt $attempt)
    {
        $attempt->load(['user', 'mock', 'mockStudent', 'test']);

        $candidateName = $attempt->mockStudent?->name ?? $attempt->user?->name ?? 'Nomzod';
        $testName = $attempt->mock?->name ?? $attempt->test?->name ?? 'Imtihon';
        $score = $attempt->score ?? $attempt->ai_score_avg ?? 0;

        return view('certificate.verify', [
            'attempt' => $attempt,
            'candidateName' => $candidateName,
            'testName' => $testName,
            'score' => $score,
            'certNumber' => 'MT-' . str_pad($attempt->id, 6, '0', STR_PAD_LEFT),
            'issueDate' => $attempt->evaluated_at ?? $attempt->finished_at ?? $attempt->created_at,
        ]);
    }
}
