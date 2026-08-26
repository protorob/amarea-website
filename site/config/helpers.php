<?php
// Auto-loaded by Kirby alongside config.php. Shared helpers for anything
// using the `tabs/layout` block-blueprint preset (background + text color
// + height) — one place for the color maps so every block resolves them
// the same way instead of each snippet redefining (and drifting from) its
// own copy.

/**
 * Resolves a block/field-holding object's Layout-tab fields into ready
 * CSS classes. Pass anything exposing backgroundType()/backgroundColor()/
 * height()/textColor() field methods (a Kirby Block is the normal case).
 */
function block_layout(object $block): array
{
    $bgColorClasses = [
        'ink'     => 'bg-ink',
        'bg'      => 'bg-bg',
        'primary' => 'bg-primary',
        'accent'  => 'bg-accent',
    ];
    $overlayClasses = [
        'ink'    => 'bg-gradient-to-t from-ink/70 via-ink/20 to-ink/40',
        'accent' => 'bg-gradient-to-t from-accent/70 via-accent/20 to-accent/40',
        'none'   => '',
    ];
    $textColorClasses = [
        'ink'   => 'text-ink',
        'white' => 'text-white',
        'bg'    => 'text-bg',
    ];
    $heightClasses = [
        'normal' => 'py-16 sm:py-20',
        'half'   => 'min-h-[50vh] py-16 flex items-center',
        'full'   => 'min-h-screen py-20 flex items-center',
    ];

    $backgroundType = $block->backgroundType()->value() ?: 'none';
    $textColor      = $block->textColor()->value() ?: 'ink';

    return [
        'backgroundType' => $backgroundType,
        'image'          => $backgroundType === 'image' ? $block->backgroundImage()->toFile() : null,
        'video'          => $backgroundType === 'video' ? $block->backgroundVideo()->toFile() : null,
        'sectionClass'   => trim(
            ($backgroundType === 'color' ? ($bgColorClasses[$block->backgroundColor()->value()] ?? $bgColorClasses['ink']) : '')
            . ' ' . ($heightClasses[$block->height()->value()] ?? $heightClasses['normal'])
        ),
        'overlayClass'   => $overlayClasses[$block->overlayColor()->value() ?: 'ink'] ?? '',
        'textColorClass' => $textColorClasses[$textColor] ?? $textColorClasses['ink'],
        // .prose (from @tailwindcss/typography) hardcodes its own dark text
        // colors unless told to invert — any light text color needs the
        // inverted (light-on-dark) prose palette, not just literal 'white'.
        'proseInvertClass' => $textColor !== 'ink' ? 'prose-invert' : '',
    ];
}
