<?php


namespace TaylorNetwork\LaravelNexmo;

use Illuminate\Support\Arr;
use TaylorNetwork\LaravelNexmo\Contracts\BuildsNcco;
use TaylorNetwork\LaravelNexmo\Contracts\BuildsNccoActions;
use TaylorNetwork\LaravelNexmo\Traits\ActionsHaveDefaults;

class NccoBuilder implements BuildsNcco, BuildsNccoActions
{
    use ActionsHaveDefaults;

    /**
     * The NCCO Stack
     *
     * @var array
     */
    protected $ncco = [];

    /**
     * NccoBuilder constructor.
     *
     * @param array $existingNcco
     */
    public function __construct(array $existingNcco = null)
    {
        if($existingNcco !== null) {
            $this->ncco = $existingNcco;
        }
    }

    /**
     * @inheritDoc
     */
    public function getNcco(): array
    {
        return $this->ncco;
    }

    /**
     * @inheritDoc
     */
    public function getJsonNcco(): string
    {
        return json_encode($this->ncco);
    }

    /**
     * @inheritDoc
     */
    public function append(array $data): BuildsNcco
    {
        $this->ncco[] = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function prepend(array $data): BuildsNcco
    {
        $this->ncco = Arr::prepend($this->ncco, $data);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function record(array $options = []): BuildsNccoActions
    {
        $this->addAction('record', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function conversation(string $name, array $options = []): BuildsNccoActions
    {
        $options['name'] = $name;
        $this->addAction('conversation', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function connect(array $endpoint, array $options = []): BuildsNccoActions
    {
        $options['endpoint'] = $endpoint;
        $this->addAction('connect', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function talk(string $text, array $options = []): BuildsNccoActions
    {
        $options['text'] = $text;
        $this->addAction('talk', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function stream(string $url, array $options = []): BuildsNccoActions
    {
        $options['url'] = $url;
        $this->addAction('stream', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function input(array $options = []): BuildsNccoActions
    {
        $this->addAction('input', $options);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function notify(array $payload, array $options = []): BuildsNccoActions
    {
        $options['payload'] = $payload;
        $this->addAction('notify', $options);
        return $this;
    }


    /**
     * Add action with defaults to NCCO stack
     *
     * @param string $action
     * @param array $options
     * @return $this
     */
    public function addAction(string $action, array $options = []): self
    {
        $this->append($this->getActionDefaults($action, $options));
        return $this;
    }


    /**
     * toString yields JSON NCCO
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getJsonNcco();
    }

}