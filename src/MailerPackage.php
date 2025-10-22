<?php
namespace inventor96\MakoMailer;

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
	}
}