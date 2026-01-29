<?php
namespace inventor96\MakoMailer;

use inventor96\MakoMailer\interfaces\EmailSenderInterface;
use mako\application\Package;

class MailerPackage extends Package {
	protected string $packageName = 'inventor96/mako-mailer';
	protected string $fileNamespace = 'mailer';

	/**
	 * @inheritDoc
	 */
	function bootstrap(): void {
		// register the mailer class as a singleton
		$this->container->registerSingleton([Mailer::class, 'mailer'], Mailer::class);

		// register the email sender adapter
		$adapter_class = $this->container->get('config')->get("{$this->fileNamespace}::email.adapter");
		$this->container->register(EmailSenderInterface::class, $adapter_class);
	}
}