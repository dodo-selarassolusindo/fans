<?php

namespace PHPMaker2024\prj_fans\Entity;

use DateTime;
use DateTimeImmutable;
use DateInterval;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Doctrine\DBAL\Types\Types;
use PHPMaker2024\prj_fans\AbstractEntity;
use PHPMaker2024\prj_fans\AdvancedSecurity;
use PHPMaker2024\prj_fans\UserProfile;
use function PHPMaker2024\prj_fans\Config;
use function PHPMaker2024\prj_fans\EntityManager;
use function PHPMaker2024\prj_fans\RemoveXss;
use function PHPMaker2024\prj_fans\HtmlDecode;
use function PHPMaker2024\prj_fans\EncryptPassword;

/**
 * Entity class for "employees" table
 */
#[Entity]
#[Table(name: "employees")]
class Employee extends AbstractEntity
{
    #[Id]
    #[Column(name: "EmployeeID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $employeeId;

    #[Column(name: "LastName", type: "string")]
    private string $lastName;

    #[Column(name: "FirstName", type: "string")]
    private string $firstName;

    #[Column(name: "Title", type: "string", nullable: true)]
    private ?string $title;

    #[Column(name: "TitleOfCourtesy", type: "string", nullable: true)]
    private ?string $titleOfCourtesy;

    #[Column(name: "BirthDate", type: "datetime", nullable: true)]
    private ?DateTime $birthDate;

    #[Column(name: "HireDate", type: "datetime", nullable: true)]
    private ?DateTime $hireDate;

    #[Column(name: "Address", type: "string", nullable: true)]
    private ?string $address;

    #[Column(name: "City", type: "string", nullable: true)]
    private ?string $city;

    #[Column(name: "Region", type: "string", nullable: true)]
    private ?string $region;

    #[Column(name: "PostalCode", type: "string", nullable: true)]
    private ?string $postalCode;

    #[Column(name: "Country", type: "string", nullable: true)]
    private ?string $country;

    #[Column(name: "HomePhone", type: "string", nullable: true)]
    private ?string $homePhone;

    #[Column(name: "Extension", type: "string", nullable: true)]
    private ?string $extension;

    #[Column(name: "Photo", type: "string", nullable: true)]
    private ?string $photo;

    #[Column(name: "Notes", type: "text", nullable: true)]
    private ?string $notes;

    #[Column(name: "ReportsTo", type: "integer", nullable: true)]
    private ?int $reportsTo;

    #[Column(name: "Username", type: "string", nullable: true)]
    private ?string $username;

    #[Column(name: "Password", type: "string", nullable: true)]
    private ?string $password;

    #[Column(name: "Email", type: "string", nullable: true)]
    private ?string $email;

    #[Column(name: "Activated", type: "string", nullable: true)]
    private ?string $activated;

    #[Column(name: "Profile", type: "text", nullable: true)]
    private ?string $profile;

    #[Column(name: "UserLevel", type: "integer", nullable: true)]
    private ?int $userLevel;

    #[Column(name: "Avatar", type: "string", nullable: true)]
    private ?string $avatar;

    #[Column(name: "ActiveStatus", type: "boolean", nullable: true)]
    private ?bool $activeStatus;

    #[Column(name: "MessengerColor", type: "string", nullable: true)]
    private ?string $messengerColor;

    #[Column(name: "CreatedAt", type: "datetime", nullable: true)]
    private ?DateTime $createdAt;

    public function __construct()
    {
        $this->userLevel = 0;
    }

    public function getEmployeeId(): int
    {
        return $this->employeeId;
    }

    public function setEmployeeId(int $value): static
    {
        $this->employeeId = $value;
        return $this;
    }

    public function getLastName(): string
    {
        return HtmlDecode($this->lastName);
    }

    public function setLastName(string $value): static
    {
        $this->lastName = RemoveXss($value);
        return $this;
    }

    public function getFirstName(): string
    {
        return HtmlDecode($this->firstName);
    }

    public function setFirstName(string $value): static
    {
        $this->firstName = RemoveXss($value);
        return $this;
    }

    public function getTitle(): ?string
    {
        return HtmlDecode($this->title);
    }

    public function setTitle(?string $value): static
    {
        $this->title = RemoveXss($value);
        return $this;
    }

    public function getTitleOfCourtesy(): ?string
    {
        return HtmlDecode($this->titleOfCourtesy);
    }

