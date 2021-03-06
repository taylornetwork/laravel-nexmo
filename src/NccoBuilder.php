<?php


namespace TaylorNetwork\LaravelNexmo;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use TaylorNetwork\LaravelNexmo\Contracts\BuildsNcco;
use TaylorNetwork\LaravelNexmo\Contracts\BuildsNccoActions;
use TaylorNetwork\LaravelNexmo\Contracts\RespondsWithJsonNcco;
use TaylorNetwork\LaravelNexmo\Traits\ActionsHaveDefaults;
use TaylorNetwork\LaravelNexmo\Traits\SimpleJsonResponse;

class NccoBuilder implements BuildsNcco, BuildsNccoActions, RespondsWithJsonNcco
{
    use ActionsHaveDefaults, SimpleJsonResponse;

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
    public function ncco(): array
    {
        return $this->getNcco();
    }

    /**
     * @inheritDoc
     */
    public function json(): string
    {
        return $this->getJsonNcco();
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
        $options['endpoint'] = gettype(Arr::first($endpoint)) === 'array' ? $endpoint : [$endpoint];

        if(!isset($options['from'])) {
            $options['from'] = Config::get('ncco.number');
        }

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
        return $this->json();
    }

    /**
     * @inheritDoc
     */
    public function buildResponse(int $httpStatus = 200): JsonResponse
    {
        return response()->json($this->ncco(), $httpStatus);
    }


}