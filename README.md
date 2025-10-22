# Mako Mailer
A simple emailing package for the PHP Mako framework. It provides an abstraction layer over email sending libraries, allowing for easy swapping of underlying implementations. The default adapter uses PHPMailer (included in this package).

## Installation
1. Install the composer package:
    ```bash
    composer require inventor96/mako-mailer
    ```

1. Enable the package in Mako:  
    `app/config/application.php`:
    ```php
    [
        'packages' => [
            'web' => [
                \inventor96\MakoMailer\MailerPackage::class
            ],
        ],
    ];
    ```
    This will automatically register the `Mailer` class in the Mako dependency injection container, including the alias `mailer`.

## Configuration
Create a new file at `app/config/packages/mailer/email.php`, and add any of the following applicable configuration options:

```php
return [
    /**
     * The name of the sender.
     */
    'from_name' => 'MakoMailer',

    /**
     * The email address of the sender.
     */
    'from_email' => 'noreply@example.com',

    /**
     * The email adapter class to use.
     * Must implement `inventor96\MakoMailer\interfaces\EmailSenderInterface`.
     */
    'adapter' => inventor96\MakoMailer\adapters\PHPMailerAdapter::class,

    /**
     * ========= PHPMailer Settings =========
     */

    /**
     * Whether to use SMTP for sending emails.
     * Set to `false` to use the mail() function.
     */
    'use_smtp' => true,

    /**
     * SMTP server host.
     */
    'host' => 'smtp.example.com',

    /**
     * SMTP server port.
     */
    'port' => 465,

    /**
     * Encryption method to use.
     * Set to empty string to disable encryption.
     */
    'encryption' => PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS,

    /**
     * Whether to use SMTP authentication.
     */
    'auth' => true,

    /**
     * SMTP username.
     */
    'username' => 'noreply@example.com',

    /**
     * SMTP password.
     */
    'password' => 'MySecurePassword123!',
];
```

## Usage
### Basics
You can use the `inventor96\MakoMailer\Mailer` or the `mailer` alias in the Mako dependency injection container. Here's a basic example in the context of a controller method:

```php
use inventor96\MakoMailer\Mailer;
use inventor96\MakoMailer\EmailUser;

function sendWelcomeEmail(Mailer $mailer) {
    $to = new EmailUser('recipient@example.com', 'Recipient Name');
    $from = new EmailUser('noreply@example.com', 'MakoMailer');

    $sent = $mailer->send( // alternatively use `$this->mailer->send();` if using the alias
        [$to],
        'Welcome to Mako Mailer!',
        '<p>Hello and welcome to Mako Mailer!</p>',
        $from, // optional, will use config defaults if not provided
    );

    if ($sent) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
```

### Email Templates
You can also use Mako's view templates for email content. Here's an example:

```php
use inventor96\MakoMailer\Mailer;
use inventor96\MakoMailer\EmailUser;

function sendWelcomeEmail(Mailer $mailer) {
    $to = new EmailUser('recipient@example.com', 'Recipient Name');
    $from = new EmailUser('noreply@example.com', 'MakoMailer');

    $sent = $mailer->sendTemplate(
        [$to],
        'Welcome to Mako Mailer!',
        'emails/welcome', // path to the view template, relative to the views directory. e.g. 'emails/welcome' for 'app/resources/views/emails/welcome.tpl.php'
        [
            'name' => 'Recipient Name',
        ],
        $from, // optional, will use config defaults if not provided
    );

    if ($sent) {
        echo "Email sent successfully!";
    } else {
        echo "Failed to send email.";
    }
}
```

### EmailUser
The `EmailUser` class is a simple value object that represents an email user (i.e., the sender or recipient of an email). It contains the user's email address and name. You can either create an instance of `EmailUser` directly (as shown above) or use the static `fromUser()` method to create an instance from an object that implements `inventor96\MakoMailer\interfaces\EmailUserInterface`:

```php
use inventor96\MakoMailer\EmailUser;
use inventor96\MakoMailer\interfaces\EmailUserInterface;
use mako\gatekeeper\entities\user\User as GatekeeperUser;

class User extends GatekeeperUser implements EmailUserInterface {
    public function getEmail(): string {
        return $this->email;
    }

    public function getName(): string {
        return $this->first_name . ' ' . $this->last_name;
    }
}

$user = User::getOrThrow(1); // fetch user from database
$to = EmailUser::fromUser($user);

$mailer->send([$to], 'Subject', 'Email body');
```