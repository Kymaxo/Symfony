<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields = {"email"},
 *     message = "l'email que vous avez saisi est déjà utilisé !"
 * )
 */
class User implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit avoir au min 8 caractères")
     * @Assert\EqualTo(propertyPath="confirm_password")
     */
    private $password;
    
    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas saisi le même mot de passe")
     */
    public $confirm_password;

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string {
        return $this->username;
    }

    public function setUsername(string $username): self {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }
	
	/**
	 * @inheritDoc
	 */
	public function getRoles() 	{
		return ['ROLE_USER'];
	}
	
	/**
	 * @inheritDoc
	 */
	public function getSalt() {}
	
	/**
	 * @inheritDoc
	 */
	public function eraseCredentials() 	{}
}