    public function setTitleOfCourtesy(?string $value): static
    {
        $this->titleOfCourtesy = RemoveXss($value);
        return $this;
    }

    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTime $value): static
    {
        $this->birthDate = $value;
        return $this;
    }

    public function getHireDate(): ?DateTime
    {
        return $this->hireDate;
    }

    public function setHireDate(?DateTime $value): static
    {
        $this->hireDate = $value;
        return $this;
    }

    public function getAddress(): ?string
    {
        return HtmlDecode($this->address);
    }

    public function setAddress(?string $value): static
    {
        $this->address = RemoveXss($value);
        return $this;
    }

    public function getCity(): ?string
    {
        return HtmlDecode($this->city);
    }

    public function setCity(?string $value): static
    {
        $this->city = RemoveXss($value);
        return $this;
    }

    public function getRegion(): ?string
    {
        return HtmlDecode($this->region);
    }

    public function setRegion(?string $value): static
    {
        $this->region = RemoveXss($value);
        return $this;
    }

    public function getPostalCode(): ?string
    {
        return HtmlDecode($this->postalCode);
    }

    public function setPostalCode(?string $value): static
    {
        $this->postalCode = RemoveXss($value);
        return $this;
    }

    public function getCountry(): ?string
    {
        return HtmlDecode($this->country);
    }

    public function setCountry(?string $value): static
    {
        $this->country = RemoveXss($value);
        return $this;
    }

    public function getHomePhone(): ?string
    {
        return HtmlDecode($this->homePhone);
    }

    public function setHomePhone(?string $value): static
    {
        $this->homePhone = RemoveXss($value);
        return $this;
    }

    public function getExtension(): ?string
    {
        return HtmlDecode($this->extension);
    }

    public function setExtension(?string $value): static
    {
        $this->extension = RemoveXss($value);
        return $this;
    }

    public function getPhoto(): ?string
    {
        return HtmlDecode($this->photo);
    }

    public function setPhoto(?string $value): static
    {
        $this->photo = RemoveXss($value);
        return $this;
    }

    public function getNotes(): ?string
    {
        return HtmlDecode($this->notes);
    }

    public function setNotes(?string $value): static
    {
        $this->notes = RemoveXss($value);
        return $this;
    }

    public function getReportsTo(): ?int
    {
        return $this->reportsTo;
    }

    public function setReportsTo(?int $value): static
    {
        $this->reportsTo = $value;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $value): static
    {
        $this->username = $value;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $value): static
    {
        $this->password = EncryptPassword(Config("CASE_SENSITIVE_PASSWORD") ? $value : strtolower($value));
        return $this;
    }

    public function getEmail(): ?string
    {
        return HtmlDecode($this->email);
    }

    public function setEmail(?string $value): static
    {
        $this->email = RemoveXss($value);
        return $this;
    }

    public function getActivated(): ?string
    {
        return $this->activated;
    }

    public function setActivated(?string $value): static
    {
        if (!in_array($value, ["Y", "N"])) {
            throw new \InvalidArgumentException("Invalid 'Activated' value");
        }
        $this->activated = $value;
        return $this;
    }

    public function getProfile(): ?string
    {
        return HtmlDecode($this->profile);
    }

    public function setProfile(?string $value): static
    {
        $this->profile = RemoveXss($value);
        return $this;
    }

    public function getUserLevel(): ?int
    {
        return $this->userLevel;
    }

    public function setUserLevel(?int $value): static
    {
        $this->userLevel = $value;
        return $this;
    }

    public function getAvatar(): ?string
    {
        return HtmlDecode($this->avatar);
    }

    public function setAvatar(?string $value): static
    {
        $this->avatar = RemoveXss($value);
        return $this;
    }

    public function getActiveStatus(): ?bool
    {
        return $this->activeStatus;
    }

    public function setActiveStatus(?bool $value): static
    {
        $this->activeStatus = $value;
        return $this;
    }

    public function getMessengerColor(): ?string
    {
        return HtmlDecode($this->messengerColor);
    }

    public function setMessengerColor(?string $value): static
    {
        $this->messengerColor = RemoveXss($value);
        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTime $value): static
    {
        $this->createdAt = $value;
        return $this;
    }

    // Get login arguments
    public function getLoginArguments(): array
    {
        return [
            "userName" => $this->get('Username'),
            "userId" => $this->get('EmployeeID'),
            "parentUserId" => $this->get('ReportsTo'),
            "userLevel" => $this->get('UserLevel') ?? AdvancedSecurity::ANONYMOUS_USER_LEVEL_ID,
            "userPrimaryKey" => $this->get('EmployeeID'),
        ];
    }
}
