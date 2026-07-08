<?php 

$bname1 = "Saravna Bavan";
$bname2= "Saravna Bavan";

echo slugify($bname1);
echo slugify($bname2);

  function slugify(
        string $text
    ): string {

        return trim(
            preg_replace(
                '/[^a-z0-9]+/i',
                '-',
                strtolower($text)
            ),
            '-'
        );
    }

