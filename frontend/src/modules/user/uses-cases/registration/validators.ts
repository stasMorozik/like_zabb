import { Either, left, right } from "@sweet-monads/either";
import { Dtos } from "./dtos";
import { Errors as CommonErrors } from '../../../common/errors';
import { Errors as RegistrationErrors } from './errors';

export namespace Validators {
  export class Registration {
    constructor(){}

    valid(dto: Dtos.RegistrationData): Either<Error, boolean> {
      if (dto.name.trim() == '') {
        return left(new CommonErrors.Domain.InvalidName());
      }

      if (dto.email.trim() == '') {
        return left(new CommonErrors.Domain.InvalidEmail());
      }

      if (dto.password.trim() == '') {
        return left(new CommonErrors.Domain.InvalidPassword());
      }

      if (dto.password != dto.confirmPassword) {
        return left(new RegistrationErrors.PasswordNotEqual());
      }

      return right(true);
    }
  }
}
