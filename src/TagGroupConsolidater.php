<?php
namespace NShiell\FuturePlc\DataParser;

class TagGroupConsolidater
{
    private const BAD_TAGS = 'bad_tags';
    public function __construct(private readonly iterable $tagsByGroup) {}

    private function getDestinationGroupNameForTag(
        array $tagGroupsForRecord,
        string $recordTag
    ): ?string {
        foreach ($this->tagsByGroup as $groupName => $tagsAllowedInGroup) {
            if ($tagGroupsForRecord[$groupName]) {
                continue;
            }

            if (in_array($recordTag, $tagsAllowedInGroup)) {
                return $groupName;
            }
        }

        return null;
    }

    public function createTagGroups(
        array $recordTags,
        string $badTagSeperator = '|'
    ): array {
        sort($recordTags);
        $tagGroups = array_fill_keys(array_keys($this->tagsByGroup), '');
        $badTags = [];

        foreach ($recordTags as $recordTag) {
            $destinationGroupNameForTag = $this->getDestinationGroupNameForTag(
                $tagGroups,
                $recordTag
            );

            if ($destinationGroupNameForTag) {
                $tagGroups[$destinationGroupNameForTag] = $recordTag;
            } else {
                $badTags[] = $recordTag;
            }
        }

        $tagGroups[self::BAD_TAGS] = implode($badTagSeperator, $badTags);

        return $tagGroups;
    }
}