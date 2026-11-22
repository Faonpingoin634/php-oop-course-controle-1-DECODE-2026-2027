<?php

namespace App\Repository;

class ContactRepository {
    private const string CONTACTS_DIR = __DIR__ . '/../../var/contacts';

    public function __construct()
    {
        if (is_dir(self::CONTACTS_DIR) === false) {
            mkdir(self::CONTACTS_DIR, 0777, true);
        }
    }

    public function save(string $filename, array $contact): void
    {
        file_put_contents(
            self::CONTACTS_DIR . '/' . $filename,
            json_encode($contact, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
    }

    public function findAll(): array
    {
        $contacts = [];

        foreach (glob(self::CONTACTS_DIR . '/*.json') as $path) {
            $contact = json_decode(file_get_contents($path), true);

            if (is_array($contact)) {
                $contacts[] = $contact;
            }
        }

        return $contacts;
    }
}