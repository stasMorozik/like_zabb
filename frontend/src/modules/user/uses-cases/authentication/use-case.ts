import { Observable } from "rxjs";
import { Either, left } from "@sweet-monads/either";

export namespace UseCase {
  export namespace Ports {
    export interface Api {
      fetch(dto: Dtos.RegistrationData): Observable<Either<Error, Dtos.SuccessRegistration>>
    }

    export interface Emiter {
      emit(either: Either<Error, Dtos.SuccessRegistration>): void
    }
  }
}
