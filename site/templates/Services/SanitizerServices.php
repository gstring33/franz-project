<?php

namespace App\Services;

use ProcessWire\ProcessWire;
use ProcessWire\WireException;

class SanitizerServices
{
    private $sanitizer;

    private $log;

    private $error = [];

    const ERROR_FORMAT = 'Das eingegebene Format ist nicht korrekt';

    public function __construct()
    {
        $wire = ProcessWire::getCurrentInstance();
        $this->sanitizer = $wire->sanitizer;
        $this->log = $wire->log;
    }

    public function sanitizeContactData(array $data)
    {
        try{
            if (isset($data['name']) && isset($data['email']) && isset($data['message'])) {
                $sanitizedName = $this->sanitizer->text($data['name']);
                if (!$sanitizedName) {
                    return $this->registerError('name', self::ERROR_FORMAT);
                }

                $sanitizedEmail = $this->sanitizer->email($data['email']);
                if (!$sanitizedEmail) {
                    return $this->registerError('email', self::ERROR_FORMAT);
                }

                $sanitizedMessage = $this->sanitizer->text($data['message']);
                if (!$sanitizedMessage) {
                    return $this->registerError('message', self::ERROR_FORMAT);
                }

                return [
                    'email' => $sanitizedEmail,
                    'message' => $sanitizedMessage,
                    'name' => $sanitizedName
                ];

            } else {
                throw new WireException('The contact can not be stored because a field is missing');
            }
        } catch (WireException $exception) {
            $this->log->error('An error occured in ' . $exception->getFile() . ': ' . $exception->getMessage());
        }
    }

    private function registerError(string $field, string $message) {
        $this->error['error'] = [
            'field' => $field,
            'message' => $message
        ];

        return $this->error;
    }
}