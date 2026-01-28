<?php

namespace ToBeAgile;

class Logger
{

    /**
     * Pseudo logger storage.
     *
     * @var array<array<string>>
     */
    protected array $logs = [];

    public function findMessage(string $fileName, string $message): bool
    {
        try {
            $this->returnMessage($fileName, $message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function log(string $fileName, string $message): void
    {
        file_put_contents('/tmp/log', $fileName . ' : ' . $message . "\n");
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
