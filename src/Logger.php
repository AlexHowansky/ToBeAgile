<?php

namespace ToBeAgile;

class Logger
{

    protected $logs = [];

    public function findMessage(string $fileName, string $message): bool
    {
        try {
            $this->returnMessage($fileName, $message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function log(string $fileName, string $message)
    {
        $this->logs[] = [$fileName, $message];
    }

    public function returnMessage(string $fileName, string $message): string
    {
        foreach ($this->logs as $log) {
            if ($log[0] === $fileName && $log[1] === $message) {
                return $message;
            }
        }
        throw new \Exception('Log not found.');
    }

}
