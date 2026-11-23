<?php

namespace Tests\Unit;

use App\Services\StudentTimeline;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class StudentTimelineTest extends TestCase
{
    public function test_events_are_newest_first_and_undated_are_dropped()
    {
        $mk = fn ($d, $t) => ['at' => $d ? Carbon::parse($d) : null, 'icon' => 'x', 'colour' => 'y', 'title' => $t, 'detail' => ''];
        $out = (new StudentTimeline())->compose([
            $mk('2026-01-02', 'b'), $mk(null, 'none'), $mk('2026-03-01', 'c'), $mk('2026-01-01', 'a'),
        ]);
        $this->assertSame(['c', 'b', 'a'], array_column($out, 'title'));
    }

    public function test_discord_invite_default()
    {
        $this->assertSame('https://discord.gg/nQKAFpf98J', (include __DIR__.'/../../config/training.php')['discord_invite']);
    }
}
