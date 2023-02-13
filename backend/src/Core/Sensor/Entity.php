<?php declare(strict_types=1);

namespace Core\Sensor;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;


class Entity
{
  protected string $id;
  protected DateTime $created;
  protected Core\Sensor\ValueObjects\Name $name;
  protected Core\Sensor\ValueObjects\Longitude $longitude;
  protected Core\Sensor\ValueObjects\Latitude $latitude;
  protected Core\Sensor\ValueObjects\Status $sataus;
  protected string $description;
  protected Core\User\Entity $owner;

  protected function __construct(
    string $id,
    DateTime $created,
    Core\Sensor\ValueObjects\Name $name,
    Core\Sensor\ValueObjects\Longitude $longitude,
    Core\Sensor\ValueObjects\Latitude $latitude,
    Core\Sensor\ValueObjects\Status $sataus,
    string $description,
    Core\User\Entity $owner
  )
  {
    $this->id = $id;
    $this->created = $created;
    $this->name = $name;
    $this->longitude = $longitude;
    $this->latitude = $latitude;
    $this->sataus = $sataus;
    $this->description = $description;
    $this->owner = $owner;
  }

  public function getId(): string
  {
    return $this->id;
  }

  public function getCreated(): DateTime
  {
    return $this->created;
  }

  public function getName(): Core\Sensor\ValueObjects\Name
  {
    return $this->name;
  }

  public function getLongitude(): Core\Sensor\ValueObjects\Longitude
  {
    return $this->longitude;
  }

  public function getLatitude(): Core\Sensor\ValueObjects\Latitude
  {
    return $this->latitude;
  }

  public function getStatus(): Core\Sensor\ValueObjects\Status
  {
    return $this->sataus;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getOwner(): Core\User\Entity
  {
    return $this->owner;
  }

  public static function new(
    ?string $name,
    ?int $longitude,
    ?int $latitude,
    ?string $sataus,
    ?string $description,
    Core\User\Entity $owner
  ): Core\Common\Errors\Domain | Entity
  {
    $maybe_name = Core\Sensor\ValueObjects\Name::new($name);

    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    $maybe_longitude = Core\Sensor\ValueObjects\Longitude::new($longitude);

    if ($maybe_longitude instanceof Core\Common\Errors\Domain) {
      return $maybe_longitude;
    }

    $maybe_latitude = Core\Sensor\ValueObjects\Latitude::new($latitude);

    if ($maybe_latitude instanceof Core\Common\Errors\Domain) {
      return $maybe_latitude;
    }

    $maybe_sataus = Core\Sensor\ValueObjects\Status::new($sataus);

    if ($maybe_sataus instanceof Core\Common\Errors\Domain) {
      return $maybe_sataus;
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_name,
      $maybe_longitude,
      $maybe_latitude,
      $maybe_sataus,
      $description ? $description : '',
      $owner
    );
  }
  // protected function isOwner(Core\User\Entity $owner): bool | Core\Common\Errors\Domain
  // {
  //   if ($owner->getId() != $this->owner->getId()) {
  //     new Core\Common\Errors\Domain('You have not right for update status of this sensor');
  //   }

  //   return true;
  // }

  // public function updtaeStatus(?string $sataus, Core\User\Entity $owner)
  // {

  // }
}
