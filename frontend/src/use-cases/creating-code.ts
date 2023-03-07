import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import { SharedDtos } from '../use-cases/shared/dtos';
import { SharedValidators } from '../use-cases/shared/validators';

export namespace CreatingCodeUseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: SharedDtos.Email): Observable<Either<Error, boolean>>
    }

    export interface Emiter {
      emit(either: Either<Error, boolean>): void
    }
  }


  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    create(dto: SharedDtos.Email) {
      const either = SharedValidators.Email(dto);

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
