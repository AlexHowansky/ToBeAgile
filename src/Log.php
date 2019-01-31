<?php

namespace \ToBeAgile;

class Log
{

    protected $logs = [];

    public function log(string $fileName, string $message)
    {
        $this->logs[] = [$fileName, $message];
    }

    public function returnMessage(string $fileName, string $message): bool
    {
        foreach ($this->logs as $log) {
            if ($log[0] === $fileName && $log[1] === $message) {
                return $message;
            }
        }
        throw new \Exception('Log not found.');
    }

    public function findMessage(string $fileName, string $message): string
    {
        try {
            $this->returnMessage($fileName, $message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}
