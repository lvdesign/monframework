<?php
namespace Framework\Session;

use Framework\Session\SessionInterface;

class ArraySession implements SessionInterface
{

    private $session = [];
    // $this->session remplace $_SESSION[]


    /**
     * Récupère une information en Session
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        //$this->ensureStarted();
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Ajoute une information en Session
     *
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value): void
    {
        //$this->ensureStarted();
        $this->session[$key] = $value;
    }

    /**
     * Supprime une clef en session
     * @param string $key
     */
    public function delete(string $key): void
    {
        //$this->ensureStarted();
        unset($this->session[$key]);
    }
}
