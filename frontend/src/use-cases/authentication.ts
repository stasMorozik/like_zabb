import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import { SharedDtos } from './shared/dtos';
import { SharedValidators } from './shared/validators';

export namespace AuthenticationUseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.Data): Observable<Either<Error, boolean>>
    }

    export interface Emiter {
      emit(either: Either<Error, boolean>): void
    }
  }

  export namespace Dtos {
    export type Data = SharedDtos.Email & SharedDtos.Password
  }

  export namespace Validators {
    export const valid = (dto: Dtos.Data): Either<Error, Dtos.Data> => {
      return SharedValidators.Email(dto as SharedDtos.Email)
        .chain(
          SharedValidators.Password.bind(undefined, dto as SharedDtos.Password)
        ) as Either<Error, Dtos.Data>
    }
  }

  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    auth(dto: Dtos.Data) {
      const either = Validators.valid(dto)

      either.mapLeft((error: Error) => {
        this._emiter.emit(left(error))
      })

      either.map(() => {
        this._api.fetch(dto).subscribe((e: Either<Error, boolean>) => {
          e.mapLeft((error: Error) => this._emiter.emit(left(error)))

          e.map((bool: boolean) => {
            this._emiter.emit(right(bool))
          })
        })
      })
    }
  }
}
