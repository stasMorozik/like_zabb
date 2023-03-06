import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import { Validators } from "./validators";
import { Dtos as CommonDtos } from "../../../common/dtos";
import { Dtos } from "./dtos";
import { Errors as CommonErrors } from "../../../common/errors";

export namespace UseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.Data): Observable<Either<CommonErrors.ErrorI, boolean>>
    }

    export interface Emiter {
      emit(either: Either<CommonErrors.ErrorI, CommonDtos.Message>): void
    }
  }

  export class UseCase {
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){}

    confirm(dto: Dtos.Data) {
      const either = Validators.valid(dto);

      either.mapLeft((error: CommonErrors.ErrorI) => {
        this._emiter.emit(left(error));
      });

      either.map(() => {
        this._api.fetch(dto).subscribe((e: Either<CommonErrors.ErrorI, boolean>) => {
          e.mapLeft((error: CommonErrors.ErrorI) => this._emiter.emit(left(error)));

          e.map((_: boolean) => {
            this._emiter.emit(right({message: 'You have successfully confirmed email'}));
          });
        });
      });
    }
  }
}
