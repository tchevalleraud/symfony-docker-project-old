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
         * @ORM\Column(type="string", length=255)
         * @OA\Property(type="string")
         */
        private $jobTitle;

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
         * @ORM\Column(type="string", length=20, nullable=true)
         */
        private $phone;

        /**
         * @ORM\Column(type="string", length=2)
         * @OA\Property(type="string")
         */
        private $locale;

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

        /**
         * @ORM\Column(type="json", nullable=true)
         */
        private $otp;

        /**
         * @ORM\Column(type="string", nullable=true)
         */
        private $otpCode;

        /**
         * @ORM\Column(type="string", nullable=true)
         */
        private $otpCodeSecret;

        /**
         * @ORM\Column(type="json")
         * @OA\Property(type="array", @OA\Items(type="string", default="ROLE_USER"))
         */
        private $roles = [];

        /**
         * @ORM\Column(type="datetime")
         */
        private $dateCreated;

        /**
         * @ORM\Column(type="datetime")
         */
        private $dateStarted;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        private $dateEnded;

        /**
         * @ORM\Column(type="datetime", nullable=true)
         */
        private $dateLastActivity;

        /**
         * @ORM\Column(type="boolean")
         */
        private $locked;

        /**
         * @ORM\Column(type="boolean")
         */
        private $enabled;

        /**
         * @ORM\Column(type="boolean")
         */
        private $deleted;

        public function __construct(){
            $this->locale       = "en";
            $this->apiToken     = implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6));
            $this->dateCreated  = new \DateTime();
            $this->dateStarted  = new \DateTime();
            $this->locked       = false;
            $this->enabled      = true;
            $this->deleted      = false;
        }

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

        public function getJobTitle() {
            return $this->jobTitle;
        }

        public function setJobTitle($jobTitle): self {
            $this->jobTitle = $jobTitle;
            return $this;
        }

        public function getEmail(): ?string {
            return $this->email;
        }

        public function setEmail(string $email): self {
            $this->email = strtolower($email);
            return $this;
        }

        public function getPhone() {
            return $this->phone;
        }

        public function setPhone($phone): self {
            $this->phone = $phone;
            return $this;
        }

        public function getLocale(): string {
            return $this->locale;
        }

        public function setLocale(string $locale): self {
            $this->locale = $locale;
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

        public function getOtp() {
            return $this->otp;
        }

        public function setOtp($otp): self {
            $this->otp = $otp;
            return $this;
        }

        public function getOtpCode() {
            return $this->otpCode;
        }

        public function setOtpCode($otpCode): self {
            $this->otpCode = $otpCode;
            return $this;
        }

        public function getOtpCodeSecret() {
            return $this->otpCodeSecret;
        }

        public function setOtpCodeSecret($otpCodeSecret): self {
            $this->otpCodeSecret = $otpCodeSecret;
            return $this;
        }

        public function getRoles(): array {
            $roles = $this->roles;
            $roles[] = 'ROLE_USER';
            return array_unique($roles);
        }

        public function addRole(string $role): self {
            $this->roles[] = $role;
            return $this;
        }

        public function removeRole(string $role): self {
            $this->roles = array_diff($this->roles, [$role]);
            return $this;
        }

        public function setRoles(array $roles): self {
            $this->roles = $roles;
            return $this;
        }

        public function getDateCreated(): \DateTime {
            return $this->dateCreated;
        }

        public function setDateCreated(\DateTime $dateCreated): self {
            $this->dateCreated = $dateCreated;
            return $this;
        }

        public function getDateStarted(): \DateTime {
            return $this->dateStarted;
        }

        public function setDateStarted(\DateTime $dateStarted): self {
            $this->dateStarted = $dateStarted;
            return $this;
        }

        public function getDateEnded() {
            return $this->dateEnded;
        }

        public function setDateEnded($dateEnded): self {
            $this->dateEnded = $dateEnded;
            return $this;
        }

        public function getDateLastActivity() {
            return $this->dateLastActivity;
        }

        public function setDateLastActivity($dateLastActivity): self {
            $this->dateLastActivity = $dateLastActivity;
            return $this;
        }

        public function isLocked(): bool {
            return $this->locked;
        }

        public function setLocked(bool $locked): self {
            $this->locked = $locked;
            return $this;
        }

        public function isEnabled(): bool {
            return $this->enabled;
        }

        public function setEnabled(bool $enabled): self {
            $this->enabled = $enabled;
            return $this;
        }

        public function isDeleted(): bool {
            return $this->deleted;
        }

        public function setDeleted(bool $deleted): self {
            $this->deleted = $deleted;
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
            return $this->lastname[0].$this->firstname[0];
        }

        public function getFullName(){
            return $this->lastname." ".$this->firstname;
        }

    }