<?php declare(strict_types=1);

namespace Core\Sensor\UseCases;

use Core;

/**
 *
 * Creating Use Case
 *
**/

class Creating
{
  private Core\Sensor\Ports\Changing $_creating_port;
  private Core\User\UseCases\Authorization $_authorization_use_case;

  public function __construct(
    Core\Sensor\Ports\Changing $creating_port,
    Core\User\UseCases\Authorization $authorization_use_case
  )
  {
    $this->_creating_port = $creating_port;
    $this->_authorization_use_case = $authorization_use_case;
  }

  public function create(array $args): bool | Core\Common\Errors\InfraStructure | Core\Common\Errors\Domain
  {
    $keys = ['name', 'longitude', 'latitude', 'status', 'description', 'access_token'];

    foreach ($keys as &$k) {
      if (!isset($args[$k])) {
        return new Core\Common\Errors\Domain('Invalid arguments');
      }
    }

    $maybe_user = $this->_authorization_use_case->auth(['access_token' => $args['access_token']]);

    if (($maybe_user instanceof Core\User\Entity) == false) {
      return $maybe_user;
    }

    $maybe_sensor = Core\Sensor\Entity::new([
      'name' => $args['name'],
      'longitude' => $args['longitude'],
      'latitude' => $args['latitude'],
      'status' => $args['status'],
      'description' => $args['description'],
      'owner' => $maybe_user
    ]);

    if ($maybe_sensor instanceof Core\Common\Errors\Domain) {
      return $maybe_sensor;
    }

    return $this->_creating_port->change($maybe_sensor);
  }
}
