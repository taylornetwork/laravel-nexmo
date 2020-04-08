# Laravel Nexmo

*Currently a work in progress so nothing is even close to guaranteed to work and will periodically fail*

## Package Status and Goals

- [x] Build NCCOs
- [ ] Implement a very simple IVR builder
- [x] Handle answering voice calls 
- [x] Route calls to correct IVR menu
- [x] Handle incoming SMS
- [x] Handle outgoing SMS
- [ ] Very simple real time SMS chat 

## Install

Using composer 

```bash
$ composer require taylornetwork/laravel-nexmo
```

### Migrate Tables

```bash
$ php artisan migrate
```

### Add your Vonage (Nexmo) information 

In your `.env` add the following lines

```
NEXMO_KEY=(Your API Key)
NEXMO_SECRET=(Your API Secret)
NEXMO_NUMBER=(Your Number)
```

### Setup your Vonage (Nexmo) Application

You will need to login to your linked application and set your webhooks.

Run `php artisan route:list` should add the following routes:

```
+---------------------------------+--------------------------------------------------------------------------------+
| URI                             | Action                                                                         |
+---------------------------------+--------------------------------------------------------------------------------+
| api/nexmo/call/answer           | TaylorNetwork\LaravelNexmo\Controllers\API\CallController@answer               |
| api/nexmo/call/ivr/{ivr}/answer | TaylorNetwork\LaravelNexmo\Controllers\API\CallController@answerWithIvr        |
| api/nexmo/event/update          | TaylorNetwork\LaravelNexmo\Controllers\API\EventController@handleEventUpdate   |
| api/nexmo/sms/inbound           | TaylorNetwork\LaravelNexmo\Controllers\API\SmsController@handleInboundMessage  |
| api/nexmo/sms/outbound          | TaylorNetwork\LaravelNexmo\Controllers\API\SmsController@handleOutboundMessage |
| api/nexmo/sms/{sms}/send        | TaylorNetwork\LaravelNexmo\Controllers\API\SmsController@send                  |

```

Assign your webhooks to the corressponding URLs.

### Prepare for Incoming Calls

You'll need to either (A) create a new IVR menu or (B) override the `answer` method.

#### Create a new IVR menu

Currently no easy way to do this other than adding the entries manually.

#### Override answer method

By default the package will look for `App\Http\Controllers\Api\CallController` 

This is customizable by publishing config (which I haven't implmented yet lol).

```php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallController extends Controller
{
	public function answer(Request $request): JsonResponse
	{
		// Must respond with a JSON response.
	}
}
```

## Usage

### NccoBuilder Class

Can be called using the `NccoBuilder` facade or by creating a new instance.

#### Action Methods

All the NCCO actions are their own method. [See NCCO Reference](https://developer.nexmo.com/voice/voice-api/ncco-reference)
Each action method will have a required parameter that will match the required parameter in the NCCO reference. All other options can be added in the second optional array parameter.

Action methods always return the `NccoBuilder` instance, so you can chain any additional methods.

For example:

```php
// Default talk action
$builder->talk('Hello!');

// Talk with different voice
$builder->talk('Hi there!', [ 'voiceName' => 'Joey' ]);

// Connect to another phone
$builder->connect([ 'type' => 'phone', 'number' => '19998887777' ]);

// Start recording
$builder->record();
```

#### Additonal Methods

**addAction(string $action, array $options = [])**

Will add the action provided with the given options.

All the action methods call this method.

```php
$builder->addAction('talk', [ 'text' => 'Hello!' ]);
```

**append(array $data)**

Appends to the end of the NCCO stack.

**prepend(array $data)**
 
Prepends to the beginning of the NCCO stack.

**getNcco()** and **ncco()**

Returns the NCCO array.

**getJsonNcco()** and **json()**

Returns the NCCO as JSON.

**buildResponse(int $httpStatus = 200)**

Builds and returns an `Illuminate\Http\JsonResponse` with the NCCO and provided HTTP status code.

**respond(int $httpStatus = 200)**

Returns the built response from `buildResponse()`

### Call Model

The `TaylorNetwork\LaravelNexmo\Models\Call` model handles the incoming calls and their statuses and prices.

### Ivr Model

The `TaylorNetwork\LaravelNexmo\Models\Ivr` model handles what the caller hears and what happens when they call in. 

Each `Ivr` model has many `IvrSteps` which handle everything.

You can build the IVR menu by using the `build()` or `respond()` method.

**build()**

Will build the entire NCCO for the menu and return the NCCO as an array.

**respond()**

Will convert the built menu from the `build()` method and convert it to an `Illuminate\Http\JsonResponse`

In your controller you can do something like:

```php
public function incomingIvr(Ivr $ivr, Request $request) 
{
	return $ivr->respond();
}
```

### IvrStep Model

The `TaylorNetwork\LaravelNexmo\Models\IvrStep` model has the action, options and order of it in the corresponding IVR menu.

### Sms Model

The `TaylorNetwork\LaravelNexmo\Models\Sms` model handles all incoming and outgoing SMS messages, including actually sending the messages.

**send()**

Calling this method will send the message if it's hasn't been sent yet, as long as it's an outgoing message.

## License

MIT
