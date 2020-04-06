<?php


namespace TaylorNetwork\LaravelNexmo\Contracts;


interface BuildsNcco
{
    /**
     * Get the NCCO stack
     *
     * @return array
     */
    public function getNcco(): array;

    /**
     * Same as getNcco()
     *
     * @return array
     */
    public function ncco(): array;

    /**
     * Encode the NCCO stack to JSON
     *
     * @return string
     */
    public function getJsonNcco(): string;

    /**
     * Same as getJsonNcco()
     *
     * @return string
     */
    public function json(): string;

    /**
     * Append to the NCCO stack
     *
     * @param array $data
     * @return $this
     */
    public function append(array $data): self;

    /**
     * Prepend to the NCCO stack
     *
     * @param array $data
     * @return $this
     */
    public function prepend(array $data): self;
}
