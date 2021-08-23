<?php
    namespace App\Domain\_mysql\System\Entity;

    use App\Domain\_mysql\System\Repository\UserRepository;
    use Doctrine\ORM\Mapping as ORM;
    use OpenApi\Annotations as OA;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * @ORM\Entity(repositoryClass=UserRepository::class)
     * @ORM\Table(name="system_user")
     * @OA\Schema(schema="SystemUser")
     */
    class User implements UserInterface, PasswordAuthenticatedUserInterface {

        /**
         * @ORM\Id
         * @ORM\Column(type="string")
         * @ORM\GeneratedValue(strategy="UUID")
         * @OA\Property(type="string", format="uuid")
         */
        private $id;

        /**
         * @ORM\Column(type="string", length=255, nullable=true)
         * @OA\Property(type="string", format="uri")
         */
        private $avatar;

        /**
         * @ORM\Column(type="string", length=100)
         * @OA\Property(type="string")
         */
        private $lastname;

        /**
         * @ORM\Column(type="string", length=100)
         * @OA\Property(type="string")
         */
        private $firstname;

        /**
         * @ORM\Column(type="string", length=180, unique=true)
         * @OA\Property(type="string", format="email")
         * @Assert\Length(
         *     min="6",
         *     max="60",
         *     minMessage="your email must be at least {{ limit }} characters long",
         *     maxMessage="your email connot be longer than {{ limit }} characters"
         * )
         * @Assert\NotBlank()
         */
        private $email;

        /**
         * @ORM\Column(type="json")
         * @OA\Property(type="array", @OA\Items(type="string", default="ROLE_USER"))
         */
        private $roles = [];

        /**
         * @ORM\Column(type="string")
         * @OA\Property(type="string", format="password")
         */
        private $password;

        /**
         * @ORM\Column(type="string", unique=true, nullable=true)
         * @OA\Property(type="string", pattern="/^([0-9a-z]{6}-){4}[0-9a-z]{6}$/")
         * @Assert\NotBlank()
         * @Assert\Regex(
         *     pattern="/^([0-9a-z]{6}-){4}[0-9a-z]{6}$/",
         *     match=true,
         *     message="your api key is not securized"
         * )
         */
        private $apiToken;

        public function __toString() {
            return $this->getId();
        }

        public function getId(): ?string {
            return $this->id;
        }

        public function getAvatar() {
            return $this->avatar;
        }

        public function setAvatar($avatar): self {
            $this->avatar = $avatar;
            return $this;
        }

        public function getLastname() {
            return $this->lastname;
        }

        public function setLastname($lastname): self {
            $this->lastname = strtolower($lastname);
            return $this;
        }

        public function getFirstname() {
            return $this->firstname;
        }

        public function setFirstname($firstname): self {
            $this->firstname = strtolower($firstname);
            return $this;
        }

        public function getEmail(): ?string {
            return $this->email;
        }

        public function setEmail(string $email): self {
            $this->email = strtolower($email);
            return $this;
        }

        public function getRoles(): array {
            $roles = $this->roles;
            $roles[] = 'ROLE_USER';
            return array_unique($roles);
        }

        public function setRoles(array $roles): self {
            $this->roles = $roles;
            return $this;
        }

        public function getPassword(): string {
            return $this->password;
        }

        public function setPassword(string $password): self {
            $this->password = $password;
            return $this;
        }

        public function getApiToken(): string {
            return $this->apiToken;
        }

        public function setApiToken(string $apiToken): self {
            $this->apiToken = $apiToken;
            return $this;
        }

        /***************************************************************************************************************
         * CUSTOM FUNCTION
         */
        public function getUserIdentifier(): string {
            return (string) $this->email;
        }

        public function getUsername(): string {
            return (string) $this->email;
        }

        public function getSalt(): ?string {
            return null;
        }

        public function eraseCredentials() {
        }

        public function getInitial(){
            return $this->firstname[0].$this->lastname[0];
        }

        public function getFullName(){
            return $this->lastname." ".$this->firstname;
        }

    }