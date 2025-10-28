<?php

namespace App\Services;

class ProfanityFilter
{
    protected array $badWords;

    public function __construct()
    {
        // Load the JSON file with bad words
        $path = storage_path('app/badwords.json');
        $data = json_decode(file_get_contents($path), true);

        // Ensure we have a lowercase array of bad words
        $this->badWords = array_map('strtolower', $data['badwords'] ?? []);
    }

    /**
     * Check if text contains bad words, including obfuscated ones (like f.u.c.k or f u c k).
     */
    public function containsBadWords(string $text): bool
    {
        $text = strtolower($text);

        foreach ($this->badWords as $word) {
            // Break each word into letters and allow spaces or symbols between
            $letters = str_split($word);
            $escaped = array_map(fn($l) => preg_quote($l, '/'), $letters);

            // Allow any number of non-letter characters (spaces, symbols, underscores, etc.)
            $pattern = '/' . implode('[\W_]*', $escaped) . '/i';

            if (preg_match($pattern, $text)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replace bad words with asterisks (*), catching obfuscated variants.
     */
    public function censor(string $text): string
    {
        foreach ($this->badWords as $word) {
            $letters = str_split($word);
            $escaped = array_map(fn($l) => preg_quote($l, '/'), $letters);
            $pattern = '/' . implode('[\W_]*', $escaped) . '/i';

            $text = preg_replace_callback($pattern, function ($matches) {
                return str_repeat('*', strlen($matches[0]));
            }, $text);
        }

        return $text;
    }
}
    