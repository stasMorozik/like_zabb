import { Either, left, right } from "@sweet-monads/either";
import { Errors as CommonErrors } from '../common/errors';
import { Dtos } from "./dtos";

export namespace Validators {
  export const Email = (dto: Dtos.Email): Either<CommonErrors.ErrorI, Dtos.Email> => {
    if (dto.email.trim() == '') {
      return left(new CommonErrors.Domain.InvalidEmail())
    }
    return right(dto);
  };

  export const Password = (dto: Dtos.Password): Either<CommonErrors.ErrorI, Dtos.Password> => {
    if (dto.password.trim() == '') {
      return left(new CommonErrors.Domain.InvalidPassword())
    }
    return right(dto);
  };

  export const Name = (dto: Dtos.Name): Either<CommonErrors.ErrorI, Dtos.Name> => {
    if (dto.name.trim() == '') {
      return left(new CommonErrors.Domain.InvalidName())
    }
    return right(dto);
  };
}
