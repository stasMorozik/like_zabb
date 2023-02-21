<?php declare(strict_types=1);

namespace Core\Sensor;

use Core;
use DateTime;
use Ramsey\Uuid\Uuid;


class Entity extends Core\Common\Entity
{
  protected Core\Sensor\ValueObjects\Name $name;
  protected Core\Sensor\ValueObjects\Longitude $longitude;
  protected Core\Sensor\ValueObjects\Latitude $latitude;
  protected Core\Sensor\ValueObjects\Status $status;
  protected string $description;
  protected Core\User\Entity $owner;

  protected function __construct(
    string $id,
    DateTime $created,
    Core\Sensor\ValueObjects\Name $name,
    Core\Sensor\ValueObjects\Longitude $longitude,
    Core\Sensor\ValueObjects\Latitude $latitude,
    Core\Sensor\ValueObjects\Status $status,
    string $description,
    Core\User\Entity $owner
  )
  {
    $this->name = $name;
    $this->longitude = $longitude;
    $this->latitude = $latitude;
    $this->status = $status;
    $this->description = $description;
    $this->owner = $owner;
    parent::__construct($id, $created);
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
    return $this->status;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function getOwner(): Core\User\Entity
  {
    return $this->owner;
  }

  public static function new(array $args): Core\Common\Errors\Domain | Entity
  {
    $keys = ['name', 'longitude', 'latitude', 'status', 'description', 'owner'];
    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $maybe_name = Core\Sensor\ValueObjects\Name::new(['name' => $args['name']]);

    if ($maybe_name instanceof Core\Common\Errors\Domain) {
      return $maybe_name;
    }

    $maybe_longitude = Core\Sensor\ValueObjects\Longitude::new(['longitude' => $args['longitude']]);

    if ($maybe_longitude instanceof Core\Common\Errors\Domain) {
      return $maybe_longitude;
    }

    $maybe_latitude = Core\Sensor\ValueObjects\Latitude::new(['latitude' => $args['latitude']]);

    if ($maybe_latitude instanceof Core\Common\Errors\Domain) {
      return $maybe_latitude;
    }

    $maybe_status = Core\Sensor\ValueObjects\Status::new(['status' => $args['status']]);

    if ($maybe_status instanceof Core\Common\Errors\Domain) {
      return $maybe_status;
    }

    if (($args['owner'] instanceof Core\User\Entity) == false) {
      return new Core\Common\Errors\Domain('Invalid user');
    }

    if ($args['owner']->isAdmin() !== true && $args['owner']->isRoot() !== true) {
      return new Core\Common\Errors\Domain('You have not right for create a sensor');
    }

    return new Entity(
      Uuid::uuid4()->toString(),
      new DateTime(),
      $maybe_name,
      $maybe_longitude,
      $maybe_latitude,
      $maybe_status,
      $args['description'] ? $args['description'] : '',
      $args['owner']
    );
  }

  protected function isOwner(Core\User\Entity $owner): bool | Core\Common\Errors\Domain
  {
    if ($owner->getAccount()->getId() != $this->owner->getAccount()->getId()) {
      return new Core\Common\Errors\Domain('You have not right for update status of this sensor');
    }

    return true;
  }

  protected function haveRights(): bool | Core\Common\Errors\Domain
  {
    if ($this->getOwner()->isAdmin() === true || $this->getOwner()->isRoot() === true) {
      return true;
    }

    return new Core\Common\Errors\Domain('You have not right for update status of this sensor');
  }

  public function updtaeStatus(array $args): bool | Core\Common\Errors\Domain
  {
    if (!isset($args['owner']) || !isset($args['status'])) {
      return new Core\Common\Errors\Domain('Invalid argument');
    }

    if (($args['owner'] instanceof Core\User\Entity) == false) {
      return new Core\Common\Errors\Domain('Invalid code');
    }

    $have_rights = $this->haveRights();
    if ($have_rights !== true) {
      return $have_rights;
    }

    $is_owner = $this->isOwner($args['owner']);
    if ($is_owner !== true) {
      return $is_owner;
    }

    $maybe_status = Core\Sensor\ValueObjects\Status::new(['status' => $args['status']]);

    if ($maybe_status instanceof Core\Common\Errors\Domain) {
      return $maybe_status;
    }

    if ($maybe_status->getValue() == $this->getStatus()->getValue()) {
      return new Core\Common\Errors\Domain('Status already updated');
    }

    $this->status = $maybe_status;

    return true;
  }
}
