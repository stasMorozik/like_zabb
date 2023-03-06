import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import { Errors as CommonErrors } from "../../../common/errors";
import { Dtos } from '../../../common/dtos';
import { Validators } from '../../../common/validators';

export namespace UseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.Email): Observable<Either<CommonErrors.ErrorI, boolean>>
    }

    export interface Emiter {
      emit(either: Either<CommonErrors.ErrorI, Dtos.Message>): void
    }
  }


  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    create(dto: Dtos.Email) {
      const either = Validators.Email(dto);

      either.mapLeft((error: CommonErrors.ErrorI) => {
        this._emiter.emit(left(error));
      });

      either.map(() => {
        this._api.fetch(dto).subscribe((e: Either<CommonErrors.ErrorI, boolean>) => {
          e.mapLeft((error: CommonErrors.ErrorI) => this._emiter.emit(left(error)));

          e.map((_: boolean) => {
            this._emiter.emit(right({message: 'You have successfully created confirmation code'}));
          });
        });
      });
    }
  }
}
