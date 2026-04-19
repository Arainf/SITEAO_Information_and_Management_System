<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function download(Request $request, Event $event)
    {
        $user          = $request->user();
        $participation = $event->participantRecord($user);

        if (! $participation || ! $participation->hasCert()) {
            abort(403, 'Certificate not yet available for this event.');
        }

        if (! $event->cert_template) {
            abort(404, 'No certificate template has been set for this event.');
        }

        $templatePath = Storage::disk('public')->path($event->cert_template);
        $image        = @imagecreatefrompng($templatePath);

        if (! $image) {
            abort(500, 'Could not load certificate template.');
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $w     = imagesx($image);
        $h     = imagesy($image);
        $color = imagecolorallocate($image, 24, 29, 38);

        $name    = $user->name;
        $dateStr = $event->event_date->format('F d, Y');
        $orgLine = 'Student Involvement Management System';

        $nameFont = 5;
        $nameX    = (int)(($w - strlen($name) * imagefontwidth($nameFont)) / 2);
        $nameY    = (int)($h * 0.45);
        imagestring($image, $nameFont, $nameX, $nameY, $name, $color);

        $dateFont = 4;
        $dateX    = (int)(($w - strlen($dateStr) * imagefontwidth($dateFont)) / 2);
        $dateY    = $nameY + imagefontheight($nameFont) + 12;
        imagestring($image, $dateFont, $dateX, $dateY, $dateStr, $color);

        $orgFont = 3;
        $orgX    = (int)(($w - strlen($orgLine) * imagefontwidth($orgFont)) / 2);
        $orgY    = (int)($h * 0.82);
        imagestring($image, $orgFont, $orgX, $orgY, $orgLine, $color);

        $filename = 'certificate-' . str($event->title)->slug() . '.png';

        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache');
        imagepng($image);
        imagedestroy($image);
        exit;
    }

    public function release(Request $request, Event $event)
    {
        $request->validate([
            'user_ids'   => ['required', 'array'],
            'user_ids.*' => ['integer'],
        ]);

        if (! $event->cert_template) {
            return back()->with('error', 'No certificate template uploaded for this event.');
        }

        EventParticipant::where('event_id', $event->id)
            ->whereIn('user_id', $request->user_ids)
            ->where('status', EventParticipant::STATUS_APPROVED)
            ->whereNull('cert_released_at')
            ->update(['cert_released_at' => now()]);

        return back()->with('success', 'Certificates released to selected participants.');
    }
}
