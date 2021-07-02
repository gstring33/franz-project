<?php

namespace App\Services;

use ProcessWire\ProcessWire;
use ProcessWire\WireException;

class SecurityServices
{
    private $sanitizer;

    private $log;

    private $errors = [];

    const ERROR_FORMAT = 'Das eingegebene Format ist nicht korrekt';
    const ERROR_STATUS = 'error';
    const SUCCESS_STATUS = 'success';

    public function __construct()
    {
        $wire = ProcessWire::getCurrentInstance();
        $this->sanitizer = $wire->sanitizer;
        $this->log = $wire->log;
    }

    /**
     * @param array $data
     * @return array|bool
     */
    public function sanitizeContactData(array $data)
    {
        try{
            if (isset($data['name']) &&
                isset($data['email']) &&
                isset($data['message']) &&
                isset($data['pdm_name']) &&
                isset($data['pdm_email'])
            ) {
                $sanitizedName = $this->sanitizer->text($data['name']);
                if (!$sanitizedName) {
                    $this->registerError('name', self::ERROR_FORMAT);
                }

                $sanitizedEmail = $this->sanitizer->email($data['email']);
                if (!$sanitizedEmail) {
                    $this->registerError('email', self::ERROR_FORMAT);
                }

                $sanitizedMessage = $this->sanitizer->text($data['message']);
                if (!$sanitizedMessage) {
                    $this->registerError('message', self::ERROR_FORMAT);
                }

                if (isset($this->errors['errors']) && count($this->errors['errors']) > 0) {
                    return $this->errors;
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

    /**
     * @param array $fields
     * @return bool
     */
    public function isSpam(array $fields): bool
    {
        return ($fields['pdm_name'] !== '' || $fields['pdm_email'] !== '');
    }

    /**
     * @param string $field
     * @param string $message
     */
    private function registerError(string $field, string $message): void
    {
        $this->errors['errors'][] = [
            'field' => $field,
            'message' => $message
        ];
    }
}