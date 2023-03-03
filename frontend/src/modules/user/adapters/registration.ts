import { Either } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { map, catchError, of, Subject, Observable } from 'rxjs';
import { Error } from "../../../use-cases/common/errors/error";
import { Registration as RegistrationUseCase } from "../../../use-cases/user/registration";

export namespace Registration {
  export class Emiter implements RegistrationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error[], RegistrationUseCase.Success>>
    ){}

    emit(either: Either<Error[], RegistrationUseCase.Success>): void {
      this._subject.next(either);
    }
  }

  export class Api implements RegistrationUseCase.Ports.Api {
    fetch(dto: RegistrationUseCase.Dto): Observable<Either<Error[], RegistrationUseCase.Success>> {
      return ajax({
        url: '/api/accounts/',
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: dto
      }).pipe(
        map(response => console.log('response: ', response)),
        catchError(error => {
          console.log('error: ', error);
          return of(error);
        })
      );
    }
  }
}
