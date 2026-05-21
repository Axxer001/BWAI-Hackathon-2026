<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TruckApproachingMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $residentName;
    public string $pointName;
    public int    $etaMinutes;
    public string $barangayName;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $residentName,
        string $pointName,
        int    $etaMinutes,
        string $barangayName
    ) {
        $this->residentName  = $residentName;
        $this->pointName     = $pointName;
        $this->etaMinutes    = $etaMinutes;
        $this->barangayName  = $barangayName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->etaMinutes === 0
                ? '🚛 Garbage Truck is NOW at your Collection Point — ' . $this->pointName
                : '🚛 Garbage Truck Approaching in ' . $this->etaMinutes . ' min — ' . $this->pointName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.truck-approaching',
        );
    }
}
