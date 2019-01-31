<?php

namespace \ToBeAgile;

class PostOffice
{

    protected $emails = [];

    public function sendEmail(string $address, string $message)
    {
        $this->emails[] = [$address, $message];
    }

    public function findEmail(string $address, string $message): string
    {
        foreach ($this->emails as $email) {
            if ($email[0] === $address && $email[1] === $message) {
                return $message;
            }
        }
        throw new \Exception('Mail not found.');
    }

    public function doesLogContain(string $address, string $message): bool
    {
        try {
            $this->findEmail($address, $message);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

}