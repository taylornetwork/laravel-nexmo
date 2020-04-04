<?php


namespace TaylorNetwork\LaravelNexmo\Contracts;


interface BuildsNccoActions
{
    /**
     * Add the record action to the NCCO stack
     *
     * @param array $options
     * @return $this
     */
    public function record(array $options = []): self;

    /**
     * Add the conversation action to the NCCO stack
     *
     * @param string $name
     * @param array $options
     * @return $this
     */
    public function conversation(string $name, array $options = []): self;

    /**
     * Add the connect action to the NCCO stack
     *
     * @param array $endpoint
     * @param array $options
     * @return $this
     */
    public function connect(array $endpoint, array $options = []): self;

    /**
     * Add the talk action to the NCCO stack
     *
     * @param string $text
     * @param array $options
     * @return $this
     */
    public function talk(string $text, array $options = []): self;

    /**
     * Add the stream action to the NCCO stack
     *
     * @param string $url
     * @param array $options
     * @return $this
     */
    public function stream(string $url, array $options = []): self;

    /**
     * Add the input action to the NCCO stack
     *
     * @param array $options
     * @return $this
     */
    public function input(array $options = []): self;

    /**
     * Add the notify action to the NCCO stack
     *
     * @param array $payload
     * @param array $options
     * @return $this
     */
    public function notify(array $payload, array $options = []): self;
}