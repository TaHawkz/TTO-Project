<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\DisclosureDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function download(DisclosureDocument $document): StreamedResponse
    {
        $user       = Auth::user();
        $disclosure = $document->disclosure;

        abort_unless(
            $user->canReviewDisclosures() || $disclosure->submitted_by === $user->id,
            403
        );
        abort_unless(Storage::disk('private')->exists($document->path), 404);

        return Storage::disk('private')->download($document->path, $document->original_name);
    }
}
