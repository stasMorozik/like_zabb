import { Either, left, right, ma } from "@sweet-monads/either";
import { Dtos } from "./dtos";
import { Errors as RegistrationErrors } from './errors';
import { Errors as CommonErrors } from '../../../common/errors';
import { Validators as CommonValidators } from '../.././../common/validators';

export namespace Validators {

  export const ConfirmPassword = (dto: Dtos.Data): Either<CommonErrors.ErrorI, boolean> => {
    if (dto.confirmPassword != dto.password) {
      return left(new RegistrationErrors.Domain.PasswordNotEqual())
    }
    return right(dto);
  };

  export const valid = (dto: Dtos.Data): Either<CommonErrors.ErrorI, boolean> => {
    return CommonValidators.Name(dto).chain(
      CommonValidators.Email
    ).chain(
      CommonValidators.Password
    ).chain(
      ConfirmPassword
    );
  }
}
