<?php

namespace Downmark\Parsers;

use Downmark\Blocks\Blank;
use Downmark\Blocks\Blockquote;
use Downmark\Blocks\ListItem;
use Downmark\Blocks\OrderedList;
use Downmark\Blocks\UnorderedList;
use Downmark\Enums\ListItemType;

final class Optimizer
{
    /**
     * @param  Block[]  $ast
     * @return Block[]
     */
    public function optimize(array $ast): array
    {
        $optimised = [];
        for ($i = 0; $i < count($ast); $i++) {
            $block = $ast[$i];

            if ($block instanceof Blank) {
                continue;
            }

            if ($block instanceof Blockquote) {
                $content = $block->content;
                $lineBreakPrev = false;
                while (isset($ast[$i + 1]) && ($nextBlock = $ast[$i + 1]) && $nextBlock instanceof Blockquote) {
                    if (trim($nextBlock->content) === '') {
                        $content .= "\n";
                        $lineBreakPrev = true;
                    } else {
                        $content .= ($lineBreakPrev ? '' : ' ') . $nextBlock->content;
                        $lineBreakPrev = false;
                    }

                    $i += 1;
                }
                $optimised[] = new Blockquote($content);

                continue;
            }

            if ($block instanceof ListItem) {
                $compact = [$block->content];
                while (isset($blocks[$i + 1]) && ($nextBlock = $blocks[$i + 1]) && $nextBlock instanceof ListItem && $nextBlock->type === $block->type) {
                    $content = $nextBlock->content;

                    $compact[] = $content;

                    $i += 1;
                }
                $optimised[] = match ($block->type) {
                    ListItemType::Unordered => new UnorderedList($compact),
                    ListItemType::Ordered => new OrderedList($compact)
                };

                continue;
            }

            if ($block instanceof Blank) {
                continue;
            }

            $optimised[] = $block;
        }

        return $optimised;
    }
}
