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
 * Entity class for "exportlog" table
 */
#[Entity]
#[Table(name: "exportlog")]
class Exportlog extends AbstractEntity
{
    #[Id]
    #[Column(name: "FileId", type: "string", unique: true)]
    private string $fileId;

    #[Column(name: "DateTime", type: "datetime")]
    private DateTime $dateTime;

    #[Column(name: "User", type: "string")]
    private string $user;

    #[Column(name: "ExportType", type: "string")]
    private string $exportType;

    #[Column(name: "`Table`", options: ["name" => "Table"], type: "string")]
    private string $table;

    #[Column(name: "KeyValue", type: "string", nullable: true)]
    private ?string $keyValue;

    #[Column(name: "Filename", type: "string")]
    private string $filename;

    #[Column(name: "Request", type: "text")]
    private string $request;

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function setFileId(string $value): static
    {
        $this->fileId = $value;
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

    public function getUser(): string
    {
        return HtmlDecode($this->user);
    }

    public function setUser(string $value): static
    {
        $this->user = RemoveXss($value);
        return $this;
    }

    public function getExportType(): string
    {
        return HtmlDecode($this->exportType);
    }

    public function setExportType(string $value): static
    {
        $this->exportType = RemoveXss($value);
        return $this;
    }

    public function getTable(): string
    {
        return HtmlDecode($this->table);
    }

    public function setTable(string $value): static
    {
        $this->table = RemoveXss($value);
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

    public function getFilename(): string
    {
        return HtmlDecode($this->filename);
    }

    public function setFilename(string $value): static
    {
        $this->filename = RemoveXss($value);
        return $this;
    }

    public function getRequest(): string
    {
        return HtmlDecode($this->request);
    }

    public function setRequest(string $value): static
    {
        $this->request = RemoveXss($value);
        return $this;
    }
}
