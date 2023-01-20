<?php

namespace App\Services\formats;

use App\Contracts\LogInterface;
use Illuminate\Support\Carbon;

class LogFormat implements LogInterface
{
    /**
     * __construct
     *
     * @param  string  $text
     */
    public function __construct(public string $text)
    {
    }

    /**
     * formatted
     *
     * @return array
     */
    public function formatted(): array
    {
        $item = explode(' ', $this->text);

        unset($item[1]);

        $item = array_combine($this->columns(), $item);

        [$day, $month, $year, $hour, $minutes, $seconds] = sscanf($item['created_at'], "[%d/%3s/%d:%d:%d:%d]");

        $month = Carbon::parse($month)->format('m');

        $item['created_at'] = "$year-$month-$day $hour:$minutes:$seconds";

        return array_map('trim', $item);
    }

    /**
     * columns
     *
     * @return string[]
     */
    public function columns(): array
    {
        return [
            'name',
            'created_at',
            'method',
            'url',
            'protocol',
            'status'
        ];
    }
}
