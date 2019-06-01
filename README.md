Sms Feedback Bundle
===================

Provides [SmsFeedback SDK](https://github.com/phpinfo/smsfeedback) integration with Symfony 4 Framework. 

Installation
------------

Require the bundle with `Composer`: 

```bash
composer require phpinfo/smsfeedback-bundle
```

Register the bundle in `config/bundles.php`:

```php
return [
    SmsFeedbackBundle\SmsFeedbackBundle::class => ['all' => true],
];
```

Create simple configuration file in `config/packages/sms_feedback.yaml`:

```yaml
sms_feedback:
    login: '%env(SMSFEEDBACK_LOGIN)%'
    password: '%env(SMSFEEDBACK_PASSWORD)%'
```

Provide credentials as environment variables in `.env.local`:

```
SMSFEEDBACK_LOGIN=my_login
SMSFEEDBACK_PASSWORD=my_password
```

Usage
-----

The [`ApiClient`](https://github.com/phpinfo/smsfeedback/blob/master/src/ApiClient.php) object is now registered in the 
container with its interface name: `SmsFeedback\ApiClientInterface\ApiClientInterface`.

Controller usage example:

```php
use SmsFeedback\ApiClientInterface;

class IndexController extends AbstractController
{
    public function index(ApiClientInterface $smsfeedback): Response
    {
        $message = $smsfeedback->send('79161234567', 'Some text');

        return new Response($message->getStatus());
    }
}
```  

Console command usage example:

```php
use SmsFeedback\ApiClientInterface;

class TestCommand extends Command
{
    private $smsfeedback;

    public function __construct(ApiClientInterface $smsfeedback)
    {
        parent::__construct();

        $this->smsfeedback = $smsfeedback;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->smsfeedback->send('79161234567', 'Some text');
    }
}
```

See [SmsFeedback SDK documentation](https://github.com/phpinfo/smsfeedback/blob/master/README.md)
for advanced usage cases.

Configuration
-------------

Configuration options:
```yaml
sms_feedback:
    # Auth login (required)
    login: 'my-login'
    
    # Auth password (required)
    password: 'my-password'
    
    # Endpoint base URI
    uri: 'http://api.smsfeedback.ru'
    
    # Request timeout
    timeout: 5000
    
    logger:
        # Determines if logger is enabled (default false)
        enabled: true
        
        # Determines logger service name
        service: 'Psr\Log\LoggerInterface'
        
        # Logger message template
        message_template: '{method} {target} HTTP/{version} {code}'
```

Resources
---------

* [SmsFeedback SDK](https://github.com/phpinfo/smsfeedback)
* [SmsFeedback SMS sending service](https://smsfeedback.ru)
* [SmsFeedback API documentation](https://www.smsfeedback.ru/smsapi/)
