<?php

namespace App\Support;

class SessionNotes
{
    public static function parseToday(): array
    {
        $date = now()->format('Y-m-d');
        $dir = base_path('docs/SESSION_NOTES');
        $result = [
            'yapilanlar' => [],
            'yapilacaklar' => [],
        ];

        if (!is_dir($dir)) {
            return $result;
        }

        $files = glob($dir . DIRECTORY_SEPARATOR . $date . '-*.md');
        if (!$files) return $result;

        $content = @file_get_contents($files[0]);
        if ($content === false) return $result;

        $sections = self::splitSections($content);

        if (isset($sections['Yapılanlar'])) {
            foreach (self::parseBullets($sections['Yapılanlar']) as $line) {
                $result['yapilanlar'][] = [
                    'ad' => $line,
                    'aciklama' => '',
                    'tarih' => now()->format('d.m.Y'),
                    'teknoloji' => '',
                    'link' => null,
                    'test_link' => null,
                ];
            }
        }

        foreach ([
            'Açık Maddeler / Sonraki Adımlar',
            'Açık Maddeler',
            'Sonraki Adımlar',
        ] as $key) {
            if (isset($sections[$key])) {
                foreach (self::parseBullets($sections[$key]) as $line) {
                    $result['yapilacaklar'][] = [
                        'ad' => $line,
                        'aciklama' => '',
                        'tahmini_sure' => '—',
                        'teknoloji' => '',
                        'oncelik' => 'orta',
                    ];
                }
                break;
            }
        }

        return $result;
    }

    private static function splitSections(string $md): array
    {
        $sections = [];
        $current = null;
        $lines = preg_split('/\r?\n/', $md);
        foreach ($lines as $line) {
            if (preg_match('/^##\s+(.+)/', $line, $m)) {
                $current = trim($m[1]);
                $sections[$current] = '';
                continue;
            }
            if ($current !== null) {
                $sections[$current] .= $line."\n";
            }
        }
        return $sections;
    }

    private static function parseBullets(string $section): array
    {
        $items = [];
        foreach (preg_split('/\r?\n/', $section) as $line) {
            $line = trim($line);
            if ($line === '' || strpos($line, '---') === 0) continue;
            if (preg_match('/^[-\*]\s+(.*)$/', $line, $m)) {
                $items[] = trim($m[1]);
            }
        }
        return $items;
    }
}
