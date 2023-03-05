import { Observable } from "rxjs";
import { Either, left } from "@sweet-monads/either";
import { Validators } from "./validators";
import { Dtos } from "./dtos";
import { Errors as CommonErrors } from "../../../common/errors";

export namespace UseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.RegistrationData): Observable<Either<Error, Dtos.SuccessRegistration>>
    }

    export interface Emiter {
      emit(either: Either<Error, Dtos.SuccessRegistration>): void
    }
  }

  export class Registration {
    private readonly _validator: Validators.Registration;
    constructor(
      private readonly _api: Ports.Api,
      private readonly _emiter: Ports.Emiter
    ){
      this._validator = new Validators.Registration();
    }

    registry(dto: Dtos.RegistrationData) {
      const maybeValid = this._validator.valid(dto);

      maybeValid.mapLeft((error: CommonErrors.ErrorI) => {
        this._emiter.emit(left(error))
      });

      maybeValid.map(() => {
        this._api.fetch(dto).subscribe((maybeRight: Either<Error, Dtos.SuccessRegistration>) => {
          this._emiter.emit(maybeRight);
        });
      });
    }
  }
}
