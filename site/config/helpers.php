<?php
// Auto-loaded by Kirby alongside config.php. Shared helpers for anything
// using the `tabs/layout` block-blueprint preset (background + text color
// + height) — one place for this logic so every block resolves it the
// same way instead of each snippet redefining (and drifting from) its
// own copy.

// The one dark backgroundColor/textColor swatch offered in tabs/layout.yml
// — everything else in that palette is light, so this is what decides
// prose-invert below. Keep in sync with that file's `options:` hex values.
const BLOCK_LAYOUT_INK = '#1b2333';

/**
 * Resolves a block/field-holding object's Layout-tab fields (background
 * type/color/image/video, height, text color — all real hex values now
 * that backgroundColor/textColor are `color` fields, see
 * https://getkirby.com/docs/reference/panel/fields/color#options) into
 * ready inline styles + CSS classes. Pass anything exposing
 * backgroundType()/backgroundColor()/height()/textColor() field methods
 * (a Kirby Block is the normal case).
 */
function block_layout(object $block): array
{
    $overlayClasses = [
        'ink'    => 'bg-gradient-to-t from-ink/70 via-ink/20 to-ink/40',
        'accent' => 'bg-gradient-to-t from-accent/70 via-accent/20 to-accent/40',
        'none'   => '',
    ];
    $heightClasses = [
        'normal' => 'py-16 sm:py-20',
        'half'   => 'min-h-[50vh] py-16 flex items-center',
        'full'   => 'min-h-screen py-20 flex items-center',
    ];

    $backgroundType = $block->backgroundType()->value() ?: 'none';
    $textColor      = $block->textColor()->value() ?: BLOCK_LAYOUT_INK;

    return [
        'backgroundType'   => $backgroundType,
        // resize(1920): these are always full-bleed section backgrounds, so
        // cap at a sane max instead of serving whatever the editor uploaded.
        'image'            => $backgroundType === 'image' ? $block->backgroundImage()->toFile()?->resize(1920) : null,
        'video'            => $backgroundType === 'video' ? $block->backgroundVideo()->toFile() : null,
        'backgroundStyle'  => $backgroundType === 'color'
            ? 'background-color: ' . ($block->backgroundColor()->value() ?: BLOCK_LAYOUT_INK)
            : '',
        'heightClass'      => $heightClasses[$block->height()->value()] ?? $heightClasses['normal'],
        'overlayClass'     => $overlayClasses[$block->overlayColor()->value() ?: 'ink'] ?? '',
        'textStyle'        => 'color: ' . $textColor,
        // .prose (from @tailwindcss/typography) hardcodes its own dark text
        // colors unless told to invert — any light text color needs the
        // inverted (light-on-dark) prose palette, not just literal white.
        'proseInvertClass' => strtolower($textColor) !== BLOCK_LAYOUT_INK ? 'prose-invert' : '',
    ];
}
