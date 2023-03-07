import { Either, left, right } from "@sweet-monads/either";
import { SharedDtos } from "./dtos";

export namespace SharedValidators {
  export const Email = (dto: SharedDtos.Email): Either<Error, SharedDtos.Email> => {
    if (dto.email.trim() == '') {
      return left({message: 'Invalid email address'});
    }
    return right(dto);
  };

  export const Password = (dto: SharedDtos.Password): Either<Error, SharedDtos.Password> => {
    if (dto.password.trim() == '') {
      return left({message: 'Invalid password'});
    }
    return right(dto);
  };

  export const Name = (dto: SharedDtos.Name): Either<Error, SharedDtos.Name> => {
    if (dto.name.trim() == '') {
      return left({message: 'Invalid name'})
    }
    return right(dto);
  };
}
