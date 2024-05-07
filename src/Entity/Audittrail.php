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
 * Entity class for "audittrail" table
 */
#[Entity]
#[Table(name: "audittrail")]
class Audittrail extends AbstractEntity
{
    #[Id]
    #[Column(name: "Id", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $id;

    #[Column(name: "DateTime", type: "datetime")]
    private DateTime $dateTime;

    #[Column(name: "Script", type: "string", nullable: true)]
    private ?string $script;

    #[Column(name: "User", type: "string", nullable: true)]
    private ?string $user;

    #[Column(name: "Action", type: "string", nullable: true)]
    private ?string $action;

    #[Column(name: "`Table`", options: ["name" => "Table"], type: "string", nullable: true)]
    private ?string $table;

    #[Column(name: "Field", type: "string", nullable: true)]
    private ?string $field;

    #[Column(name: "KeyValue", type: "text", nullable: true)]
    private ?string $keyValue;

    #[Column(name: "OldValue", type: "text", nullable: true)]
    private ?string $oldValue;

    #[Column(name: "NewValue", type: "text", nullable: true)]
    private ?string $newValue;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value): static
    {
        $this->id = $value;
        return $this;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(DateTime $value): static
    {
        $this->dateTime = $value;
        return $this;
    }

    public function getScript(): ?string
    {
        return HtmlDecode($this->script);
    }

    public function setScript(?string $value): static
    {
        $this->script = RemoveXss($value);
        return $this;
    }

    public function getUser(): ?string
    {
        return HtmlDecode($this->user);
    }

    public function setUser(?string $value): static
    {
        $this->user = RemoveXss($value);
        return $this;
    }

    public function getAction(): ?string
    {
        return HtmlDecode($this->action);
    }

    public function setAction(?string $value): static
    {
        $this->action = RemoveXss($value);
        return $this;
    }

    public function getTable(): ?string
    {
        return HtmlDecode($this->table);
    }

    public function setTable(?string $value): static
    {
        $this->table = RemoveXss($value);
        return $this;
    }

    public function getField(): ?string
    {
        return HtmlDecode($this->field);
    }

    public function setField(?string $value): static
    {
        $this->field = RemoveXss($value);
        return $this;
    }

    public function getKeyValue(): ?string
    {
        return HtmlDecode($this->keyValue);
    }

    public function setKeyValue(?string $value): static
    {
        $this->keyValue = RemoveXss($value);
        return $this;
    }

    public function getOldValue(): ?string
    {
        return HtmlDecode($this->oldValue);
    }

    public function setOldValue(?string $value): static
    {
        $this->oldValue = RemoveXss($value);
        return $this;
    }

    public function getNewValue(): ?string
    {
        return HtmlDecode($this->newValue);
    }

    public function setNewValue(?string $value): static
    {
        $this->newValue = RemoveXss($value);
        return $this;
    }
}
