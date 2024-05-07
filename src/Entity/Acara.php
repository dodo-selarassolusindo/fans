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
 * Entity class for "acara" table
 */
#[Entity]
#[Table(name: "acara")]
class Acara extends AbstractEntity
{
    #[Id]
    #[Column(name: "AcaraID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $acaraId;

    #[Column(name: "Nama", type: "string")]
    private string $nama;

    public function getAcaraId(): int
    {
        return $this->acaraId;
    }

    public function setAcaraId(int $value): static
    {
        $this->acaraId = $value;
        return $this;
    }

    public function getNama(): string
    {
        return HtmlDecode($this->nama);
    }

    public function setNama(string $value): static
    {
        $this->nama = RemoveXss($value);
        return $this;
    }
}
