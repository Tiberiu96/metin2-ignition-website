<?php

namespace Database\Seeders;

use App\Enums\EventCategory;
use App\Models\Web\GameEvent;
use Illuminate\Database\Seeder;

class GameEventsSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            // ── Holidays ─────────────────────────────────────────────
            [
                'name' => 'Easter Event',
                'slug' => 'easter',
                'description' => 'Special Easter metinstones (8041-8050) spawn on maps. Players collect eggs (50160-50179) and exchange them with the Easter Rabbit NPC for rewards.',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'easter_drop',
                'params' => [
                    'easter_rabbit' => 1,
                    'easter_spawn_chance' => 5,
                    'easter_logging' => 0,
                ],
            ],
            [
                'name' => 'Halloween Hair Event',
                'slug' => 'halloween_hair',
                'description' => 'Pumpkin Head NPC (33008) spawns on main maps. Players bring a coupon (30323) + pumpkin (30321) to receive a special Halloween hairstyle with a 7-day buff.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'halloween_hair',
                'params' => [],
            ],
            [
                'name' => 'Christmas Tree',
                'slug' => 'xmas_tree',
                'description' => 'Interactive Christmas Tree — players insert up to 3 stockings (item 50010) and receive random gifts from a special item group. Requires level 10+.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'xmas_tree',
                'params' => [],
            ],
            [
                'name' => 'Christmas Effects',
                'slug' => 'xmas_effects',
                'description' => 'Visual Christmas effects: snowfall (xmas_snow), fireworks/explosions (xmas_boom), Santa Claus spawns on maps (xmas_santa). Can be enabled independently.',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'xmas_snow',
                'params' => [
                    'xmas_boom' => 1,
                    'xmas_santa' => 1,
                ],
            ],
            [
                'name' => 'New Christmas 2012',
                'slug' => 'new_xmas_event',
                'description' => 'Extended Christmas event — Santa NPC (20126) spawns on main maps (1, 21, 41) with sub-quests for eggnog and stockings.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'new_xmas_event',
                'params' => [],
            ],
            [
                'name' => 'Ramadan Event',
                'slug' => 'ramadan',
                'description' => 'Iftar collection event: players gather bread (30315) → baklava (50183) → plate (30316) → iftar (30317) → fruit (30318). Exchange with 5 beggar NPCs for rewards and a mount (71131-71134).',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'ramadan_drop',
                'params' => [
                    'ramadan_reward' => 1,
                ],
            ],
            [
                'name' => 'Harvest Festival',
                'slug' => 'harvest_festival',
                'description' => 'Chuseok harvest festival. Players kill level-appropriate mobs to collect glutinous rice, exchangeable at the Historian NPC (20087) for special items.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'harvest_festival',
                'params' => [],
            ],

            // ── Drop Events ───────────────────────────────────────────
            [
                'name' => 'Mystery Box Drop',
                'slug' => 'mystery_box',
                'description' => 'All mobs across the world have a configurable chance to drop a mystery box. Drop rate multiplier scales with mob type (bosses drop more).',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'mystery_box_drop',
                'params' => [
                    'mystery_box_prob' => 1,
                    'mystery_box_vnum' => 0,
                    'mystery_box_logging' => 0,
                ],
            ],
            [
                'name' => 'Dragon Soul Drop',
                'slug' => 'ds_drop',
                'description' => 'Controls the Dragon Soul stone drop rate from mobs. The value is a multiplier — higher values yield more Dragon Soul stones.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'ds_drop',
                'params' => [
                    'ds_drop' => 10,
                ],
            ],

            // ── PvP / War ─────────────────────────────────────────────
            [
                'name' => 'Arena Manager',
                'slug' => 'arena',
                'description' => 'Controls access to the 1v1 PvP arena. NOTE: arena_close = 0 means OPEN, arena_close = 1 means CLOSED.',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'arena_close',
                'params' => [
                    'arena_use_min_level' => 25,
                ],
            ],
            [
                'name' => 'Threeway War',
                'slug' => 'threeway_war',
                'description' => 'Three-empire war on dedicated maps. Each empire has a pass map and a central sungzi map. The empire that kills the central bosses wins.',
                'category' => EventCategory::Complex,
                'quest_flag' => 'threeway_war',
                'params' => [
                    'threeway_war_level_min' => 85,
                    'threeway_war_level_max' => 105,
                    'threeway_war_kill_count' => 250,
                    'threeway_war_boss_count' => 5,
                    'threeway_war_dead_count' => 25,
                ],
            ],
            [
                'name' => 'Entry Event Map',
                'slug' => 'event_map',
                'description' => 'Special PvP/PvE map (map 200) with configurable access control: level range, max players, empire restriction.',
                'category' => EventCategory::Complex,
                'quest_flag' => 'event_map_active',
                'params' => [
                    'event_map_choice' => 1,
                    'event_map_player_max' => 50,
                    'event_map_empire' => 0,
                ],
            ],

            // ── Dungeon / Raid ────────────────────────────────────────
            [
                'name' => 'Dragon Lair',
                'slug' => 'dragon_lair',
                'description' => 'Group raid on Dragon Boss (vnum 2493) on map 208. Requires a password and God Symbols x3 to enter. 5-minute entry window after first player. Max 1 hour.',
                'category' => EventCategory::Complex,
                'quest_flag' => 'dragon_lair_alive',
                'params' => [
                    'dragon_lair_password' => 12345,
                ],
            ],
            [
                'name' => 'Flame Dungeon Event',
                'slug' => 'flame_dungeon',
                'description' => 'Daily quest in Flame Dungeon (map 62) for level 90+ players. Kill 100 specific mobs to receive a teleport item (71173) and 3 pass items (71174). Daily reset.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'w21open_event',
                'params' => [],
            ],

            // ── Special / Complex ─────────────────────────────────────
            [
                'name' => 'OX Event (Quiz Arena)',
                'slug' => 'ox_event',
                'description' => 'True/False quiz event on map 113. A GM hosts questions; wrong answers eliminate players. Requires active GM participation. Control NPC: 20358 (GM only).',
                'category' => EventCategory::Complex,
                'quest_flag' => 'oxevent_status',
                'params' => [
                    'ox_map_level_min' => 15,
                    'ox_map_level_max' => 39,
                    'ox_map_player_max' => 100,
                ],
            ],
            [
                'name' => 'Mob Invasion',
                'slug' => 'mob_invasion',
                'description' => 'Wave-based mob invasion on main maps. 3 progressive waves (weak → aggressive → boss). Each wave duration is configurable.',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'mob_invasion',
                'params' => [
                    'mob_invasion_type' => 1,
                    'mob_invasion_wave' => 0,
                    'mob_invasion_wave_duration' => 1,
                    'mob_invasion_target_map' => 1,
                ],
            ],
            [
                'name' => 'Change Empire Event',
                'slug' => 'change_empire',
                'description' => 'Allows players to change their empire once. Costs 500,000 yang, 7-day cooldown, one-time only. NPC: 20090.',
                'category' => EventCategory::Simple,
                'quest_flag' => 'c_e',
                'params' => [],
            ],
            [
                'name' => 'In-Game Message',
                'slug' => 'ingame_message',
                'description' => 'Sends letters to players on login with promotion, new item, or security messages. Can optionally include a gift item.',
                'category' => EventCategory::MultiParam,
                'quest_flag' => 'message_type',
                'params' => [
                    'message_id' => 1,
                    'message_item_vnum' => 0,
                    'message_gift_vnum' => 0,
                    'message_duration' => 24,
                ],
            ],
        ];

        foreach ($events as $data) {
            GameEvent::query()->updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );
        }
    }
}
