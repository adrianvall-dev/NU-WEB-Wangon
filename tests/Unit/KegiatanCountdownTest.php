<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use Tests\TestCase;

class KegiatanCountdownTest extends TestCase
{
    public function test_is_selesai_returns_true_for_past_activity(): void
    {
        $kegiatan = new Kegiatan([
            'event_date' => now()->subDay(),
            'start_time' => '08:00:00',
            'status' => 'published',
        ]);

        $this->assertTrue($kegiatan->isSelesai());
    }

    public function test_is_selesai_returns_false_for_future_activity(): void
    {
        $kegiatan = new Kegiatan([
            'event_date' => now()->addDays(2),
            'start_time' => '08:00:00',
            'status' => 'published',
        ]);

        $this->assertFalse($kegiatan->isSelesai());
    }

    public function test_is_selesai_returns_false_for_draft_activity(): void
    {
        $kegiatan = new Kegiatan([
            'event_date' => now()->subDay(),
            'start_time' => '08:00:00',
            'status' => 'draft',
        ]);

        $this->assertFalse($kegiatan->isSelesai());
    }
}
