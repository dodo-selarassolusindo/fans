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
 * Entity class for "fans" table
 */
#[Entity]
#[Table(name: "fans")]
class Fan extends AbstractEntity
{
    #[Id]
    #[Column(name: "FansID", type: "integer", unique: true)]
    #[GeneratedValue]
    private int $fansId;

    #[Column(name: "Nama", type: "string")]
    private string $nama;

    #[Column(name: "Gender", type: "integer", nullable: true)]
    private ?int $gender;

    #[Column(name: "NomorHP", type: "string")]
    private string $nomorHp;

    #[Column(name: "TahunKelahiran", type: "string", nullable: true)]
    private ?string $tahunKelahiran;

    #[Column(name: "Kota", type: "integer")]
    private int $kota;

    #[Column(name: "Profesi", type: "string", nullable: true)]
    private ?string $profesi;

    #[Column(name: "Hobi", type: "string", nullable: true)]
    private ?string $hobi;

    #[Column(name: "AcaraID", type: "integer", nullable: true)]
    private ?int $acaraId;

    #[Column(name: "RadioID", type: "integer", nullable: true)]
    private ?int $radioId;

    #[Column(name: "Keterangan", type: "text", nullable: true)]
    private ?string $keterangan;

    public function getFansId(): int
    {
        return $this->fansId;
    }

    public function setFansId(int $value): static
    {
        $this->fansId = $value;
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

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $value): static
    {
        $this->gender = $value;
        return $this;
    }

    public function getNomorHp(): string
    {
        return HtmlDecode($this->nomorHp);
    }

    public function setNomorHp(string $value): static
    {
        $this->nomorHp = RemoveXss($value);
        return $this;
    }

    public function getTahunKelahiran(): ?string
    {
        return HtmlDecode($this->tahunKelahiran);
    }

    public function setTahunKelahiran(?string $value): static
    {
        $this->tahunKelahiran = RemoveXss($value);
        return $this;
    }

    public function getKota(): int
    {
        return $this->kota;
    }

    public function setKota(int $value): static
    {
        $this->kota = $value;
        return $this;
    }

    public function getProfesi(): ?string
    {
        return HtmlDecode($this->profesi);
    }

    public function setProfesi(?string $value): static
    {
        $this->profesi = RemoveXss($value);
        return $this;
    }

    public function getHobi(): ?string
    {
        return HtmlDecode($this->hobi);
    }

    public function setHobi(?string $value): static
    {
        $this->hobi = RemoveXss($value);
        return $this;
    }

    public function getAcaraId(): ?int
    {
        return $this->acaraId;
    }

    public function setAcaraId(?int $value): static
    {
        $this->acaraId = $value;
        return $this;
    }

    public function getRadioId(): ?int
    {
        return $this->radioId;
    }

    public function setRadioId(?int $value): static
    {
        $this->radioId = $value;
        return $this;
    }

    public function getKeterangan(): ?string
    {
        return HtmlDecode($this->keterangan);
    }

    public function setKeterangan(?string $value): static
    {
        $this->keterangan = RemoveXss($value);
        return $this;
    }
}
