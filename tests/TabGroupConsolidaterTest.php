<?php

namespace Tests\NShiell\DiveIn;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use NShiell\FuturePlc\DataParser\TagGroupConsolidater;

class TabGroupConsolidaterTest extends TestCase
{
    private const GROUPS = [
        'group_a' => [
            'a1',
            'a2',
            'a3',
        ],
        'group_b' => [
            'b1',
            'b2',
            'b3',
        ],
        'group_c' => [
            'c1',
            'c2',
            'c3',
        ],
    ];

    private const PROVIDER = [
        [
            'Empty set',
            [],
            ['group_a' => '', 'group_b' => '', 'group_c' => '', 'bad_tags' => '']
        ],
        [
            'Bad tag handle',
            ['badness'],
            ['group_a' => '', 'group_b' => '', 'group_c' => '', 'bad_tags' => 'badness']
        ],
        [
            'put in a group',
            ['a1'],
            ['group_a' => 'a1', 'group_b' => '', 'group_c' => '', 'bad_tags' => '']
        ],
        [
            'put second in a group',
            ['b2'],
            ['group_a' => '', 'group_b' => 'b2', 'group_c' => '', 'bad_tags' => '']
        ],
        [
            'have two groups',
            ['a1', 'a2'],
            ['group_a' => 'a1', 'group_b' => '', 'group_c' => '', 'bad_tags' => 'a2']
        ],
        [
            'have two groups - sorting',
            ['a2', 'a1'],
            ['group_a' => 'a1', 'group_b' => '', 'group_c' => '', 'bad_tags' => 'a2']
        ],
        [
            'three in a group',
            ['c2', 'a2', 'c1'],
            ['group_a' => 'a2', 'group_b' => '', 'group_c' => 'c1', 'bad_tags' => 'c2']
        ],
            [
            '2 bad in order',
            ['z123', 'a123'],
            ['group_a' => '', 'group_b' => '', 'group_c' => '', 'bad_tags' => 'a123|z123']
            ],
        [
            'have groups with multiple matching, with bad',
            ['c2', 'a2', 'badness', 'c1'],
            ['group_a' => 'a2', 'group_b' => '', 'group_c' => 'c1', 'bad_tags' => 'badness|c2']
        ]
    ];

    public static function createTagGroupsProvider(): array
    {
        return self::PROVIDER;
    }

    #[DataProvider('createTagGroupsProvider')]
    public function testCreateTagGroups(
        string $message,
        array $recordTags,
        array $expected
    ): void {
        $tagGroupConsolidater = new TagGroupConsolidater(self::GROUPS);
        $actual = $tagGroupConsolidater->createTagGroups($recordTags);
        $this->assertEquals($expected, $actual, $message);
    }
}