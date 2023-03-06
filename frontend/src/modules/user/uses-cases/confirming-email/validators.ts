import { Either, left, right } from "@sweet-monads/either";
import { Dtos as Dtos} from "./dtos";
import { Dtos as CommonDtos } from "../../../common/dtos";
import { Errors as Errors } from './errors';
import { Errors as CommonErrors } from '../../../common/errors';
import { Validators as CommonValidators } from '../../../common/validators';

export namespace Validators {

  export const Code = (dto: Dtos.Data): Either<CommonErrors.ErrorI, Dtos.Data> => {
    if (dto.code > 9999 || dto.code < 1000) {
      return left(new Errors.Domain.InvlidCode())
    }
    return right(dto);
  };

  export const valid = (dto: Dtos.Data): Either<CommonErrors.ErrorI, Dtos.Data> => {
    return Validators.Code(dto).chain(
      CommonValidators.Email.bind(dto as CommonDtos.Email)
    );
  }

}
