import { Either, left, right } from "@sweet-monads/either";
import { Dtos as RegistrationDtos} from "./dtos";
import { Dtos as CommonDtos } from "../../../common/dtos";
import { Errors as RegistrationErrors } from './errors';
import { Errors as CommonErrors } from '../../../common/errors';
import { Validators as CommonValidators } from '../.././../common/validators';

export namespace Validators {

  export const ConfirmPassword = (dto: RegistrationDtos.Data): Either<CommonErrors.ErrorI, RegistrationDtos.Data> => {
    if (dto.confirmPassword != dto.password) {
      return left(new RegistrationErrors.Domain.PasswordNotEqual())
    }
    return right(dto);
  };

  export const valid = (dto: RegistrationDtos.Data): Either<CommonErrors.ErrorI, RegistrationDtos.Data> => {
    return CommonValidators.Name(dto as CommonDtos.Name).chain(
      CommonValidators.Email.bind(undefined, dto as CommonDtos.Email)
    ).chain(
      CommonValidators.Password.bind(undefined, dto as CommonDtos.Password)
    ).chain(
      Validators.ConfirmPassword.bind(undefined, dto as RegistrationDtos.Data)
    );
  }

}
