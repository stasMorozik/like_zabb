import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import { SharedValidators } from "./shared/validators";
import { SharedDtos } from './shared/dtos';

export namespace RegistrationUseCase {

  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.Data): Observable<Either<Error, boolean>>
    }

    export interface Emiter {
      emit(either: Either<Error, boolean>): void
    }
  }

  export namespace Dtos {
    export type Data = SharedDtos.Email & SharedDtos.Name & SharedDtos.Password & ConfirmingPassword

    export type ConfirmingPassword = {
      confirmingPassword: string
    }
  }

  export namespace Validators {
    export const ConfirmPassword = (dto: Dtos.Data): Either<Error, Dtos.Data> => {
      if (dto.confirmingPassword != dto.password) {
        return left({message: 'Password are not equal'});
      }
      return right(dto);
    };

    export const valid = (dto: Dtos.Data): Either<Error, Dtos.Data> => {
      return SharedValidators.Name(dto as SharedDtos.Name).chain(
        SharedValidators.Email.bind(undefined, dto as SharedDtos.Email)
      ).chain(
        SharedValidators.Password.bind(undefined, dto as SharedDtos.Password)
      ).chain(
        Validators.ConfirmPassword.bind(undefined, dto as Dtos.Data)
      );
    }
  }

  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    registry(dto: Dtos.Data) {
      const either = Validators.valid(dto);

      either.mapLeft((error: Error) => {
        this._emiter.emit(left(error));
      });

      either.map(() => {
        this._api.fetch(dto).subscribe((e: Either<Error, boolean>) => {
          e.mapLeft((error: Error) => this._emiter.emit(left(error)));

          e.map((_: boolean) => {
            this._emiter.emit(right(true));
          });
        });
      });
    }
  }
}
