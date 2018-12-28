<?php
function extract_md_title(string $content): string
{
    $matched = preg_match('/# ([^#\n]+)/', $content, $matches);
    if (!$matched) {
        return '';
    }
    return trim($matches[1]);
}

function elapsed(Gap\Dto\DateTime $datetime): string
{
    /*
    if (is_int($datetime)) {
        $datetime = date(DATE_ATOM, $datetime);
    }
    $datetime = new \DateTime($datetime);
     */
    $now = new \DateTime();
    $diff = $now->diff($datetime);
    if ($diff->y) {
        return $datetime->format('Y-m-d');
    }
    if ($diff->m) {
        return $datetime->format('Y-m-d');
    }
    if ($diff->d) {
        return sprintf('%dd', $diff->d);
    }
    if ($diff->h) {
        return sprintf('%dh', $diff->h);
    }
    if ($diff->i) {
        return sprintf('%dm', $diff->i);
    }
    if ($diff->s) {
        return sprintf('%ds', $diff->s);
    }

    return sprintf('just-now');
}
