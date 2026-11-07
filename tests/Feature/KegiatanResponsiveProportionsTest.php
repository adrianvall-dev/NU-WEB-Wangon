<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KegiatanResponsiveProportionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_index_cards_have_responsive_proportions_and_accessible_touch_targets(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $eventDate = now($tz)->addDays(5)->toDateString();

        $kegiatan = Kegiatan::create([
            'title' => 'Pengajian Akbar Ranting NU',
            'kategori' => 'fatayat',
            'description' => 'Pengajian rutin sekaligus silaturahmi kader Muslimat dan Fatayat NU se-Kecamatan Wangon.',
            'event_date' => $eventDate,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'location' => 'Masjid Jami Baiturrahim Wangon',
            'status' => 'published',
        ]);

        $response = $this->get(route('kegiatan.index'));

        $response->assertStatus(200);

        // 1. Proportional card padding (mobile/tablet adjusted while locking desktop lg:p-5)
        $response->assertSee('p-3.5 sm:p-4.5 lg:p-5 border-2 border-brand-forest shadow-solid-md', false);

        // 2. Thumbnail container with optimized height and aspect ratio
        $response->assertSee('h-52 sm:h-56 lg:h-48 xl:h-52 aspect-[16/10] sm:aspect-auto rounded-2xl overflow-hidden', false);

        // 3. Metadata pills with breathing room
        $response->assertSee('px-2.5 sm:px-3 py-1.5 rounded-xl border border-brand-forest/25 text-xs font-bold', false);

        // 4. Strong title hierarchy and line-clamp
        $response->assertSee('text-lg sm:text-xl font-black text-brand-deep leading-snug line-clamp-2 break-words', false);

        // 5. Accessible touch target for CTA button (>= 44px on mobile)
        $response->assertSee('min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand hover:bg-brand-forest', false);
        $response->assertSee('Pengajian Akbar Ranting NU');
    }

    public function test_kegiatan_show_has_proportional_media_countdown_and_accessible_touch_targets(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $eventDate = now($tz)->addDays(3)->toDateString();

        $kegiatan = Kegiatan::create([
            'title' => 'Musyawarah Kerja Wakil Cabang NU',
            'kategori' => 'ansor',
            'description' => 'Rapat pleno dan koordinasi seluruh banom NU di aula pusat.',
            'event_date' => $eventDate,
            'start_time' => '13:00:00',
            'end_time' => '17:00:00',
            'location' => 'Aula Gedung Bersama MWC NU Wangon',
            'latitude' => -7.531234,
            'longitude' => 109.051234,
            'status' => 'published',
        ]);

        // Create related kegiatan for Agenda Lainnya check
        Kegiatan::create([
            'title' => 'Lailatul Ijtima Rutin',
            'kategori' => 'fatayat',
            'description' => 'Dzikir dan tahlil bersama jajaran pengurus.',
            'event_date' => now($tz)->addDays(7)->toDateString(),
            'start_time' => '19:30:00',
            'location' => 'Masjid Nurul Huda Wangon',
            'status' => 'published',
        ]);

        $response = $this->get(route('kegiatan.show', $kegiatan->id));

        $response->assertStatus(200);

        // 1. Page container padding for balanced breathing room
        $response->assertSee('py-6 sm:py-10 px-3.5 sm:px-6 lg:px-8', false);

        // 2. Countdown timer preserve IDs and responsive digit boxes
        $response->assertSee('id="countdown-card"', false);
        $response->assertSee('id="days"', false);
        $response->assertSee('id="hours"', false);
        $response->assertSee('id="minutes"', false);
        $response->assertSee('id="seconds"', false);
        $response->assertSee('min-w-[48px] xs:min-w-[54px] sm:min-w-[70px]', false);

        // 3. Description card spacious padding
        $response->assertSee('p-4 sm:p-6 lg:p-8 shadow-solid-sm space-y-4', false);

        // 4. Interactive map container with fixed min-height
        $response->assertSee('id="publicKegiatanMap"', false);
        $response->assertSee('style="height: 380px; min-height: 320px;"', false);

        // 5. Google Maps navigation button touch target >= 44px
        $response->assertSee('min-h-[44px] lg:min-h-0 rounded-xl bg-brand-sand hover:bg-brand-forest hover:text-white', false);

        // 6. Share buttons touch target >= 44px
        $response->assertSee('min-h-[44px] rounded-xl text-xs font-black uppercase tracking-wider flex items-center justify-center gap-2', false);

        // 7. Agenda Lainnya card proportions and touch target
        $response->assertSee('p-3.5 sm:p-4.5 lg:p-4.5 hover:-translate-y-1 hover:shadow-solid-md', false);
        $response->assertSee('min-h-[44px] inline-flex items-center py-1', false);
    }

    public function test_homepage_agenda_section_cards_have_mobile_adapted_padding_and_touch_targets(): void
    {
        $tz = config('app.timezone', 'Asia/Jakarta');
        $eventDate = now($tz)->addDays(2)->toDateString();

        Kegiatan::create([
            'title' => 'Kajian Rutin Ahad Pagi',
            'kategori' => 'muslimat',
            'description' => 'Kajian kitab kuning tafsir Jalalain bersama santri dan masyarakat umum.',
            'event_date' => $eventDate,
            'start_time' => '06:00:00',
            'location' => 'Kompleks Pesantren MWC NU Wangon',
            'status' => 'published',
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);

        // 1. Mobile-adapted card content padding
        $response->assertSee('p-4.5 sm:p-5 md:p-6', false);

        // 2. Mobile-adapted card footer padding
        $response->assertSee('px-4.5 sm:px-5 md:px-6 py-3.5 md:py-4 border-t-2 border-brand-sand', false);

        // 3. Accessible touch target for Detail Agenda
        $response->assertSee('min-h-[44px] flex items-center', false);
        $response->assertSee('w-9 h-9 md:w-8 md:h-8 rounded-lg bg-brand-lightmatcha', false);
    }
}
