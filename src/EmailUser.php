<?php
namespace inventor96\MakoMailer;

use InvalidArgumentException;
use inventor96\MakoMailer\interfaces\EmailUserInterface;

class EmailUser {
	public ?string $name;
	public string $email;

	public function __construct(string $email, ?string $name = null) {
		if (empty($email)) {
			throw new InvalidArgumentException("Email cannot be empty");
		}
		$this->email = $email;
		$this->name = $name;
	}

	public static function fromUser(EmailUserInterface $user): self {
		return new self($user->getEmail(), $user->getName());
	}
}